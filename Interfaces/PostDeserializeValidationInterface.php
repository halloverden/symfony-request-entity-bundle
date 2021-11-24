<?php


namespace HalloVerden\RequestEntityBundle\Interfaces;


use HalloVerden\HttpExceptions\Utility\ValidationException;

/**
 * Interface PostDeserializeValidationInterface
 *
 * @package HalloVerden\RequestEntityBundle\Interfaces
 */
interface PostDeserializeValidationInterface {

  /**
   * @return RequestDataValidationOptionsInterface
   */
  public function getPostDeserializeValidationOptions(): RequestDataValidationOptionsInterface;

}
