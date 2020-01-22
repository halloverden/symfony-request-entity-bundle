<?php

namespace HalloVerden\RequestEntityBundle\Requests;

use HalloVerden\RequestEntityBundle\Interfaces\IRequestEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class AbstractRequestEntity
 *
 * @package HalloVerden\RequestEntityBundle\Requests
 */
abstract class AbstractRequestEntity implements IRequestEntity {

  /**
   * @var Request
   */
  private $_request;

  /**
   * @var ConstraintViolationListInterface
   */
  private $_requestEntityViolations;

  /**
   * @var RequestEntityOptions
   */
  private $_requestEntityOptions;

  /**
   * AbstractRequestEntity constructor.
   *
   * @param Request              $request
   * @param RequestEntityOptions $requestEntityOptions
   */
  protected function __construct(Request $request, RequestEntityOptions $requestEntityOptions) {
    $this->_request = $request;
    $this->_requestEntityOptions = $requestEntityOptions;
  }

  /**
   * @param array                $data
   * @param Request              $request
   * @param RequestEntityOptions $requestEntityOptions
   *
   * @return IRequestEntity
   *
   * @throws \ReflectionException
   */
  public static function create(array $data, Request $request, RequestEntityOptions $requestEntityOptions): IRequestEntity {
    if (static::class === self::class) {
      throw new \RuntimeException('Run this from a class that extends this class');
    }

    $requestEntity = new static($request, $requestEntityOptions);
    $requestEntity->setData($data);
    return $requestEntity;
  }

  /**
   * @return null|ConstraintViolationListInterface
   */
  public function getRequestEntityViolations(): ?ConstraintViolationListInterface {
    return $this->_requestEntityViolations;
  }

  /**
   * @param ConstraintViolationListInterface $violations
   */
  public function setRequestEntityViolations( ConstraintViolationListInterface $violations ): void {
    $this->_requestEntityViolations = $violations;
  }

  /**
   * @return RequestEntityOptions
   */
  public function getRequestEntityOptions(): RequestEntityOptions {
    return $this->_requestEntityOptions;
  }

  /**
   * @param array $data
   *
   * @throws \ReflectionException
   */
  protected function setData(array $data): void {
    $reflectionClass = new \ReflectionClass($this);
    while ($reflectionClass) {
      foreach ($reflectionClass->getProperties() as $property) {
        $this->setDataOnProperty($data, $property);
      }

      $reflectionClass = $reflectionClass->getParentClass();
    }
  }

  /**
   * @param array               $data
   * @param \ReflectionProperty $property
   */
  private function setDataOnProperty(array $data, \ReflectionProperty $property): void {
    $key = $property->getName();

    // Ignore properties that start with _
    if ($key[0] === '_') {
      return;
    }

    if (!isset($data[$key])) {
      return;
    }

    $property->setAccessible(true);
    $property->setValue($this, $data[$key]);
  }

  /**
   * @param array $data
   * @param array $propsToGet
   *
   * @return array
   */
  protected function fetchPropertiesArray(?array $data, array $propsToGet = null): array {
    if ($data === null) {
      return [];
    }

    if ($propsToGet === null) {
      return $data;
    }

    return array_intersect_key($data, array_flip($propsToGet));
  }

  /**
   * @param            $object
   * @param array|null $propsToGet
   *
   * @return array
   * @throws \ReflectionException
   */
  public function fetchProperties($object, array $propsToGet = null): array {
    $props = [];

    $reflectionClass = new \ReflectionClass($object);
    while ($reflectionClass) {
      foreach ($reflectionClass->getProperties() as $property) {
        $property->setAccessible(true);
        $props[$property->getName()] = $property->getValue();
      }

      $reflectionClass = $reflectionClass->getParentClass();
    }

    return $this->fetchPropertiesArray($props, $propsToGet);
  }

  /**
   * @return array
   * @throws \ReflectionException
   */
  public function getProperties(): array {
    return $this->fetchProperties($this);
  }

  /**
   * @return array
   */
  public static function getValidatorGroups(): array {
    return [];
  }
}
