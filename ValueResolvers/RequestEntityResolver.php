<?php

namespace HalloVerden\RequestEntityBundle\ValueResolvers;

use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityInterface;
use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RequestEntityResolver implements ValueResolverInterface {
  private RequestEntityServiceInterface $requestEntityService;

  /**
   * @param RequestEntityServiceInterface $requestEntityService
   */
  public function __construct(RequestEntityServiceInterface $requestEntityService) {
    $this->requestEntityService = $requestEntityService;
  }

  public function resolve(Request $request, ArgumentMetadata $argument): iterable {
    $argumentType = $argument->getType();
    if (
      !$argumentType
      || !is_subclass_of($argumentType, RequestEntityInterface::class, true)
    ) {
      return [];
    }
    return [$this->requestEntityService->createRequestEntity($request, $argumentType)];
  }
}
