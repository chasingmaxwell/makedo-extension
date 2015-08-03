<?php

namespace chasingmaxwell\MockDataExtension;

use chasingmaxwell\MockData\MockData;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\Context\Context;

/**
 * Initializes mock data aware contexts.
 */
class MockDataInitializer implements ContextInitializer {

  private $mockData;

  /**
   * Construct an instance of this class.
   */
  public function __construct(MockData $mockData) {
    $this->mockData = $mockData;
  }

  /**
   * {@inheritdoc}
   */
  public function initializeContext(Context $context) {
    if (!$context instanceof MockDataAwareContext) {
      return;
    }

    $context->setMockData($this->mockData);
  }
}
