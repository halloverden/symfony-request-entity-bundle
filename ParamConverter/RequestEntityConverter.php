<?php

namespace HalloVerden\RequestEntityBundle\ParamConverter;

use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityInterface;
use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestEntityConverter
 * @package App\ParamConverters
 */
class RequestEntityConverter implements ParamConverterInterface {

  /**
   * @var RequestEntityServiceInterface
   */
  private $requestEntityService;

  /**
   * RequestEntityConverter constructor.
   *
   * @param RequestEntityServiceInterface $requestEntityService
   */
  public function __construct(RequestEntityServiceInterface $requestEntityService) {
    $this->requestEntityService = $requestEntityService;
  }

  /**
   * @inheritDoc
   */
  public function supports( ParamConverter $configuration ): bool {
    return \is_subclass_of($configuration->getClass(), RequestEntityInterface::class);
  }

  /**
   * @inheritDoc
   */
  public function apply( Request $request, ParamConverter $configuration ): bool {
    $requestEntity = $this->requestEntityService->createRequestEntity($request, $configuration->getClass());

    $request->attributes->set($configuration->getName(), $requestEntity);

    return true;
  }

}
