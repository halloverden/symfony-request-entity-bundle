<?php

namespace HalloVerden\RequestEntityBundle\Requests;

use HalloVerden\RequestEntityBundle\Helpers\CollectionConstraintHelper;
use HalloVerden\RequestEntityBundle\Interfaces\RequestDataValidationOptionsInterface;
use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityInterface;
use JMS\Serializer\DeserializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;

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
   * @var Collection|null
   */
  private static $_collectionConstraint = null;

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

  /**
   * @inheritDoc
   */
  public static function createRequestDataValidationOptions(): RequestDataValidationOptionsInterface {
    return new RequestDataValidationOptions(static::getCollectionConstraint() ?: new Collection(['fields' => []]));
  }

  /**
   * @inheritDoc
   */
  public static function getAllowedAttributes(): ?array {
    if ($collectionConstraint = static::getCollectionConstraint()) {
      return CollectionConstraintHelper::getFields($collectionConstraint);
    }

    return null;
  }

  /**
   * @return Collection|null
   */
  protected final static function getCollectionConstraint(): ?Collection {
    return static::$_collectionConstraint ?: static::$_collectionConstraint = static::createCollectionConstraint();
  }

  /**
   * @return Collection|null
   */
  protected static function createCollectionConstraint(): ?Collection {
    return null;
  }

}
