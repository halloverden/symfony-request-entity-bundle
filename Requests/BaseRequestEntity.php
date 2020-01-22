<?php

namespace HalloVerden\RequestEntityBundle\Requests;

use HalloVerden\RequestEntityBundle\Interfaces\IBaseRequestEntity;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseRequestEntity
 * @package App\Entity\Requests
 */
abstract class BaseRequestEntity extends AbstractRequestEntity implements IBaseRequestEntity {
  /**
   * @var Request
   */
  private $_request;

  /**
   * BaseRequestEntity constructor.
   *
   * Private to make sure create is used.
   *
   * @param Request              $request
   * @param RequestEntityOptions $requestEntityOptions
   */
  protected final function __construct(Request $request, RequestEntityOptions $requestEntityOptions) {
    $this->_request = $request;
    parent::__construct($requestEntityOptions);
  }


  /**
   * @param array                $data
   * @param Request              $request
   * @param RequestEntityOptions $requestEntityOptions
   *
   * @return IBaseRequestEntity
   *
   * @throws \ReflectionException
   */
  public static function create(array $data, Request $request, RequestEntityOptions $requestEntityOptions): IBaseRequestEntity {
    if (static::class === self::class) {
      throw new \Exception('Run this from a class that extends this class');
    }

    $requestEntity = new static($request, $requestEntityOptions);
    $requestEntity->setData($data);
    return $requestEntity;
  }

  /**
   * @return Request
   */
  public function getRequest(): Request {
    return $this->_request;
  }

  /**
   * @return array
   * @throws \ReflectionException
   */
  public function getAccessDefinitionProperties(): array {
    return $this->getProperties();
  }


}
