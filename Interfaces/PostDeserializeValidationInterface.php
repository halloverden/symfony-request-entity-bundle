<?php

namespace HalloVerden\RequestEntityBundle\Interfaces;

interface PostDeserializeValidationInterface {

  /**
   * @return array|null
   */
  public function getDataValidatorGroups(): ?array;

}
