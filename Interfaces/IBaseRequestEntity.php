<?php

namespace HalloVerden\RequestEntityBundle\Interfaces;

use HalloVerden\RequestEntityBundle\Requests\RequestEntityOptions;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface IBaseRequestEntity
 * @package App\Interfaces
 */
interface IBaseRequestEntity extends IRequestEntity {
  public static function create(array $data, Request $request, RequestEntityOptions $requestEntityOptions): self;
  public function getRequest(): ?Request;
}
