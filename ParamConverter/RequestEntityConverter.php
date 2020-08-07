<?php

namespace HalloVerden\RequestEntityBundle\ParamConverter;

use HalloVerden\HttpExceptions\Utility\ValidationException;
use HalloVerden\RequestEntityBundle\Event\PreRequestEntityDeserializationEvent;
use HalloVerden\RequestEntityBundle\Interfaces\RequestEntityInterface;
use HalloVerden\RequestEntityBundle\Requests\RequestEntityOptions;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class RequestEntityConverter
 * @package App\ParamConverters
 */
class RequestEntityConverter implements ParamConverterInterface {

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
  public function supports( ParamConverter $configuration ) {
    return \is_subclass_of($configuration->getClass(), RequestEntityInterface::class);
  }

  /**
   * @inheritDoc
   */
  public function apply( Request $request, ParamConverter $configuration ) {
    /** @var RequestEntityInterface $requestEntityClass only as string! */
    $requestEntityClass = $configuration->getClass();

    $requestEntityOptions = $requestEntityClass::createRequestEntityOptions();

    $data = $this->createDataArray($request, $configuration, $requestEntityOptions);

    $this->validateData($requestEntityClass, $data);

    $requestEntity = $this->createRequestEntity($request, $requestEntityClass, $data);

    $request->attributes->set($configuration->getName(), $requestEntity);

    return true;
  }

  /**
   * @param Request              $request
   * @param ParamConverter       $configuration
   * @param RequestEntityOptions $requestEntityOptions
   *
   * @return array
   */
  private function createDataArray(Request $request, ParamConverter $configuration, RequestEntityOptions $requestEntityOptions): array {
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

    return $data ?: [];
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
  private function createRequestEntity(Request $request, string $requestEntityClass, array $data): RequestEntityInterface {
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
