<?php


namespace HalloVerden\RequestEntityBundle\Interfaces;


use Symfony\Component\Validator\ConstraintViolationListInterface;

interface IValidatableRequestEntity {

  /**
   * @return ConstraintViolationListInterface|null
   */
  public function getRequestEntityViolations(): ?ConstraintViolationListInterface;

  /**
   * @param ConstraintViolationListInterface $violations
   */
  public function setRequestEntityViolations( ConstraintViolationListInterface $violations ): void;

  /**
   * @return array
   */
  public static function getValidatorGroups(): array;
}
