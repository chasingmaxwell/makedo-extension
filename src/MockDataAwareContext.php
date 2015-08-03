<?php

namespace chasingmaxwell\MockDataExtension;

use chasingmaxwell\MockData\MockData;
use Behat\Behat\Context\Context;

/**
 * Mock data aware context.
 */
interface MockDataAwareContext extends Context {
  public function setMockData(MockData $mockData);
}
