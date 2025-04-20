<?php

namespace core;

use Exception;
use PDO;

class Database2
{
   protected $DB_HOST = DB_HOST;
   protected $DB_PORT = DB_PORT;
   protected $DB_DATABASE = DB_NAME;
   protected $DB_USERNAME = DB_USERNAME;
   protected $DB_PASSWORD = DB_PASSWORD;
   protected $dbh2;

   public function __construct()
   {
      try {
         $this->dbh2 = new PDO("mysql:host=$this->DB_HOST;dbname=$this->DB_DATABASE", $this->DB_USERNAME, $this->DB_PASSWORD);
      } catch (Exception $e) {
         echo "Gagal terhubung : " . $e->getMessage();
         die;
      }
   }

   public function qPrepare($sql)
   {
      $prepare = $this->dbh2->prepare($sql);
      return $prepare;
   }
}
