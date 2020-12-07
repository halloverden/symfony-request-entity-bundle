<?php

namespace HalloVerden\RequestEntityBundle\Interfaces;

use HalloVerden\RequestEntityBundle\Requests\RequestEntityOptions;
use JMS\Serializer\DeserializationContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface RequestEntityInterface
 *
 * @package HalloVerden\RequestEntityBundle\Interfaces
 */
interface RequestEntityInterface {

  /**
   * @return Request|null
   */
  public function getRequest(): ?Request;

  /**
   * @param Request $request
   */
  public function setRequest(Request $request): void;

  /**
   * @return RequestEntityOptions
   */
  public static function createRequestEntityOptions(): RequestEntityOptions;

  /**
   * @return DeserializationContext
   */
  public static function createDeserializationContext(): DeserializationContext;

  /**
   * @return RequestDataValidationOptionsInterface
   */
  public static function createRequestDataValidationOptions(): RequestDataValidationOptionsInterface;

  /**
   * @return array|null
   */
  public static function getAllowedAttributes(): ?array;

}
