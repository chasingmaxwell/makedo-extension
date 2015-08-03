<?php

namespace chasingmaxwell\MockDataExtension;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class MockDataExtension implements ExtensionInterface {

  /**
   * Mock data service ID.
   */
  const MOCK_DATA_ID = 'mock_data';

  /**
   * {@inheritdoc}
   */
  public function getConfigKey() {
    return 'mock_data';
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
        ->scalarNode('mock_data_service')
          ->defaultValue('chasingmaxwell\MockData\MockData')
          ->info('Use "mock_data_service" to set the class which should handle mock data.')
        ->end()
        ->scalarNode('mock_data_file')
          ->info('Use "mock_data_file" to require a file before loading the mock data service.')
        ->end()
      ->end()
    ->end();
  }

  /**
   * {@inheritdoc}
   */
  public function load(ContainerBuilder $container, array $config) {
    $this->loadMockData($container, $config);
    $this->loadContextInitializer($container, $config);
  }

  /**
   * Load the mock data service.
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
  private function loadMockData(ContainerBuilder $container, array $config) {
    $definition = new Definition($config['mock_data_service']);

    if (isset($config['mock_data_file'])) {
      $definition->setFile($config['mock_data_file']);
    }

    $container->setDefinition(self::MOCK_DATA_ID, $definition);
  }

  /**
   * Load the context initializer.
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
  private function loadContextInitializer(ContainerBuilder $container, $config) {
    $definition = new Definition('chasingmaxwell\MockDataExtension\MockDataInitializer', array(new Reference(self::MOCK_DATA_ID)));
    $definition->addTag(ContextExtension::INITIALIZER_TAG, array('priority' => 0));
    $container->setDefinition(self::MOCK_DATA_ID . '.context_initializer', $definition);
  }
}
