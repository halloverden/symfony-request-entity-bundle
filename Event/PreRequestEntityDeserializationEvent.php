<?php


namespace HalloVerden\RequestEntityBundle\Event;


use JMS\Serializer\DeserializationContext;
use Symfony\Contracts\EventDispatcher\Event;

class PreRequestEntityDeserializationEvent extends Event {

  /**
   * @var DeserializationContext
   */
  private $context;

  /**
   * @var string
   */
  private $class;

  /**
   * @var array
   */
  private $data;

  /**
   * PreDeserializationEvent constructor.
   *
   * @param DeserializationContext $context
   * @param string                 $class
   * @param array                  $data
   */
  public function __construct(DeserializationContext $context, string $class, array $data) {
    $this->context = $context;
    $this->class = $class;
    $this->data = $data;
  }

  /**
   * @return DeserializationContext
   */
  public function getContext(): DeserializationContext {
    return $this->context;
  }

  /**
   * @return string
   */
  public function getClass(): string {
    return $this->class;
  }

  /**
   * @return array
   */
  public function getData(): array {
    return $this->data;
  }

}
