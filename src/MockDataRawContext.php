<?php

namespace chasingmaxwell\MockDataExtension;

use chasingmaxwell\MockData\MockData;

abstract class MockDataRawContext implements MockDataAwareContext {

  private $mockData;

  public function setMockData(MockData $mockData) {
    $this->mockData = $mockData;
  }

  public function getMockData() {
    return $this->mockData;
  }
}
