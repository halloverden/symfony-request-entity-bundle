<?php

namespace HalloVerden\RequestEntityBundle\Interfaces;

use HalloVerden\RequestEntityBundle\Requests\RequestEntityOptions;
use Symfony\Component\HttpFoundation\Request;

interface IRequestEntity {
  public static function create(array $data, Request $request, RequestEntityOptions $requestEntityOptions): self;
  public function getRequest(): ?Request;
  public function getRequestEntityOptions(): RequestEntityOptions;
  public function getProperties(): array;
}
