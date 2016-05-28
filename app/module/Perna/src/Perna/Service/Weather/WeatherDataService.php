<?php

namespace Perna\Service\Weather;


use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\City;
use Perna\Document\Weather\CurrentWeatherData;
use Perna\Document\Weather\TemporalWeatherData;
use Perna\Document\Weather\WeatherDataCache;
use Perna\Exception\WeatherDataAccessException;
use ZfrRest\Http\Exception\Client\NotFoundException;
use ZfrRest\Http\Exception\Server\ServiceUnavailableException;

/**
 * Service responsible for Weather data
 *
 * @author      Jannik Portz
 * @package     Perna\Service\Weather
 */
class WeatherDataService {

	/**
	 * @var       WeatherDataAccessService
	 */
	protected $accessService;

	/**
	 * @var       DocumentManager
	 */
	protected $documentManager;

	public function __construct ( WeatherDataAccessService $accessService, DocumentManager $documentManager ) {
		$this->accessService = $accessService;
		$this->documentManager = $documentManager;
	}

	/**
	 * Retrieves weather data for the specified location
	 * @param     int       $location The id of the location for which to retrieve weather data
	 * @return    WeatherDataCache    The WeatherDataCache containing all weather data
	 *
	 * @throws    NotFoundException   If no location could be found
	 * @throws    ServiceUnavailableException If an error occurred while fetching weather data
	 */
	public function getWeatherData ( int $location ) : WeatherDataCache {
		$city = $this->documentManager->getRepository( City::class )->find( $location );

		if ( !$city instanceof City )
			throw new NotFoundException("The weather location with id {$location} could not be found.");

		$cache = $city->getWeatherDataCache();
		if ( !$cache instanceof WeatherDataCache ) {
			$cache = new WeatherDataCache();
			$city->setWeatherDataCache( $cache );
		}

		$changed = $this->updateCurrentWeatherData( $city->getId(), $cache );
		$changed = $this->updateTodayWeatherData( $city->getId(), $cache ) || $changed;
		$changed = $this->updateDailyWeatherData( $city->getId(), $cache ) || $changed;

		if ( $changed )
			$this->documentManager->flush();

		return $cache;
	}

	/**
	 * Updates the cache with current weather data
	 * @param     int               $location The location id
	 * @param     WeatherDataCache  $cache    The cache whose data to update
	 *
	 * @return    bool                        Whether new data has been fetched
	 * @throws    ServiceUnavailableException If an error occurred while fetching the data and no old data is available
	 */
	protected function updateCurrentWeatherData ( int $location, WeatherDataCache $cache ) : bool {
		$hasData = $cache->getCurrent() instanceof CurrentWeatherData;
		if ( $hasData && $cache->getFetchedCurrent() >= (new \DateTime('now'))->sub( new \DateInterval('PT15M') ) )
			return false;

		try {
			$data = $this->accessService->getCurrentWeatherData( $location );
			$cache->setCurrent( $data );
			$cache->setFetchedCurrent( new \DateTime('now') );
			return true;
		} catch ( WeatherDataAccessException $e ) {
			if ( !$hasData )
				throw new ServiceUnavailableException();

			return false;
		}
	}

	/**
	 * Updates the cache with today's weather data
	 * @param     int               $location The location id
	 * @param     WeatherDataCache  $cache    The cache whose data to update
	 *
	 * @return    bool                        Whether new data has been fetched
	 * @throws    ServiceUnavailableException If an error occurred while fetching the data and no old data is available
	 */
	protected function updateTodayWeatherData ( int $location, WeatherDataCache $cache ) : bool {
		$today = $cache->getToday();
		$hasData = ($today instanceof Collection && $today->count() > 0) || ( is_array( $today ) && count( $today ) > 0 );
		if ( $hasData // If data is already present
		      && $cache->getFetchedToday() >= (new \DateTime('now'))->sub( new \DateInterval('PT30M') ) // AND the cached data is fresh enough
					&& $cache->getFetchedToday() >= (new \DateTime('now'))->setTime(0,0,0) ) // AND the cached data has not been fetched yesterday
			return false;

		try {
			$data = $this->accessService->getTemporalWeatherData( $location );
			$last = new \DateTime('now');
			$last->setTime(23,59,59);

			// Remove weather data that is not for today
			$weatherData = array_filter( $data, function ( int $i, TemporalWeatherData $wd ) use ( $last ) {
				return $wd->getDateTime() <= $last;
			}, ARRAY_FILTER_USE_BOTH);

			$cache->setToday( $weatherData );
			$cache->setFetchedToday( new \DateTime('now') );
			return true;
		} catch ( WeatherDataAccessException $e ) {
			if ( !$hasData )
				throw new ServiceUnavailableException();

			return false;
		}
	}

	/**
	 * Updates the cache with daily weather data
	 * @param     int               $location The location id
	 * @param     WeatherDataCache  $cache    The cache whose data to update
	 *
	 * @return    bool                        Whether new data has been fetched
	 * @throws    ServiceUnavailableException If an error occurred while fetching the data and no old data is available
	 */
	protected function updateDailyWeatherData ( int $location, WeatherDataCache $cache ) : bool {
		$daily = $cache->getDaily();
		$hasData = ($daily instanceof Collection && $daily->count() > 0) || ( is_array( $daily ) && count( $daily ) > 0 );
		if ( $hasData // If data is already present
		     && $cache->getFetchedDaily() >= (new \DateTime('now'))->sub( new \DateInterval('PT2H') ) // AND the cached data is fresh enough
		     && $cache->getFetchedDaily() >= (new \DateTime('now'))->setTime(0,0,0) ) // AND the cached data has not been fetched yesterday
			return false;

		try {
			$data = $this->accessService->getDailyWeatherData( $location );
			$cache->setDaily( $data );
			$cache->setFetchedDaily( new \DateTime('now') );
			return true;
		} catch ( WeatherDataAccessException $e ) {
			if ( !$hasData )
				throw new ServiceUnavailableException();

			return false;
		}
	}
}