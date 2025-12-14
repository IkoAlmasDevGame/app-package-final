<?php

namespace controllers;

use models\Example_model;
use core\Database;

class Example
{
   protected $konfig = null;
   protected $db = null;
   protected $table_name = ""; # database in website phpmyadmin
   public function __construct()
   {
      $this->konfig = Example_model::getInstance();
      $this->db = Database::getInstance();
   }
}
