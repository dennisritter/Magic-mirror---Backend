<?php

namespace Perna\Doc;

use Swagger\Annotations as SWG;

/**
 * Class SuccessResponse
 *
 * @SWG\Definition(
 *   definition="ResponseError",
 *   @SWG\Property(property="status_code", type="number", format="int32", default=400),
 *   @SWG\Property(property="message", type="string", description="the error message")
 * )
 *
 * @SWG\Definition(
 *   @SWG\Xml(name="ResponseSuccess")
 * )
 */
class ResponseSuccess {

	/**
	 * @SWG\Property()
	 * @var       bool
	 */
	protected $success = true;

}