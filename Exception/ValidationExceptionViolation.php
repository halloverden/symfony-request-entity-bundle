<?php


namespace HalloVerden\RequestEntityBundle\Exception;

/**
 * Class ValidationExceptionViolation
 *
 * @package HalloVerden\RequestEntityBundle\Exception
 */
class ValidationExceptionViolation {
  /**
   * @var string|null
   */
  private $errorName;

  /**
   * @var mixed
   */
  private $invalidValue;

  /**
   * @var string|null
   */
  private $message;

  /**
   * @var string|null
   */
  private $propertyPath;

  /**
   * ValidationExceptionViolation constructor.
   *
   * @param $invalidValue
   * @param string $message
   * @param string $propertyPath
   */
  public function __construct($invalidValue, string $message, string $propertyPath) {
    $this->invalidValue = $invalidValue;
    $this->message = $message;
    $this->propertyPath = $propertyPath;
  }

  /**
   * @return string|null
   */
  public function getErrorName(): ?string {
    return $this->errorName;
  }

  /**
   * @param string|null $errorName
   */
  public function setErrorName(?string $errorName): void {
    $this->errorName = $errorName;
  }

  /**
   * @return mixed
   */
  public function getInvalidValue() {
    return $this->invalidValue;
  }

  /**
   * @param mixed $invalidValue
   */
  public function setInvalidValue($invalidValue): void {
    $this->invalidValue = $invalidValue;
  }

  /**
   * @return string|null
   */
  public function getMessage(): ?string {
    return $this->message;
  }

  /**
   * @param string|null $message
   */
  public function setMessage(?string $message): void {
    $this->message = $message;
  }

  /**
   * @return string|null
   */
  public function getPropertyPath(): ?string {
    return $this->propertyPath;
  }

  /**
   * @return array
   */
  public function toArray(): array {
    $a = [
      'invalidValue' => $this->getInvalidValue(),
      'message' => $this->getMessage(),
      'propertyPath' => $this->getPropertyPath()
    ];

    if ($this->getErrorName()) {
      $a['errorName'] = $this->getErrorName();
    }

    return $a;
  }
}
