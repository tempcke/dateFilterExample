<?php

namespace Hcc;

abstract class Filter {
  protected $attributes = [];
  
  public function __construct() {
  }
  
  public function getAttributes() {
    return $this->attributes;
  }
  public function setAttribute($attr, $val) {
    $this->attributes[$attr] = $val;
  }
}