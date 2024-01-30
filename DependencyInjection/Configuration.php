<?php

namespace HalloVerden\RequestEntityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

  /**
   * @inheritDoc
   */
  public function getConfigTreeBuilder(): TreeBuilder {
    $treeBuilder = new TreeBuilder('hallo_verden_request_entity');

    $treeBuilder->getRootNode()
      ->addDefaultsIfNotSet()
      ->children()
        ->booleanNode('decode_json_requests')->defaultTrue()->end()
      ->end();

    return $treeBuilder;
  }

}
