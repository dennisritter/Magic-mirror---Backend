<?php

namespace Perna\Controller;

use ZfrRest\Mvc\Controller\AbstractRestfulController;
use ZfrRest\View\Model\ResourceViewModel;

class AbstractApiController extends AbstractRestfulController {

	/**
	 * Creates a new view model with the default template
	 * @param     mixed     $data     The data for the view model
	 * @return    ResourceViewModel   The new view model
	 */
	protected function createDefaultViewModel ( $data ) {
		$model = new ResourceViewModel( [
			'data' => $data
		] );
		$model->setTemplate( 'default' );
		return $model;
	}

	public function options () {
		$response = parent::options();
		$methods = $response->getHeaders()->get('Allow');
		$response->getHeaders()->addHeaderLine('Access-Control-Allow-Methods', $methods->getFieldValue() );
		return $response;
	}

}