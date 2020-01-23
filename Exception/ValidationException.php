<?php


namespace HalloVerden\RequestEntityBundle\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ValidationException
 *
 * @package HalloVerden\RequestEntityBundle\Exception
 */
class ValidationException extends \Exception {
  const ERROR_VALIDATION_FAILED = 'VALIDATION_FAILED';

  /**
   * @var ConstraintViolationListInterface
   */
  private $violations;

  /**
   * ValidationException constructor.
   *
   * @param ConstraintViolationListInterface $violations
   * @param int                              $code
   * @param \Throwable|null                  $previous
   */
  public function __construct(ConstraintViolationListInterface $violations, $code = 0, \Throwable $previous = null) {
    parent::__construct(self::ERROR_VALIDATION_FAILED, $code, $previous);
    $this->violations = $violations;
  }

  /**
   * @return ConstraintViolationListInterface
   */
  public function getViolations(): ConstraintViolationListInterface {
    return $this->violations;
  }

}
