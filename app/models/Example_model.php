<?php

namespace model;

use core\Database2;

class Example_model
{
   protected $tabel = ""; # database in website phpmyadmin
   protected $dbh;
   private static $instance = null;

   public function __construct()
   {
      $this->dbh = Database2::getInstance();
   }

   public static function getInstance()
   {
      if (self::$instance == null) {
         self::$instance = new Example_model();
      }
      return self::$instance;
   }
}
