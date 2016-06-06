<?php

namespace Perna\Service\Weather;


use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\City;
use Perna\Document\CurrentWeatherData;
use Perna\Document\TemporalWeatherData;
use Perna\Document\WeatherDataCache;
use Perna\Exception\WeatherDataAccessException;
use Zend\Stdlib\DateTime;
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

	/**
	 * @var       GeoNamesAccessService
	 */
	protected $geoNamesService;

	public function __construct ( WeatherDataAccessService $accessService, DocumentManager $documentManager, GeoNamesAccessService $geoNamesService ) {
		$this->accessService = $accessService;
		$this->documentManager = $documentManager;
		$this->geoNamesService = $geoNamesService;
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
		$city = $this->geoNamesService->getCityById( $location );

		$cache = $city->getWeatherDataCache();
		if ( !$cache instanceof WeatherDataCache ) {
			$cache = new WeatherDataCache();
			$city->setWeatherDataCache( $cache );
		}

		$changed = $this->updateCurrentWeatherData( $city, $cache );
		$changed = $this->updateTodayWeatherData( $city, $cache ) || $changed;
		$changed = $this->updateDailyWeatherData( $city, $cache ) || $changed;

		if ( $changed )
			$this->documentManager->flush();

		return $cache;
	}

	/**
	 * Updates the cache with current weather data
	 * @param     City              $location The weather location for which to retrieve the results
	 * @param     WeatherDataCache  $cache    The cache whose data to update
	 *
	 * @return    bool                        Whether new data has been fetched
	 * @throws    ServiceUnavailableException If an error occurred while fetching the data and no old data is available
	 */
	protected function updateCurrentWeatherData ( City $location, WeatherDataCache $cache ) : bool {
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
	 * @param     City              $location The weather location for which to retrieve the results
	 * @param     WeatherDataCache  $cache    The cache whose data to update
	 *
	 * @return    bool                        Whether new data has been fetched
	 * @throws    ServiceUnavailableException If an error occurred while fetching the data and no old data is available
	 */
	protected function updateTodayWeatherData ( City $location, WeatherDataCache $cache ) : bool {
		$today = $cache->getToday();
		$hasData = ($today instanceof Collection && $today->count() > 0) || ( is_array( $today ) && count( $today ) > 0 );
		if ( $hasData // If data is already present
		      && $cache->getFetchedToday() >= (new \DateTime('now'))->sub( new \DateInterval('PT30M') ) // AND the cached data is fresh enough
					&& $cache->getFetchedToday() >= (new \DateTime('now'))->setTime(0,0,0) ) // AND the cached data has not been fetched yesterday
			return false;

		try {
			$data = $this->accessService->getTemporalWeatherData( $location );
			$now = new \DateTime('now');
			$last = new \DateTime('now');
			$last->setTime(23,59,59);

			$weatherData = [];
			for ( $i = 0; $i < count( $data ) && count( $weatherData ) < 5; ++$i ) {
				$wd = $data[$i];
				if ( $wd->getDateTime() >= $now )
					$weatherData[] = $wd;
			}

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
	 * @param     City              $location The weather location for which to retrieve the results
	 * @param     WeatherDataCache  $cache    The cache whose data to update
	 *
	 * @return    bool                        Whether new data has been fetched
	 * @throws    ServiceUnavailableException If an error occurred while fetching the data and no old data is available
	 */
	protected function updateDailyWeatherData ( City $location, WeatherDataCache $cache ) : bool {
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