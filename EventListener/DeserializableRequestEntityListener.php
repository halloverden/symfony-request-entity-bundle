<?php


namespace HalloVerden\RequestEntityBundle\EventListener;

use HalloVerden\RequestEntityBundle\Interfaces\DeserializableRequestEntityInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DeserializableRequestEntityListener implements EventSubscriberInterface {
  /**
   * @var SerializerInterface
   */
  private $serializer;

  /**
   * @var array
   */
  private $serializerGroups = ['RequestEntityDeserialization'];

  /**
   * DeserializableRequestEntitySubscriber constructor.
   *
   * @param SerializerInterface $serializer
   * @param array|null          $serializerGroups
   */
  public function __construct(SerializerInterface $serializer, array $serializerGroups = null ) {
    $this->serializer = $serializer;

    if ($serializerGroups) {
      $this->serializerGroups = $serializerGroups;
    }
  }

  /**
   * Makes sure all exceptions are returned as JSON
   *
   * @param ControllerArgumentsEvent $event
   */
  public function onControllerArguments( ControllerArgumentsEvent $event ) {
    $request = $event->getRequest();
    foreach ($request->attributes as $name => $attribute) {
      if ($attribute instanceof DeserializableRequestEntityInterface) {
        $this->deserializeArgument($attribute);
      }
    }
  }

  /**
   * @param DeserializableRequestEntityInterface $requestEntity
   */
  private function deserializeArgument(DeserializableRequestEntityInterface $requestEntity) {
    $deserializableProperties = $requestEntity->getDeserializableProperties();

    foreach ($deserializableProperties as $name => $class) {
      $requestEntity->setDeserializedValue($name, $this->deserializeRequestEntityData($requestEntity->getDeserializablePropertyValue($name), $class));
    }
  }

  /**
   * @param $data
   * @param string $class
   * @return mixed
   */
  private function deserializeRequestEntityData($data, string $class) {
    if (null === $data) {
      return null;
    }
    $context = DeserializationContext::create()->setGroups($this->serializerGroups);
    return $this->serializer->deserialize(json_encode($data), $class, 'json', $context);
  }

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    return array(
      KernelEvents::CONTROLLER_ARGUMENTS => ['onControllerArguments', 2048]
    );
  }
}
