<?php

namespace chasingmaxwell\MockDataExtension;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MockDataExtension implements ExtensionInterface {

  /**
   * Mock data service ID.
   */
  const MOCK_DATA_ID = 'mock_data';

  /**
   * {@inheritdoc}
   */
  public function getConfigKey() {
    return 'mock-data';
  }

  /**
   * {@inheritdoc}
   */
  public function initialize(ExtensionManager $extensionManager) {
  }

  /**
   * {@inheritdoc}
   */
  public function configure(ArrayNodeDefinition $builder) {
    $builder->
      children()->
        scalarNode('mock_data_service')->
          defaultValue('MockData')->
          info('Use "mock_data_service" to set the class which should handle mock data.')->
        end()->
        scalarNode('mock_data_file')->
          info('Use "mock_data_file" to require a file before loading the mock data service.')->
        end()->
      end()->
    end();
  }

  /**
   * {@inheritdoc}
   */
  public function load(ContainerBuilder $container, array $config) {
    $this->loadMockData($container, $config);
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
      $defintion->setFile($config['mock_data_file']);
    }

    $container->setDefinition(self::MOCK_DATA_ID, $definition);
  }
}
