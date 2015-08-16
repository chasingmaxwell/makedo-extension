<?php

namespace chasingmaxwell\MakeDoExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class MakeDoExtension implements ExtensionInterface {

  /**
   * MakeDo service ID.
   */
  const MAKEDO_ID = 'makdedo';

  /**
   * {@inheritdoc}
   */
  public function getConfigKey() {
    return 'makedo';
  }

  /**
   * {@inheritdoc}
   */
  public function initialize(ExtensionManager $extensionManager) {
  }

  /**
   * {@inheritdoc}
   */
  public function process(ContainerBuilder $container) {
  }

  /**
   * {@inheritdoc}
   */
  public function configure(ArrayNodeDefinition $builder) {
    $builder
      ->addDefaultsIfNotSet()
      ->children()
        ->scalarNode('service')
          ->defaultValue('chasingmaxwell\MakeDo\MakeDo')
          ->info('Use "service" to set the class which should handle MakeDo records.')
        ->end()
        ->scalarNode('file')
          ->info('Use "file" to require a file before loading the MakeDo service.')
        ->end()
      ->end()
    ->end();
  }

  /**
   * {@inheritdoc}
   */
  public function load(ContainerBuilder $container, array $config) {
    $this->loadMakeDo($container, $config);
    $this->loadContextInitializer($container, $config);
  }

  /**
   * Load the MakeDo service.
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
  private function loadMakeDo(ContainerBuilder $container, array $config) {
    $definition = new Definition($config['service']);

    if (isset($config['file'])) {
      $definition->setFile($config['file']);
    }

    $container->setDefinition(self::MAKEDO_ID, $definition);
  }

  /**
   * Load the context initializer.
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
  private function loadContextInitializer(ContainerBuilder $container, $config) {
    $definition = new Definition('chasingmaxwell\MakeDoExtension\MakeDoInitializer', array(new Reference(self::MAKEDO_ID)));
    $definition->addTag(ContextExtension::INITIALIZER_TAG, array('priority' => 0));
    $container->setDefinition(self::MAKEDO_ID . '.context_initializer', $definition);
  }
}
