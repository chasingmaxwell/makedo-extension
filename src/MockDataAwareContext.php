<?php

namespace chasingmaxwell\MockData;

use Behat\Behat\Context\Context;
use chasingmaxwell\MockData\MockData;

/**
 * Mock data aware context.
 */
interface MockDataAwareContext extends Context {
  public function setMockData(MockData $mockData);
}
