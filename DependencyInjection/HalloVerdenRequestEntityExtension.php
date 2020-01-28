<?php

namespace HalloVerden\RequestEntityBundle\DependencyInjection;

use HalloVerden\RequestEntityBundle\EventListener\DeserializableRequestEntityListener;
use HalloVerden\RequestEntityBundle\ParamConverter\RequestEntityConverter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use JMS\Serializer\SerializerInterface;

class HalloVerdenRequestEntityExtension extends Extension {

  /**
   * @inheritDoc
   * @throws \Exception
   */
  public function load(array $configs, ContainerBuilder $container) {
    $arguments = [];

    if (interface_exists(ValidatorInterface::class)) {
      $arguments['$validator'] = new Reference(ValidatorInterface::class);
    }

    $requestEntityConverter = new Definition(RequestEntityConverter::class, $arguments);

    $requestEntityConverter->addTag('request.param_converter', [
      'converter' => RequestEntityConverter::class
    ]);

    $container->setDefinition(RequestEntityConverter::class, $requestEntityConverter);

    if (interface_exists(SerializerInterface::class)) {
      $deserializableRequestEntityListener = new Definition(DeserializableRequestEntityListener::class, [
        '$serializer' => new Reference(SerializerInterface::class)
      ]);

      $deserializableRequestEntityListener->addTag('kernel.event_subscriber');

      $container->setDefinition(DeserializableRequestEntityListener::class, $deserializableRequestEntityListener);
    }
  }

}
