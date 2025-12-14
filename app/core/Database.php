<?php

namespace core;

class Database
{
   private $DB_HOST = DB_HOST;
   private $DB_DATABASE = DB_DATABASE;
   private $DB_USERNAME = DB_USERNAME;
   private $DB_PASSWORD = DB_PASSWORD;
   private $dbh;
   private static $instance = null;

   public function __construct()
   {
      $this->dbh = mysqli_connect(hostname: $this->DB_HOST, username: $this->DB_USERNAME, password: $this->DB_PASSWORD, database: $this->DB_DATABASE, port: "3306");
      if ($this->dbh->errno) {
         echo "database gagal terhubung";
         die;
      }
   }

   # Khusus MVC Database khusus Models

   public static function getInstance()
   {
      if (self::$instance == null) {
         self::$instance = new Database();
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

   public function backupFile()
   {
      $backup_folder = "../../../app/database/";
      // Membuat folder backup jika belum ada
      if (!file_exists($backup_folder)) {
         mkdir($backup_folder, 0777, true);
      }

      // Nama file backup
      $backup_file = $backup_folder . $this->DB_DATABASE . '.sql';
      // Membuka file backup
      $handle = fopen($backup_file, 'w+');
      // Header file backup
      fwrite($handle, "-- Backup Database\n");
      fwrite($handle, "-- Waktu Backup: " . date('Y-m-d H:i:s') . "\n");
      fwrite($handle, "-- Database: " . $this->DB_DATABASE . "\n\n");

      // Mendapatkan daftar tabel
      $tables = array();
      $result = $this->query("SHOW TABLES");
      while ($row = $result->fetch_row()) {
         $tables[] = $row[0];
      }

      // Backup setiap tabel
      foreach ($tables as $table) {
         // Backup struktur tabel
         fwrite($handle, "-- ----------------------------\n");
         fwrite($handle, "-- Struktur tabel `$table`\n");
         fwrite($handle, "-- ----------------------------\n");
         fwrite($handle, "DROP TABLE IF EXISTS `$table`;\n");

         $create_table = $this->query("SHOW CREATE TABLE `$table`");
         $create_table_row = $create_table->fetch_row();
         fwrite($handle, $create_table_row[1] . ";\n\n");

         // Backup data tabel
         fwrite($handle, "-- ----------------------------\n");
         fwrite($handle, "-- Data tabel `$table`\n");
         fwrite($handle, "-- ----------------------------\n");

         $data = $this->query("SELECT * FROM `$table`");
         while ($row = $data->fetch_assoc()) {
            $keys = array_map('addslashes', array_keys($row));
            $values = array_map('addslashes', array_values($row));

            fwrite($handle, "INSERT INTO `$table` (`" . implode('`,`', $keys) . "`) VALUES ('" . implode("','", $values) . "');\n");
         }
         fwrite($handle, "\n");
      }
      // Menutup file
      fclose($handle);
      // Menutup koneksi
      $this->close_query();
      echo "<script>location.href = '../ui/header.php?page=beranda';</script>";
   }


   public function LastGetError()
   {
      return $this->dbh->errno;
   }

   public function real_escape_string($value)
   {
      return mysqli_real_escape_string($this->dbh, $value);
   }

   public function close_query()
   {
      if ($this->dbh) {
         mysqli_close($this->dbh);
         return self::$instance = null;
      }
   }

   public function queryJoin($tabel, $inisial, $join)
   {
      return $this->dbh->query("SELECT * FROM $tabel as $inisial " . $join . "");
   }

   public function query($sql)
   {
      $pesan = $this->dbh->query($sql, MYSQLI_STORE_RESULT);
      return $pesan;
   }

   public function CREATE($tabel, $data)
{
    if (empty($data)) {
        return false; // Tidak ada data
    }
    
    $fields = array_keys($data);
    $placeholders = str_repeat('?,', count($fields) - 1) . '?';
    $field_list = implode(',', array_map(function($field) { return "`$field`"; }, $fields));
    
    $query = "INSERT INTO `$tabel` ($field_list) VALUES ($placeholders)";
    
    // Gunakan mysqli_prepare
    $stmt = mysqli_prepare($this->dbh, $query);
    if (!$stmt) {
        return false;
    }
    
    // Tentukan tipe data (s = string, i = int, d = double)
    $types = '';
    $values = [];
    foreach ($data as $value) {
        if (is_int($value)) {
            $types .= 'i';
        } elseif (is_float($value)) {
            $types .= 'd';
        } else {
            $types .= 's';
        }
        $values[] = $value;
    }
    
    mysqli_stmt_bind_param($stmt, $types, ...$values);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $result ? true : false;
}


   public function SELECT($tabel)
   {
      $sql = "SELECT * FROM $tabel";
      $result = $this->dbh->prepare($sql);
      return $result;
   }

   public function UPDATE($tabel, $data, $where)
   {
    if (empty($data) || empty($where)) {
        return false; // Tidak ada data atau kondisi
    }
    
    // Bangun SET clause
    $set_parts = [];
    $set_values = [];
    $set_types = '';
    foreach ($data as $field => $value) {
        $set_parts[] = "`$field` = ?";
        if (is_int($value)) {
            $set_types .= 'i';
        } elseif (is_float($value)) {
            $set_types .= 'd';
        } else {
            $set_types .= 's';
        }
        $set_values[] = $value;
    }
    $set_clause = implode(", ", $set_parts);
    
    // Bangun WHERE clause
    $where_parts = [];
    $where_values = [];
    $where_types = '';
    foreach ($where as $field => $value) {
        $where_parts[] = "`$field` = ?";
        if (is_int($value)) {
            $where_types .= 'i';
        } elseif (is_float($value)) {
            $where_types .= 'd';
        } else {
            $where_types .= 's';
        }
        $where_values[] = $value;
    }
    $where_clause = implode(" AND ", $where_parts);
    
    $query = "UPDATE `$tabel` SET $set_clause WHERE $where_clause";
    
    // Gunakan mysqli_prepare
    $stmt = mysqli_prepare($this->dbh, $query);
    if (!$stmt) {
        return false;
    }
    
    // Gabungkan tipe dan nilai
    $all_types = $set_types . $where_types;
    $all_values = array_merge($set_values, $where_values);
    
    mysqli_stmt_bind_param($stmt, $all_types, ...$all_values);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $result ? true : false;
   }


   public function SELECT_WHERE($tabel, $where, $name)
   {
      $sql = "SELECT * FROM $tabel WHERE $where = '$name'";
      $result = $this->dbh->query($sql);
      return $result;
      # under English
      # SELECT WHERE on the core database is to search for data desired by the website programmer and must match the database table row example like id_admin or others.

      # under Indonesian
      # SELECT WHERE pada core Database ini untuk mencari data yang diinginkan oleh programmer website dan harus sesuai row tabel database contoh seperti id_admin atau yang lainnya.
   }

   public function SELECT_WHERE2($name_value, $tabel, $where, $name)
   {
      $sql = "SELECT $name_value FROM $tabel WHERE $where = '$name'";
      $result = $this->dbh->query($sql, MYSQLI_STORE_RESULT);
      $row = $result->fetch_array();
      return $row[0];
      # under English
      # SELECT WHERE on the core database is to search for data desired by the website programmer and must match the database table row example like id_admin or others.

      # under Indonesian
      # SELECT WHERE pada core Database ini untuk mencari data yang diinginkan oleh programmer website dan harus sesuai row tabel database contoh seperti id_admin atau yang lainnya.
   }

   public function delete($table, $where, $id)
   {
      return $this->dbh->query("DELETE FROM $table WHERE $where = '$id'");
      # dibawah ini bahasa indonesia :
      # database harus sama ketika ingin dihapus pada table yang sesuai.

      # under English
      # The database must be the same when you want to delete the corresponding table.
   }

   public function SELECTMAX($tabel, $field, $inisial)
   {
      $sql = "SELECT MAX($field) as $inisial FROM $tabel";
      $result = $this->dbh->query($sql, MYSQLI_STORE_RESULT);
      $data = mysqli_fetch_array($result);
      return $data;
      # under English :
      # special field in table row in database example like id_admin or others.
      # initials for initials in the field in the row area in the database table.
      # tabel or table pada database you

      # under Indonesian :
      # field khusus di baris tabel dalam contoh database seperti id_admin atau yang lainnya.
      # inisial untuk inisial di field di area baris dalam tabel database.
      # tabel atau table pada database anda
   }

   public function SELECTMIN($tabel, $field, $inisial)
   {
      $sql = "SELECT MIN($field) as $inisial FROM $tabel";
      $result = $this->dbh->query($sql, MYSQLI_STORE_RESULT);
      $data = mysqli_fetch_array($result);
      return $data;
      # under English :
      # special field in table row in database example like id_admin or others.
      # initials for initials in the field in the row area in the database table.
      # tabel or table pada database you

      # under Indonesian :
      # field khusus di baris tabel dalam contoh database seperti id_admin atau yang lainnya.
      # inisial untuk inisial di field di area baris dalam tabel database.
      # tabel atau table pada database anda
   }

   public function SELECTCOUNT($tabel, $field, $inisial)
   {
      $sql = "SELECT COUNT($field) as $inisial FROM $tabel";
      $result = $this->dbh->query($sql, MYSQLI_STORE_RESULT);
      $data = mysqli_fetch_array($result);
      return $data;
      # under English :
      # special field in table row in database example like id_admin or others.
      # initials for initials in the field in the row area in the database table.
      # tabel or table pada database you

      # under Indonesian :
      # field khusus di baris tabel dalam contoh database seperti id_admin atau yang lainnya.
      # inisial untuk inisial di field di area baris dalam tabel database.
      # tabel atau table pada database anda
   }

   public function SELECTMAXLIMIT($tabel, $field, $inisial)
   {
      $sql = "SELECT MAX($field) as $inisial FROM $tabel LIMIT 1";
      $result = $this->dbh->query($sql, MYSQLI_STORE_RESULT);
      $data = mysqli_fetch_array($result);
      return $data;
      # under English :
      # special field in table row in database example like id_admin or others.
      # initials for initials in the field in the row area in the database table.
      # tabel or table pada database you

      # under Indonesian :
      # field khusus di baris tabel dalam contoh database seperti id_admin atau yang lainnya.
      # inisial untuk inisial di field di area baris dalam tabel database.
      # tabel atau table pada database anda
   }

   public function SELECTSUM($tabel, $field, $inisial)
   {
      $sql = "SELECT SUM($field) as $inisial FROM $tabel";
      $result = $this->dbh->query($sql, MYSQLI_STORE_RESULT);
      $data = mysqli_fetch_array($result);
      return $data;
      # under English :
      # special field in table row in database example like id_admin or others.
      # initials for initials in the field in the row area in the database table.
      # tabel or table pada database you

      # under Indonesian :
      # field khusus di baris tabel dalam contoh database seperti id_admin atau yang lainnya.
      # inisial untuk inisial di field di area baris dalam tabel database.
      # tabel atau table pada database anda
   }

   public function SELECTAVERAGE($tabel, $field, $inisial)
   {
      $sql = "SELECT AVG($field) as $inisial FROM $tabel";
      $result = $this->dbh->query($sql, MYSQLI_STORE_RESULT);
      $data = mysqli_fetch_array($result);
      return $data;
      # under English :
      # special field in table row in database example like id_admin or others.
      # initials for initials in the field in the row area in the database table.
      # tabel or table pada database you

      # under Indonesian :
      # field khusus di baris tabel dalam contoh database seperti id_admin atau yang lainnya.
      # inisial untuk inisial di field di area baris dalam tabel database.
      # tabel atau table pada database anda
   }

   # Khusus MVC Database khusus Models

   public function CreateCode($tabel, $field, $inisial)
   {
    // Assuming $this->dbh is a PDO instance
    $stmt = $this->dbh->query("SELECT MAX($field) as max_val FROM $tabel");
    $row = $stmt->fetch_assoc();

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


   public function CreateCodeInisial($tabel, $field, $inisial)
   {
      // Query to get table structure (to dynamically determine the field name)
      $struktur = $this->dbh->query("SELECT * FROM $tabel LIMIT 1");
      if (!$struktur) {
          // Handle query error (e.g., table doesn't exist)
          return false; // Or throw an exception
      }
    
      // Get all fields and use the second one (index 1). If only one field, use index 0.
      $fields = mysqli_fetch_fields($struktur);
      $fieldIndex = isset($fields[1]) ? 1 : 0; // Default to first if no second field
      $field = $fields[$fieldIndex]->name; // Overwrites the passed $field parameter
    
      // Query to get the MAX value of the field
      $mysql = $this->dbh->query("SELECT MAX($field) as $field FROM $tabel ORDER BY $field DESC");
      if (!$mysql) {
          // Handle query error
          return false;
      }

      $row = mysqli_fetch_array($mysql);

      // Handle case where table is empty (MAX returns null)
      if ($row[$field] === null) {
          $urut = 0;
      } else {
          // Extract the last 3 characters (assuming the code format is prefix + MM + YY + 3-digit number)
          $urut = (int)substr($row[$field], 9, 3);
      }
    
      $tahun = date('y'); // 2-digit year
      $bulan = date('m'); // Month with leading zero
    
      $nextUrut = $urut + 1;
      $nextUrutStr = (string)$nextUrut;
    
      // Pad the number based on its length
      if (strlen($nextUrutStr) == 1) {
          return $inisial . $bulan . $tahun . '00' . $nextUrutStr;
      } elseif (strlen($nextUrutStr) == 2) {
          return $inisial . $bulan . $tahun . '0' . $nextUrutStr;
      } else {
       return $inisial . $bulan . $tahun . $nextUrutStr;
      }
   }


   public function CreateCodeAkuntansi($tabel, $id, $inisial, $index, $panjang)
   {
      $sql = "SELECT max(" . $id . ") as max_id FROM `" . $tabel . "` WHERE " . $id . " LIKE '" . $inisial . "%'";
      $data = $this->dbh->query($sql)->fetch_array();
      $id_max = $data['max_id'];

      if ($index == '' && $panjang == '') {
         $no_urut   = (int) $id_max;
      } else if ($index != '' && $panjang == '') {
         $no_urut    = (int) substr($id_max, $index);
      } else {
         $no_urut   = (int) substr($id_max, $index, $panjang);
      }
      $no_urut   = $no_urut + 1;
      if ($index == '' && $panjang == '') {
         $id_baru  = $no_urut;
      } else if ($index != '' && $panjang == '') {
         $id_baru  = $inisial . $no_urut;
      } else {
         $id_baru   = $inisial . sprintf("%0$panjang" . "s", $no_urut);
      }

      return $id_baru;
   }

   public function form_input($type, $inputmode = null, $dataToggle = null, $dataTarget = null, $maxlength = null, $class, $name, $placeholder, $id = null, $value = null)
   {
      # example simple no array
      $html = "<input type='$type' inputmode='$inputmode' maxlength='$maxlength' data-bs-toggle='$dataToggle' data-bs-target='$dataTarget' class='$class' name='$name' placeholder='$placeholder' id='$id' value = '$value' required/>";
      return $html;

      # example array form_input($form_attribute);
      # $form_attribute = array('type' => 'text', 'class' => 'form-control', 'name' = > 'username', 'placeholder' = > 'username');
   }

   public function form_input_readonly($type, $inputmode = null, $dataToggle = null, $dataTarget = null, $maxlength = null, $class, $name, $placeholder, $id = null, $value = null)
   {
      # example simple no array
      $html = "<input type='$type' inputmode='$inputmode' maxlength='$maxlength' data-bs-toggle='$dataToggle' data-bs-target='$dataTarget' class='$class' name='$name' placeholder='$placeholder' id='$id' value = '$value' readonly required/>";
      return $html;

      # example array form_input($form_attribute);
      # $form_attribute = array('type' => 'text', 'class' => 'form-control', 'name' = > 'username', 'placeholder' = > 'username');
   }

   public function form_password($class, $name, $placeholder)
   {
      $html = "<input type='password' class='$class' name='$name' placeholder='$placeholder' id='password' required/>";
      return $html;
   }

   public function cmb_dinamis($name, $table, $display_column, $value_column, $selected_value = null)
   {
      $sql = "SELECT $value_column, $display_column FROM $table";
      $result =  $this->dbh->query($sql);

      $html = "<div class='col-sm-6'>";
      $html .= "<select name='$name' id='$name' class='form-select'>";
      $html .= "<option value=''>-- Pilih --</option>";

      if ($result->num_rows > 0) {
         while ($row = $result->fetch_assoc()) {
            // Cek apakah nilai saat ini adalah nilai yang dipilih
            $selected = ($row[$value_column] == $selected_value) ? 'selected' : '';
            $html .= "<option value='" . $row[$value_column] . "' $selected>" . $row[$display_column] . "</option>";
         }
      } else {
         $html .= "<option value=''>Tidak ada opsi tersedia</option>";
      }

      $html .= "</select>";
      $html .= "</div>";
      return $html;
   }

   public function cmb_dinamis2($name, $table, $where_list = null, $stts = null, $display_column, $value_column, $selected_value = null)
   {
      $sql = "SELECT $value_column, $display_column FROM $table WHERE $where_list = '$stts'";
      $result =  $this->dbh->query($sql);

      $html = "<div class='col-sm-6'>";
      $html .= "<select name='$name' id='$name' class='form-select'>";
      $html .= "<option value=''>-- Pilih --</option>";

      if ($result->num_rows > 0) {
         while ($row = $result->fetch_assoc()) {
            // Cek apakah nilai saat ini adalah nilai yang dipilih
            $selected = ($row[$value_column] == $selected_value) ? 'selected' : '';
            $html .= "<option value='" . $row[$value_column] . "' $selected>" . $row[$display_column] . "</option>";
         }
      } else {
         $html .= "<option value=''>Tidak ada opsi tersedia</option>";
      }

      $html .= "</select>";
      $html .= "</div>";
      return $html;
   }
}