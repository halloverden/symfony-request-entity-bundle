<?php


namespace HalloVerden\RequestEntityBundle\Interfaces;


use Symfony\Component\Validator\ConstraintViolationListInterface;

interface IValidatableRequestEntity {
  public function getRequestEntityViolations(): ?ConstraintViolationListInterface;
  public function setRequestEntityViolations( ConstraintViolationListInterface $violations ): void;
  public static function getValidatorGroups(): array;
}
