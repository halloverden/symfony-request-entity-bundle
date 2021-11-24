<?php


namespace HalloVerden\RequestEntityBundle\Interfaces;


/**
 * Interface PostDeserializeValidationInterface
 *
 * @package HalloVerden\RequestEntityBundle\Interfaces
 */
interface PostDeserializeValidationInterface {

  /**
   * @return array|null
   */
  public function getDataValidatorGroups(): ?array;

}
