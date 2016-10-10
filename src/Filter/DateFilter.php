<?php

namespace Hcc\Filter;


class DateFilter extends \Hcc\Filter {
  protected $tableAlias = "filter";
  protected $dateField = "date";
  protected $_i=0;
  protected $bind = [];
  protected $clause = [];
  
  public function buildQueryCondition($increment) {
    $this->_i = $increment;
    $this->where("{$this->tableAlias}.deleted_at is null");
    $this->setStartDateCondition();
    $this->setEndDateCondition();
    return [
        'clause'     => $this->getClauseAsString(),
        'parameters' => $this->bind
    ];
  }
  protected function getClauseAsString() {
    return '('.implode(' AND ',$this->clause).')';
  }

  protected function whereNotDeleted() {
  }
  protected function setStartDateCondition() {
    if ($this->getStartDate()) {
      $index = 'startDate'.$this->_i;
      $this->where("{$this->tableDateField()} >= :{$index}");
      $this->bind($index , $this->getStartDate());
    }
  }
  protected function setEndDateCondition() {
    if ($this->getEndDate()) {
      $index = 'endDate'.$this->_i;
      $this->where("{$this->tableDateField()} <= :{$index}"); 
      $this->bind($index , $this->getEndDate());
    }
  }
  protected function getStartDate() {
    return $this->getForatedDateAttr('after_date');
  }
  protected function getEndDate() {
    return $this->getForatedDateAttr('before_date');
  }
  protected function getForatedDateAttr($name, $default = null) {
    $attr = $this->getAttributes();
    return isset($attr[$name]) ? $this->formatDate($attr[$name]) : $default;
  }
  protected function formatDate($dateExpression) {
    return date('Y-m-d H:i:s', strtotime($dateExpression));
  }
  
  protected function where($condition) {
    $this->clause[] = "({$condition})";
  }
  protected function bind($key, $value) {
    $this->bind[$key] = $value;
  }
  
  protected function tableDateField() {
    return "{$this->tableAlias}.{$this->dateField}";
  }
}