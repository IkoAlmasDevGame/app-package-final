<?php

namespace core;

use Exception;
use PDO;
use PDOException;

class Database2 extends PDO
{
   private $dbh2;
   protected $DB_HOST = DB_HOST;
   protected $DB_DATABASE = DB_DATABASE;
   protected $DB_USERNAME = DB_USERNAME;
   protected $DB_PASSWORD = DB_PASSWORD;
   private static $instance = null;

   public function __construct()
   {
      try {
         $this->dbh2 = new PDO("mysql:host=$this->DB_HOST;dbname=$this->DB_DATABASE", $this->DB_USERNAME, $this->DB_PASSWORD);
         $this->dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
         echo "Gagal terhubung : " . $e->getMessage();
      }
   }

   public static function getInstance()
   {
      if (self::$instance == null) {
         self::$instance = new Database2();
      }
      return self::$instance;
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

   public function CreateCodeAuto($tabel, $field)
   {
      $sql = "SELECT * FROM $tabel LIMIT 1";
      $struktur = $this->dbh2->prepare($sql);
      $struktur->execute();
      $mysql = $this->dbh2->prepare("SELECT MAX($field) as $field FROM $tabel order by $field desc");
      $mysql->execute();
      $row = $mysql->fetch();

      $urut = substr($row[$field], 0, 7);
      $tambah = (int)$urut + 1;

      if (strlen($tambah) == 1) {
         return $tambah;
      } elseif (strlen($tambah) == 2) {
         return $tambah;
      } else {
         return $tambah;
      }
   }

   public function CreateCode($tabel, $field, $inisial)
   {
      $sql = "SELECT * FROM $tabel LIMIT 1";
      $struktur = $this->dbh2->prepare($sql);
      $struktur->execute();
      $mysql = $this->dbh2->prepare("SELECT MAX($field) as $field FROM $tabel order by $field desc");
      $mysql->execute();
      $row = $mysql->fetch();

      $urut = substr($row[$field], 4, 3);
      $tambah = (int)$urut + 1;
      $tahun = date('Y');

      if (strlen($tambah) == 1) {
         return $inisial.$tahun . '00' . $tambah;
      } elseif (strlen($tambah) == 2) {
         return $inisial.$tahun . '0' . $tambah;
      } else {
         return $inisial.$tahun . $tambah;
      }
   }

   public function PrepareExecute($tabel, $where, $name)
   {
      $result = $this->dbh2->prepare("SELECT * FROM $tabel WHERE $where = ?");
      $array = array($name);
      $result->execute($array);
      $row = $result->fetch();
      return $row;
   }
   
   public function PrepareExecute2($tabel, $where, $name, $where2, $name2)
   {
      $result = $this->dbh2->prepare("SELECT * FROM $tabel WHERE $where = ? and $where2 = ?");
      $array = array($name, $name2);
      $result->execute($array);
      $row = $result->fetch();
      return $row;
   }

   public function DeleteExecute($tabel, $where, $name)
   {
      $result = $this->dbh2->prepare("DELETE FROM $tabel WHERE $where = ?");
      $array = array($name);
      $result->execute($array);
      return $result === 1 ? true : false;
   }

   public function CreateCodeInvoice($tabel, $field, $inisial)
   {
      // Assuming $this->dbh is a PDO instance
      $stmt = $this->dbh2->prepare("SELECT MAX($field) as max_val FROM $tabel");
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
      $tahun = date('Y');
      $prefix = $inisial . $tahun;
      
      if ($row['max_val']) {
          $num_part = substr($row['max_val'], strlen($prefix));
          $urut = (int)$num_part;
      } else {
          $urut = 0;
      }

      $tambah = $urut + 1;

      // Always pad the number to 3 digits (or more if necessary)
      $code = $prefix . str_pad($tambah, 3, '0', STR_PAD_LEFT);

      return $code;
   }


   public function CreateCodeInvoice2($tabel, $field, $inisial)
   {
    // Assuming $this->dbh is a PDO instance
    $stmt = $this->dbh2->prepare("SELECT MAX($field) as max_val FROM $tabel");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $tahun = date('Ymd');
    $prefix = $inisial . $tahun;

    if ($row['max_val']) {
        $num_part = substr($row['max_val'], strlen($prefix));
        $urut = (int)$num_part;
    } else {
        $urut = 0;
    }

    $tambah = $urut + 1;

    // Always pad the number to 3 digits (or more if necessary)
    $code = $prefix . str_pad($tambah, 3, '0', STR_PAD_LEFT);

    return $code;
   }

   public function LastGetError()
   {
      return $this->dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   }

   public function LastGetErrCode()
   {
      return $this->dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
   }

   function redirect($alamat)
   {
      header("Location:" . URL_BASE . $alamat);
	  exit(0);
   }
   function redirect2($alamat, $alert)
   {
      header("Location:" . URL_BASE . $alamat . "&msg=".urlencode($alert));
      exit(0);
   }

   public function Create($table, array $field): void
   {
      $columns = array_keys($field);
      $columnsList = implode(', ', $columns);
      $placeholders = rtrim(str_repeat('?, ', count($columns)), ', ');
      $sql = "INSERT INTO $table ($columnsList) VALUES ($placeholders)";
      $stmt = $this->qPrepare2($sql);
      $stmt->execute(array_values($field)) === 1 ? true : false;
   }

   public function Update($table, array $field, $where, array $whereParams = []): void
   {
      $columns = array_keys($field);
      $setParts = array_map(fn($col) => "$col = ?", $columns);
      $setString = implode(', ', $setParts);

      $sql = "UPDATE $table SET $setString WHERE $where";

      $stmt = $this->qPrepare2($sql);

      // Gabungkan nilai field dan whereParams untuk positional binding
      $params = array_merge(array_values($field), $whereParams);
      
      $stmt->execute($params) === 1 ? true : false;
   }

   public function cekdata($table)
   {
      $prepare = $this->dbh2->prepare("SELECT * FROM $table");
      $prepare->execute();
      $prepare->rowCount();
      return $prepare;
   }

   public function jPrepare($tabel, $field, $join)
   {
      $prepare = $this->dbh2->prepare("SELECT * FROM $tabel as $field $join");
      $prepare->execute();
      return $prepare;
   }

   public function qPrepare($sql)
   {
      $prepare = $this->dbh2->prepare("SELECT * FROM $sql");
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