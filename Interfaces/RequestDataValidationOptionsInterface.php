<?php


namespace HalloVerden\RequestEntityBundle\Interfaces;


use Symfony\Component\Validator\Constraint;

/**
 * Interface RequestDataValidationInterface
 *
 * @package HalloVerden\RequestEntityBundle\Interfaces
 */
interface RequestDataValidationOptionsInterface {

  /**
   * The validation groups to use when validating data
   *
   * @return string[]|null
   */
  public function getDataValidatorGroups(): ?array;

  /**
   * The constraint to validate the data array with.
   *
   * @return Constraint
   */
  public function getDataConstraint(): Constraint;

}
