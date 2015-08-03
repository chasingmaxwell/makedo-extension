<?php

namespace chasingmaxwell\MockData;

/**
 * @file
 * Contains the class MockData.
 */

class MockData {

  /** @var array An array of mock data records grouped by type. */
  private static $records = array();

  /** @var array The functions or methods to invoke upon a data change. */
  private $subscribers = array();

  /**
   * Subscribe to data changes.
   *
   * @param array $types
   *   The types of data to subscribe to.
   * @param callable $method
   *   The callable function or method which should be invoked when data is
   *   changed.
   */
  public function subscribe(array $types, callable $method) {
    $this->subscribers[] = array(
      'types' => $types,
      'method' => $method,
    );
  }

  /**
   * Publish data changes to subscribers.
   *
   * @param array $types
   *   Restrict the data types we publish to the given types if provided.
   *   Otherwise publish all types.
   */
  private function publish(array $types) {
    $subscribers = $this->subscribers;
    $records = $this->getAllRecords();

    // Filter subscribers by the given types.
    if ($types) {
      $subscribers = array_filter($this->subscribers, function($subscriber) use ($types) {
        return (bool) array_intersect($subscriber['types'], $types);
      });
    }

    foreach ($subscribers as $subscriber) {
      call_user_func_array($subscriber['method'], array($types, $records));
    }
  }

  /**
   * Reset data.
   *
   * This is useful to set initial mock data records.
   *
   * @param array $data
   *   An array of mock data records grouped by type.
   * @param string $type
   *   The data type to reset.
   */
  public function resetData($type, $data) {
    self::$records[$type] = $data;
    $this->recordsUpdated(array($type));
  }

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
    $this->recordsUpdated(array($type));
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
    $this->recordsUpdated(array($type));
  }

  /**
   * Act on the event that records were updated.
   *
   * @param array $types
   *   The types of records that were updated.
   */
  protected function recordsUpdated(array $types) {
    $this->publish($types);
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
