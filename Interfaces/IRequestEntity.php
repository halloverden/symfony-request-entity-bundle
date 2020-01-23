<?php

namespace HalloVerden\RequestEntityBundle\Interfaces;

use HalloVerden\RequestEntityBundle\Requests\RequestEntityOptions;
use Symfony\Component\HttpFoundation\Request;

interface IRequestEntity {

  /**
   * @param array                $data
   * @param Request              $request
   * @param RequestEntityOptions $requestEntityOptions
   *
   * @return static
   */
  public static function create(array $data, Request $request, RequestEntityOptions $requestEntityOptions): self;

  /**
   * @return Request|null
   */
  public function getRequest(): ?Request;

  /**
   * @return RequestEntityOptions
   */
  public function getRequestEntityOptions(): RequestEntityOptions;

  /**
   * @return array
   */
  public function getProperties(): array;
}
