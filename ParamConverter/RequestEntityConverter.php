<?php

namespace HalloVerden\RequestEntityBundle\ParamConverter;

use HalloVerden\HttpExceptions\Utility\ValidationException;
use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityInterface;
use HalloVerden\RequestEntityBundle\Interfaces\ValidatableRequestEntityInterface;
use HalloVerden\RequestEntityBundle\Requests\RequestEntityOptions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RequestEntityConverter
 * @package App\ParamConverters
 */
class RequestEntityConverter implements ParamConverterInterface {

  /**
   * @var ValidatorInterface
   */
  private $validator;

  /**
   * RequestEntityConverter constructor.
   *
   * @param ValidatorInterface $validator
   */
  public function __construct(ValidatorInterface $validator = null ) {
    $this->validator = $validator;
  }

  /**
   * @inheritDoc
   * @throws ValidationException
   */
  public function apply( Request $request, ParamConverter $configuration ) {
    $requestEntityOptions = isset($configuration->getOptions()['requestEntityOptions']) ?
      new RequestEntityOptions($configuration->getOptions()['requestEntityOptions']) : new RequestEntityOptions();

    $data = $request->request->get( $configuration->getName() );

    if ( $requestEntityOptions->getRootElement() ) {
      $data = $request->request->get( $requestEntityOptions->getRootElement() );
    }

    if ( !$data && !$requestEntityOptions->preventEntireBody() ) {
      $data = $request->request->all();
    }

    if ( $requestEntityOptions->combineQueryAndBody() ) {
      $data = array_merge($request->query->all(), ($data ?: []));
    }

    $data = $data ?: [];

    $requestEntityClass = $configuration->getClass();
    /** @var RequestEntityInterface $requestEntityClass */
    $class = $requestEntityClass::create($data, $request, $requestEntityOptions);

    if ($class instanceof ValidatableRequestEntityInterface) {
      if (!$this->validator) {
        throw new \RuntimeException('You need to have symfony/validator installed to validate the request');
      }

      $violations = $this->inputValidation($class, $requestEntityOptions);

      if (0 !== count($violations)) {
        $class->setRequestEntityViolations($violations);
      }
    }

    $request->attributes->set($configuration->getName(), $class);

    return true;
  }

  /**
   * @inheritDoc
   */
  public function supports( ParamConverter $configuration ) {
    if (!class_exists($configuration->getClass())) {
      return false;
    }

    $implements = class_implements($configuration->getClass());

    if (!$implements) {
      return false;
    }

    return array_search(RequestEntityInterface::class, $implements) !== false;
  }

  /**
   * @param ValidatableRequestEntityInterface $entity
   * @param RequestEntityOptions              $requestEntityOptions
   *
   * @return ConstraintViolationListInterface
   * @throws ValidationException
   */
  private function inputValidation(ValidatableRequestEntityInterface $entity, RequestEntityOptions $requestEntityOptions): ConstraintViolationListInterface {
    $violations = $this->validator->validate($entity, null, $entity::getValidatorGroups());

    if (0 !== count($violations) && $requestEntityOptions->isThrowViolations()) {
      throw new ValidationException($violations);
    }

    return $violations;
  }

}
