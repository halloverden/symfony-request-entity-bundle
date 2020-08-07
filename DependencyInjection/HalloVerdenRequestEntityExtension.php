<?php

namespace HalloVerden\RequestEntityBundle\DependencyInjection;

use HalloVerden\RequestEntityBundle\EventListener\JsonRequestSubscriber;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class HalloVerdenRequestEntityExtension extends Extension {

  /**
   * @inheritDoc
   * @throws \Exception
   */
  public function load(array $configs, ContainerBuilder $container) {
    $config = $this->processConfiguration(new Configuration(), $configs);

    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
    $loader->load('services.yaml');

    if (!$config['decode_json_requests']) {
      $container->removeDefinition(JsonRequestSubscriber::class);
    }
  }

}
