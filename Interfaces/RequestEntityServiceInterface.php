<?php

namespace HalloVerden\RequestEntityBundle\Interfaces;

use HalloVerden\HttpExceptions\Utility\ValidationException;
use Symfony\Component\HttpFoundation\Request;

interface RequestEntityServiceInterface {

  /**
   * @param Request $request
   * @param string $requestEntityClass FQCN of a class that implements {@link RequestEntityInterface}
   *
   * @return RequestEntityInterface
   * @throws ValidationException
   */
  public function createRequestEntity(Request $request, string $requestEntityClass): RequestEntityInterface;

}
