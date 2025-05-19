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

   public function json_response($pesan = null, $typeError = null, $code = '')
   {
      header_remove();
      http_response_code($code);
      header("Cache-Controll: no-transform, public, max-age30, s-maxage=900");
      header("Content-type: application/json");
      $status = array(
         200 => '200 OK',
         400 => '400 Bad Request',
         422 => '422 Unprocessable entity',
         500 => '500 Internal server error'
      );
      header("Status:" . $status[$code]);
      return json_encode(array(
         'status' => $code < 300,
         'message' => $pesan,
         'type' => $typeError,
      ));
   }

   public function LastGetError()
   {
      return $this->dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   }

   public function LastGetErrCode()
   {
      return $this->dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
   }

   public function qPrepare($sql)
   {
      $prepare = $this->dbh2->prepare($sql);
      $prepare->execute();
      return $prepare;
   }

   public function qPrepare2($sql)
   {
      $prepare = $this->dbh2->prepare($sql);
      return $prepare;
   }

   public function inc($num = 1)
   {
      if (!is_numeric($num)) {
         throw new Exception('Argument supplied to inc must be a number');
      }
      return array("[I]" => "+" . $num);
   }
}
