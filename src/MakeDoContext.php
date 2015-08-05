<?php

namespace chasingmaxwell\MakeDoExtension;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Provides a behat context for MakeDo.
 */
class MakeDoContext extends MakeDoRawContext implements SnippetAcceptingContext {

  /** @var array An array of records from the last MakeDo request. */
  protected $records;

  /**
   * Add MakeDo records.
   *
   * @Given :type records:
   */
  public function assertRecords($type, TableNode $records) {
    foreach ($records->getHash() as $record) {
      $this->getMakeDo()->addRecord($type, $record);
    }
  }

  /**
   * Add a MakeDo record.
   *
   * @Given a/an :type record:
   */
  public function assertRecord($type, TableNode $record) {
    $this->getMakeDo()->addRecord($type, $record->getRowsHash());
  }

  /**
   * Retrieve MakeDo records.
   *
   * @When I request :type records matching the parameters:
   */
  public function retrieveRecords($type, TableNode $parameters) {
    // Retrieve records.
    $this->records = $this->getMakeDo()->getRecords($type, $parameters->getRowsHash());
  }

  /**
   * Assert records from MakeDo request.
   *
   * @Then I should have :type records matching the parameters:
   */
  public function assertRetrievedRecords($type, TableNode $parameters) {
    // Retrieve records.
    $this->records = $this->getMakeDo()->getRecords($type, $parameters->getRowsHash());

    if (empty($this->records)) {
      throw new \Exception('There were no records that matched the given parameters.');
    }
  }

  /**
   * Assert no records from MakeDo request.
   *
   * @Then I should not have :type records matching the parameters:
   */
  public function assertNoRetrievedRecords($type, TableNode $parameters) {
    // Retrieve records.
    $this->records = $this->getMakeDo()->getRecords($type, $parameters->getRowsHash());

    if (!empty($this->records)) {
      throw new \Exception('There were records that matched the given parameters.');
    }
  }
}
