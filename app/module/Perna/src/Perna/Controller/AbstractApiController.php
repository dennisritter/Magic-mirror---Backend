<?php

namespace Perna\Controller;

use Perna\Controller\Plugin\ExtractObject;
use ZfrRest\Mvc\Controller\AbstractRestfulController;
use ZfrRest\View\Model\ResourceViewModel;

/**
 * Base Controller class for whole API
 *
 * @author      Jannik Portz
 * @package     Perna\Controller
 *
 * @method      array extractObject(string $hydratorName, $object)
 * @property    $extractObject ExtractObject
 */
class AbstractApiController extends AbstractRestfulController {

	/**
	 * Creates a new view model with the default template
	 * @param     mixed     $data     The data for the view model
	 * @return    ResourceViewModel   The new view model
	 */
	protected function createDefaultViewModel ( $data = null ) {
		$content = [];
		if ( !$data !== null )
			$content['data'] = $data;

		$model = new ResourceViewModel( $content );
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