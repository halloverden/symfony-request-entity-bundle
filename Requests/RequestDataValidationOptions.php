<?php

namespace HalloVerden\RequestEntityBundle\Requests;

use HalloVerden\RequestEntityBundle\Interfaces\RequestDataValidationOptionsInterface;
use Symfony\Component\Validator\Constraint;

class RequestDataValidationOptions implements RequestDataValidationOptionsInterface {
  public function __construct(
    private readonly Constraint $dataConstraint,
    private readonly ?array     $dataValidatorGroups = null
  ) {
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
