<?php

namespace controllers;

use model\Example_model;
use core\Database2;

class Example
{
   protected $konfig;
   protected $db;
   public function __construct()
   {
      $this->konfig = Example_model::getInstance();
      $this->db = Database2::getInstance();
   }
}
