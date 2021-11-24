<?php


namespace HalloVerden\RequestEntityBundle\Services;


use HalloVerden\HttpExceptions\Utility\ValidationException;
use HalloVerden\RequestEntityBundle\Event\PreRequestEntityDeserializationEvent;
use HalloVerden\RequestEntityBundle\Interfaces\PostDeserializeValidationInterface;
use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityInterface;
use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityServiceInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class RequestEntityService
 *
 * @package HalloVerden\RequestEntityBundle\Services
 */
class RequestEntityService implements RequestEntityServiceInterface {

  /**
   * @var SerializerInterface
   */
  private $serializer;

  /**
   * @var EventDispatcherInterface
   */
  private $dispatcher;

  /**
   * @var ValidatorInterface|null
   */
  private $validator;

  /**
   * RequestEntityConverter constructor.
   *
   * @param SerializerInterface      $serializer
   * @param EventDispatcherInterface $dispatcher
   * @param ValidatorInterface  $validator
   */
  public function __construct(SerializerInterface $serializer, EventDispatcherInterface $dispatcher, ValidatorInterface $validator) {
    $this->serializer = $serializer;
    $this->dispatcher = $dispatcher;
    $this->validator = $validator;
  }

  /**
   * @inheritDoc
   */
  public function createRequestEntity(Request $request, string $requestEntityClass): RequestEntityInterface {
    if (!\is_subclass_of($requestEntityClass, RequestEntityInterface::class)) {
      throw new \LogicException(\sprintf('%s is not subclass of %s', $requestEntityClass, RequestEntityInterface::class));
    }

    $data = $this->createDataArray($request, $requestEntityClass);

    $this->validateData($data, $requestEntityClass);

    return $this->_createRequestEntity($data, $request, $requestEntityClass);
  }

  /**
   * @param Request $request
   * @param string  $requestEntityClass
   *
   * @return array
   */
  private function createDataArray(Request $request, string $requestEntityClass): array {
    /** @var RequestEntityInterface|string $requestEntityClass */
    $requestEntityOptions = $requestEntityClass::createRequestEntityOptions();

    if (null !== $rootElement = $requestEntityOptions->getRootElement()) {
      $data = $request->request->get( $rootElement ) ?? [];
    } else {
      $data = $request->request->all();
    }

    if ( $requestEntityOptions->combineQueryAndBody() ) {
      $data = array_merge($request->query->all(), $data);
    }

    return $this->filterData($data, $requestEntityClass);
  }

  /**
   * @param array  $data
   * @param string $requestEntityClass
   */
  private function validateData(array $data, string $requestEntityClass): void {
    /** @var RequestEntityInterface $requestEntityClass only as string! */
    $validationOptions = $requestEntityClass::createRequestDataValidationOptions();

    $violations = $this->validator->validate($data, $validationOptions->getDataConstraint(), $validationOptions->getDataValidatorGroups());

    if (0 !== count($violations)) {
      throw new ValidationException($violations);
    }
  }

  /**
   * @param array   $data
   * @param Request $request
   * @param string  $requestEntityClass
   *
   * @return RequestEntityInterface
   */
  private function _createRequestEntity(array $data, Request $request, string $requestEntityClass): RequestEntityInterface {
    /** @var RequestEntityInterface|string $requestEntityClass */
    $context = $requestEntityClass::createDeserializationContext();

    $event = new PreRequestEntityDeserializationEvent(
      $context,
      $requestEntityClass,
      $data
    );
    $this->dispatcher->dispatch($event);

    /** @var RequestEntityInterface $requestEntity */
    $requestEntity = $this->serializer->deserialize(json_encode($event->getData()), $event->getClass(), 'json', $event->getContext());

    $requestEntity->setRequest($request);

    if ($requestEntity instanceof PostDeserializeValidationInterface) {
      $this->validator->validate(
        $requestEntity,
        $requestEntity->getPostDeserializeValidationOptions()->getDataConstraint(),
        $requestEntity->getPostDeserializeValidationOptions()->getDataValidatorGroups()
      );
    }

    return $requestEntity;
  }

  /**
   * @param array  $data
   * @param string $requestEntityClass
   *
   * @return array
   */
  private function filterData(array $data, string $requestEntityClass): array {
    /** @var RequestEntityInterface $requestEntityClass only as string! */
    $allowedAttributes = $requestEntityClass::getAllowedAttributes();

    // if null, allow everything.
    if (null === $allowedAttributes) {
      return $data;
    }

    return $this->_filterData($data, $allowedAttributes);
  }

  /**
   * @param array $data
   * @param array $allowedAttributes
   *
   * @return array
   */
  private function _filterData(array $data, array $allowedAttributes): array {
    $filteredData = [];

    foreach ($data as $key => $value) {
      if (in_array($key, $allowedAttributes, true)) {
        $filteredData[$key] = $value;
      } elseif (isset($allowedAttributes[$key]) && is_array($allowedAttributes[$key]) && is_array($value)) {
        $filteredData[$key] = $this->_filterData($value, $allowedAttributes[$key]);
      }
    }

    return $filteredData;
  }

}
