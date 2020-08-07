<?php

namespace HalloVerden\RequestEntityBundle\Requests;

use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityInterface;
use JMS\Serializer\DeserializationContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractRequestEntity
 *
 * @package HalloVerden\RequestEntityBundle\Requests
 */
abstract class AbstractRequestEntity implements RequestEntityInterface {

  /**
   * @var Request
   */
  private $_request;

  /**
   * @inheritDoc
   */
  public function getRequest(): ?Request {
    return $this->_request;
  }

  /**
   * @param Request $request
   */
  public function setRequest(Request $request): void {
    $this->_request = $request;
  }

  /**
   * @inheritDoc
   */
  public static function createRequestEntityOptions(): RequestEntityOptions {
    return RequestEntityOptions::create();
  }

  /**
   * @inheritDoc
   */
  public static function createDeserializationContext(): DeserializationContext {
    return DeserializationContext::create();
  }

}
