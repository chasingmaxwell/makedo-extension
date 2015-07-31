<?php

namespace chasingmaxwell\MockData;

use chasingmaxwell\MockData\MockDataAwareContext;

abstract class MockDataRawContext implements MockDataAwareContext {

  private $mockData;

  public function setMockData(MockData $mockData) {
    $this->mockData = $mockData;
  }
}
