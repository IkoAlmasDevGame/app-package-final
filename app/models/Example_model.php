<?php

namespace model;

use core\Database;

class Example_model
{
   protected $tabel = ""; # database in website phpmyadmin
   protected $dbh;
   private static $instance = null;

   public function __construct()
   {
      $this->dbh = new Database;
   }

   public static function getInstance()
   {
      if (self::$instance == null) {
         self::$instance = new Example_model();
      }
      return self::$instance;
   }
}
