<?php

namespace chasingmaxwell\MockData;

use chasingmaxwell\MockData\MockDataRawContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Provides a context for mocking the STARS API V1.
 */
class MockDataContext extends MockDataRawContext implements SnippetAcceptingContext {

  /** @var array An array of records from the last mock request. */
  protected $records;

  /**
   * Add mock data records.
   *
   * @Given :type records:
   */
  public function assertRecords($type, TableNode $records) {
    foreach ($records->getHash() as $record) {
      $this->mockData->addRecord($type, $record);
    }
  }

  /**
   * Add a mock data record.
   *
   * @Given a/an :type record:
   */
  public function assertRecord($type, TableNode $record) {
    $this->mockData->addRecord($type, $record->getRowsHash());
  }

  /**
   * Retrieve mock data records.
   *
   * @When I request :type records matching the parameters:
   */
  public function retrieveRecords($type, TableNode $parameters) {
    // Retrieve records.
    $this->records = $this->mockData->getRecords($type, $parameters->getRowsHash());
  }

  /**
   * Assert records from mock request.
   *
   * @Then I should have :type records matching the parameters:
   */
  public function assertRetrievedRecords($type, TableNode $parameters) {
    // Retrieve records.
    $this->records = $this->mockData->getRecords($type, $parameters->getRowsHash());

    if (empty($this->records)) {
      throw new Exception('There were no records that matched the given parameters.');
    }
  }

  /**
   * Assert no records from mock request.
   *
   * @Then I should not have :type records matching the parameters:
   */
  public function assertNoRetrievedRecords($type, TableNode $parameters) {
    // Retrieve records.
    $this->records = $this->mockData->getRecords($type, $parameters->getRowsHash());

    if (!empty($this->records)) {
      throw new Exception('There were records that matched the given parameters.');
    }
  }
}
