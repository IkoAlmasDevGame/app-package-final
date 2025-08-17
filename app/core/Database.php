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
      $this->dbh = mysqli_connect($this->DB_HOST, $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_DATABASE);
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
      foreach ($data as $field => $value) {
         $values[] = "'" . addslashes($data[$field]) . "'";
         $fields[] = "'" . $field . "'";
      }
      $value_list = join(",", $values);
      $field_list = join(",", $fields);
      $query = "INSERT INTO " . $tabel . " (" . $field_list . ") VALUES (" . $value_list . ")";
      $hasil = $this->dbh->query($query);
      return $hasil;
   }

   public function SELECT($tabel)
   {
      $sql = "SELECT * FROM $tabel";
      $result = $this->dbh->prepare($sql);
      return $result;
   }

   public function UPDATE($tabel, $data, $where)
   {
      foreach ($where as $f_where => $v_where) {
         $vws[] = "'" . $f_where . "' = '" . addslashes($where[$f_where]) . "'";
      }
      foreach ($data as $field => $value) {
         $dt[] = "'" . $field . "' = '" . addslashes($data[$field]) . "'";
      }
      $data_list = join(",", $dt);
      $where_list = join(" AND ", $vws);
      $query = "UPDATE $tabel SET $data_list WHERE $where_list";
      $hasil = $this->dbh->query($query);
      return $hasil;
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

   public function CreateCode($tabel, $field)
   {
      $struktur = $this->dbh->query("SELECT * FROM $tabel LIMIT 1");
      $field = mysqli_fetch_field_direct($struktur, 1)->name;
      $mysql = $this->dbh->query("SELECT MAX($field) as $field FROM $tabel order by $field desc");
      $row = mysqli_fetch_array($mysql);

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

   public function CreateCodeInisial($tabel, $field, $inisial)
   {
      $struktur = $this->dbh->query("SELECT * FROM $tabel LIMIT 1");
      $field = mysqli_fetch_field_direct($struktur, 1)->name;
      $mysql = $this->dbh->query("SELECT MAX($field) as $field FROM $tabel order by $field desc");
      $row = mysqli_fetch_array($mysql);

      $urut = substr($row[$field], 9, 3);
      $tahun = date('y');
      $bulan = date('m');

      if (strlen((int)$urut + 1) == 1) {
         return $inisial . $bulan . $tahun . '00' . (int)$urut + 1;
      } elseif (strlen((int)$urut + 1) == 2) {
         return $inisial . $bulan . $tahun . '0' . (int)$urut + 1;
      } else {
         return $inisial . $bulan . $tahun . (int)$urut + 1;
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
