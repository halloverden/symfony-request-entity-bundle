<?php


namespace HalloVerden\RequestEntityBundle\Services;


use HalloVerden\HttpExceptions\Utility\ValidationException;
use HalloVerden\RequestEntityBundle\Event\PreRequestEntityDeserializationEvent;
use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityInterface;
use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityServiceInterface;
use HalloVerden\RequestEntityBundle\Requests\RequestEntityOptions;
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

    $requestEntityOptions = $requestEntityClass::createRequestEntityOptions();

    $data = $this->createDataArray($request, $requestEntityOptions);

    $this->validateData($requestEntityClass, $data);

    return $this->_createRequestEntity($request, $requestEntityClass, $data);
  }

  /**
   * @param Request              $request
   * @param RequestEntityOptions $requestEntityOptions
   *
   * @return array
   */
  private function createDataArray(Request $request, RequestEntityOptions $requestEntityOptions): array {
    if (null !== $rootElement = $requestEntityOptions->getRootElement()) {
      $data = $request->request->get( $rootElement ) ?? [];
    } else {
      $data = $request->request->all();
    }

    if ( $requestEntityOptions->combineQueryAndBody() ) {
      $data = array_merge($request->query->all(), $data);
    }

    return $data;
  }

  /**
   * @param string $requestEntityClass
   * @param array  $data
   */
  private function validateData(string $requestEntityClass, array $data): void {
    /** @var RequestEntityInterface $requestEntityClass only as string! */
    $validationOptions = $requestEntityClass::createRequestDataValidationOptions();

    $violations = $this->validator->validate($data, $validationOptions->getDataConstraint(), $validationOptions->getDataValidatorGroups());

    if (0 !== count($violations)) {
      throw new ValidationException($violations);
    }
  }

  /**
   * @param Request $request
   * @param string  $requestEntityClass
   * @param array   $data
   *
   * @return RequestEntityInterface
   */
  private function _createRequestEntity(Request $request, string $requestEntityClass, array $data): RequestEntityInterface {
    /** @var RequestEntityInterface $requestEntityClass only as string! */
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

    return $requestEntity;
  }

}
