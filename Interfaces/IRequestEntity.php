<?php

namespace HalloVerden\RequestEntityBundle\Interfaces;

use HalloVerden\RequestEntityBundle\Requests\RequestEntityOptions;

interface IRequestEntity {
  public function getRequestEntityOptions(): RequestEntityOptions;
  public function getProperties(): array;
}
