<?php


namespace HalloVerden\RequestEntityBundle\Requests;


use HalloVerden\RequestEntityBundle\Interfaces\RequestDataValidationOptionsInterface;
use Symfony\Component\Validator\Constraint;

/**
 * Class RequestEntityValidationData
 *
 * @package HalloVerden\RequestEntityBundle\Requests
 */
class RequestDataValidationOptions implements RequestDataValidationOptionsInterface {

  /**
   * @var array|null
   */
  private $dataValidatorGroups;

  /**
   * @var Constraint
   */
  private $dataConstraint;

  /**
   * RequestEntityValidationData constructor.
   *
   * @param Constraint $dataConstraint
   * @param array|null $dataValidatorGroups
   */
  public function __construct(Constraint $dataConstraint, ?array $dataValidatorGroups = null) {
    $this->dataConstraint = $dataConstraint;
    $this->dataValidatorGroups = $dataValidatorGroups;
  }

  /**
   * @inheritDoc
   */
  public function getDataValidatorGroups(): ?array {
    return $this->dataValidatorGroups;
  }

  /**
   * @inheritDoc
   */
  public function getDataConstraint(): Constraint {
    return $this->dataConstraint;
  }

}
