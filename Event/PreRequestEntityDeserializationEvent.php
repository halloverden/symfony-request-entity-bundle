<?php

namespace HalloVerden\RequestEntityBundle\Event;

use JMS\Serializer\DeserializationContext;
use Symfony\Contracts\EventDispatcher\Event;

class PreRequestEntityDeserializationEvent extends Event {
  /**
   * @param DeserializationContext $context
   * @param string $class
   * @param array $data
   */
  public function __construct(
    private DeserializationContext $context,
    private string                 $class,
    private array                  $data
  ) {
  }

  /**
   * @return DeserializationContext
   */
  public function getContext(): DeserializationContext {
    return $this->context;
  }

  /**
   * @param DeserializationContext $context
   *
   * @return self
   */
  public function setContext(DeserializationContext $context): self {
    $this->context = $context;
    return $this;
  }

  /**
   * @return string
   */
  public function getClass(): string {
    return $this->class;
  }

  /**
   * @param string $class
   *
   * @return self
   */
  public function setClass(string $class): self {
    $this->class = $class;
    return $this;
  }

  /**
   * @return array
   */
  public function getData(): array {
    return $this->data;
  }

  /**
   * @param array $data
   *
   * @return self
   */
  public function setData(array $data): self {
    $this->data = $data;
    return $this;
  }

}
