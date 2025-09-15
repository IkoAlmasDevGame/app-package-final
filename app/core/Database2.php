<?php

namespace core;

use Exception;
use PDO;
use PDOException;

class Database2 extends PDO
{
   private $DB_HOST = DB_HOST;
   private $DB_DATABASE = DB_DATABASE;
   private $DB_USERNAME = DB_USERNAME;
   private $DB_PASSWORD = DB_PASSWORD;
   private $dbh2;
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

   public function CreateCode($tabel, $field)
   {
      $sql = "SELECT * FROM $tabel LIMIT 1";
      $struktur = $this->dbh2->prepare($sql);
      $struktur->execute();
      $mysql = $this->dbh2->prepare("SELECT MAX($field) as $field FROM $tabel order by $field desc");
      $mysql->execute();
      $row = $mysql->fetch();

      $urut = substr($row[$field], 4, 3);
      $tambah = (int)$urut + 1;
      $tahun = date('y');

      if (strlen($tambah) == 1) {
         return $tahun . '00' . $tambah;
      } elseif (strlen($tambah) == 2) {
         return $tahun . '0' . $tambah;
      } else {
         return $tahun . $tambah;
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

   public function DeleteExecute($tabel, $where, $name)
   {
      $result = $this->dbh2->prepare("DELETE FROM $tabel WHERE $where = ?");
      $array = array($name);
      $result->execute($array);
      return $result;
   }

   public function CreateCodeInvoice($tabel, $field, $inisial)
   {
      $sql = "SELECT * FROM $tabel LIMIT 1";
      $struktur = $this->dbh2->prepare($sql);
      $struktur->execute();
      $mysql = $this->dbh2->prepare("SELECT MAX($field) as $field FROM $tabel order by $field desc");
      $mysql->execute();
      $row = $mysql->fetch();

      $urut = substr($row[$field], 9, 3);
      $tambah = (int)$urut + 1;
      $tahun = date('Y');

      if (strlen($tambah) == 1) {
         return $inisial . '-' . $tahun . '/00' . $tambah;
      } elseif (strlen($tambah) == 2) {
         return $inisial . '-' . $tahun . '/00' . $tambah;
      } else {
         return $inisial . '-' . $tahun . '/' . $tambah;
      }
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
	   header("Location:".URL_BASE."");
   }

   public function Create($table, array $field)
   {
      $sql = "INSERT INTO $table SET ";
      foreach ($field as $key => $value) {
         $sql .= "$key = '$value',";
      }
      $sql = rtrim($sql, ',');
      $jalan = $this->qPrepare($sql);
      $jalan->execute(array($field));
   }

   public function Update($table, array $field, $where)
   {
      $sql = "UPDATE $table SET ";
      foreach ($field as $key => $value) {
         $sql .= "$key = '$value',";
      }
      $sql = rtrim($sql, ',');
      $sql .= " WHERE $where";
      $jalan = $this->qPrepare($sql);
      $jalan->execute(array($field, $where));
   }
      
   public function Create($table, array $field)
   {
      $columns = array_keys($field);
      $columnsList = implode(', ', $columns);
      $placeholders = rtrim(str_repeat('?, ', count($columns)), ', ');
      $sql = "INSERT INTO $table ($columnsList) VALUES ($placeholders)";
      $stmt = $this->qPrepare($sql);
      $stmt->execute(array_values($field));
   }

   public function Update($table, array $field, $where, array $whereParams = [])
   {
      $columns = array_keys($field);
      $setParts = array_map(fn($col) => "$col = ?", $columns);
      $setString = implode(', ', $setParts);

      $sql = "UPDATE $table SET $setString WHERE $where";

      $stmt = $this->qPrepare($sql);

      // Gabungkan nilai field dan whereParams untuk positional binding
      $params = array_merge(array_values($field), $whereParams);

      $stmt->execute($params);
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

   public function inc($num = 1)
   {
      if (!is_numeric($num)) {
         throw new Exception('Argument supplied to inc must be a number');
      }
      return array("[I]" => "+" . $num);
   }
}