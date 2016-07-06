<?php

namespace Perna\Hydrator;

use Perna\Document\Departure;
use Perna\Service\PublicTransport\ProductsService;

class DepartureHydrator extends AbstractHydrator {

	/**
	 * @var ProductsService
	 */
	protected $productsService;

	public function __construct ( ProductsService $productsService ) {
		$this->productsService = $productsService;
	}

	/** @inheritdoc */
	public function extract ( $object ) {
		/** @var Departure $object */
		return [
			'product' => $object->getProduct(),
			'name' => $object->getName(),
			'direction' => $object->getDirection(),
			'realTime' => $this->extractDateTime( $object->getRealTime() ),
			'scheduledTime' => $this->extractDateTime( $object->getScheduledTime() )
		];
	}

	/**
	 * @inheritdoc
	 * @throws    \InvalidArgumentException If the departure could not be hydrated due to unsupported product class
	 */
	public function hydrate ( array $data, $object ) {
		/** @var Departure $object */
		$p = $this->productsService->parseProduct( $data['Product']['catOutL'] );
		$object->setProduct( $p );
		$name = trim( $data['Product']['line'] );
		$object->setName( $name );
		$object->setDirection( $data['direction'] );
		$object->setScheduledTime( new \DateTime( $data['date'].' '.$data['time'] ) );
		$realTime = ( array_key_exists('rtDate', $data) && array_key_exists('rtTime', $data) )
			? new \DateTime($data['rtDate'].' '.$data['rtTime'])
			: $object->getScheduledTime();
		$object->setRealTime( $realTime );
		return $object;
	}
}