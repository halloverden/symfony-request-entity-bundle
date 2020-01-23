<?php


namespace HalloVerden\RequestEntityBundle\Exception;


use HalloVerden\HttpExceptions\BadRequestException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ValidationException
 *
 * @package HalloVerden\RequestEntityBundle\Exception
 */
class ValidationException extends BadRequestException {

  /**
   * ValidationException constructor.
   *
   * @param ConstraintViolationListInterface $violationList
   * @param string $message
   * @param \Exception|null $previous
   * @param int $code
   */
  public function __construct(ConstraintViolationListInterface $violationList, $message = "VALIDATION_ERROR", \Exception $previous = null, $code = 0) {
    $v = [];

    foreach ($violationList as $violation) {
      /* @var $violation ConstraintViolationInterface*/
      $d = new ValidationExceptionViolation($violation->getInvalidValue(), $violation->getMessage(), $violation->getPropertyPath());

      if ($violation instanceof ConstraintViolation) {
        $d->setErrorName($violation->getConstraint()::getErrorName($violation->getCode()));
      }

      $v[] = $d->toArray();
    }

    parent::__construct($message, [
      "violations" => $v
    ], $previous, $code);
  }

  /**
   * @return string
   */
  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message} -> " . json_encode($this->data) . ".";
  }

}
