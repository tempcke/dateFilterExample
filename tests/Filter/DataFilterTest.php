<?php

namespace Hcc\Tests\Filter;

use Hcc\Filter\DateFilter;

class DataFilterTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var DateFilter
   */
  protected $dateFilter;
  protected $_i = 1;
  
  public static function setUpBeforeClass() {
    $testsDir = realpath(substr(__DIR__.'/',0,strrpos(__DIR__.'/','/tests/')+7));
    require_once $testsDir.'/_bootstrap.php';
  }
  public function setUp() {
    $this->dateFilter = new DateFilter;
  }
  
  public function testEndDateOnly() {
    $this->setEndDate();
    $result = $this->dateFilter->buildQueryCondition($this->_i);
    $this->assertEndDateSetCorrectly($result);
  }

  public function testStartDateOnly() {
    $this->setStartDate();
    $result = $this->dateFilter->buildQueryCondition($this->_i);
    $this->assertStartDateSetCorrectly($result);
  }
  
  public function testStartAndEndDate() {
    $this->setStartDate();
    $this->setEndDate();
    $result = $this->dateFilter->buildQueryCondition($this->_i);
    $this->assertEndDateSetCorrectly($result);
    $this->assertStartDateSetCorrectly($result);
  }

  protected function setEndDate() {
    $this->dateFilter->setAttribute('before_date','april 20th 2016');
  }
  protected function setStartDate() {
    $this->dateFilter->setAttribute('after_date','january 10th 2016');
  }

  protected function assertEndDateSetCorrectly($result) {
    $index   = 'endDate'.$this->_i;
    $lookfor = "filter.date <= :{$index}";
    $this->assertConditionSet($lookfor, $result);
    $this->assertFalse(empty($result['parameters'][$index]));
  }
  protected function assertStartDateSetCorrectly($result) {
    $index   = 'startDate'.$this->_i;
    $lookfor = "filter.date >= :{$index}";
    $this->assertConditionSet($lookfor, $result);
    $this->assertFalse(empty($result['parameters'][$index]));
  }
  protected function assertConditionSet($condition, $result) {
    $conditions = $this->extractConditions($result);
    $this->assertTrue(
        in_array($condition, $conditions),
        "Expected to find '{$condition}' in the conditions"
    );
  }
  protected function assertBindSet($key, $value, $result) {
    $this->assertEqual($value, $result['parameters'][$key]);
  }
  protected function extractConditions($result) {
    $conditions = explode(' AND ',$result['clause']);
    foreach ($conditions as &$condition) {
      $condition = str_replace(['(',')'],'',$condition);
    }
    return $conditions;
  }
}