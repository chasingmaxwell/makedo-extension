<?php

/**
 * @file
 * Contains the class MockData.
 */

class MockData {

  /** @var array An array of mock data records grouped by type. */
  protected static $records = array();

  /**
   * Add a mock record.
   *
   * @param string $type
   *   The record type.
   * @param array $record
   *   An array of property values which make up a mocked data record.
   *
   * @return array
   *   The record which has been added.
   */
  public function addRecord($type, $record) {
    self::$records[$type][] = $record;
    $this->recordsUpdated();
    return $record;
  }

  /**
   * Delete a mock record.
   *
   * @param string $type
   *   The record type.
   * @param array $id
   *   The numeric identifier for the mock record being used as the key in the
   *   records array.
   */
  public function deleteRecord($type, $id) {
    unset(self::$records[$type][$id]);
    $this->recordsUpdated();
  }

  /**
   * Act on the event that records were updated.
   */
  protected function recordsUpdated() {
  }

  /**
   * Get records of a given type optionally filtered by provided parameters.
   *
   * @param string $type
   *   The record type.
   * @param array $params
   *   An optional array of parameters with which to filter the returned
   *   records.
   *
   * @return array
   *   The remaining records after applying the filters from the the $params
   *   array.
   */
  public function getRecords($type, $params = array()) {
    return empty($params) ? self::$records[$type] : array_filter(self::$records[$type], function($record) use ($params) {
      // Unfortunately the version of phpcs we're using can't handle the
      // indentation in this anonymous function.
      // @codingStandardsIgnoreStart
      foreach ($params as $property => $value) {
        if (!$this->filterByProp($record, $property, $value)) {
          return FALSE;
        }
      }
      // @codingStandardsIgnoreEnd
      return TRUE;
    });
  }

  /**
   * Retrieve all records of all types.
   */
  public function getAllRecords() {
    return self::$records;
  }

  /**
   * Determine whether a record should be included in a result set.
   *
   * @param array $record
   *   The record to potentially filter.
   * @param string $property
   *   The property name on which we're filtering.
   * @param mixed $value
   *   The property value by which we're filtering.
   *
   * @return bool
   *   TRUE if the record should be included. FALSE if the record should be
   *   filtered out.
   */
  protected function filterByProp($record, $property, $value) {
    // If we have a method for handling this property, use it.
    $method = 'matches' . ucfirst($property);
    if (method_exists($this, $method)) {
      return $this->$method($record, $value);
    }

    // If no custom handler exists, just check the value against the record.
    return isset($record[$property]) && $record[$property] === $value;
  }
}
