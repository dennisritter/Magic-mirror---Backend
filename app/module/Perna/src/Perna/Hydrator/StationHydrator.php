<?php

namespace Perna\Hydrator;

use Perna\Document\Station;
use Perna\Service\PublicTransport\ProductsService;

class StationHydrator extends AbstractHydrator {

	/**
	 * @var ProductsService
	 */
	protected $productsService;

	public function __construct ( ProductsService $productsService ) {
		$this->productsService = $productsService;
	}

	/** @inheritdoc */
	public function extract( $object ) {
		/** @var Station $object */
		return [
			'id' => $object->getExtId(),
			'name' => $object->getName(),
			'location' => $object->getLocation(),
			'products' => $object->getProducts()
		];
	}

	/** @inheritdoc */
	public function hydrate( array $data, $object ) {
		/** @var Station $object */
		$object->setExtId( $data['extId'] );
		$object->setName( $data['name'] );
		$object->setLocation( [$data['lat'], $data['lon']] );
		$object->setProducts( $this->productsService->bitmapToProducts( $data['products'] ) );
		return $object;
	}
}