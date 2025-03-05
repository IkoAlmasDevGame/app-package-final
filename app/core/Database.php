<?php

namespace core;

class Database
{
   protected $DB_HOST = DB_HOST;
   protected $DB_PORT = DB_PORT;
   protected $DB_DATABASE = DB_NAME;
   protected $DB_USERNAME = DB_USERNAME;
   protected $DB_PASSWORD = DB_PASSWORD;
   protected $dbh;

   public function __construct()
   {
      $this->dbh = mysqli_connect($this->DB_HOST, $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_DATABASE, $this->DB_PORT) or mysqli_connect_errno();
   }

   # Khusus MVC Database khusus Models

   public function query($sql)
   {
      return $this->dbh->query($sql, MYSQLI_STORE_RESULT);
      # insert, update, read (CRU)
   }

   public function SELECT_WHERE($tabel, $where, $name)
   {
      $sql = "SELECT * FROM $tabel WHERE $where = '$name'";
      return $this->dbh->query($sql, MYSQLI_STORE_RESULT);
      return $result;
      # under English
      # SELECT WHERE on the core database is to search for data desired by the website programmer and must match the database table row example like id_admin or others.

      # under Indonesian
      # SELECT WHERE pada core Database ini untuk mencari data yang diinginkan oleh programmer website dan harus sesuai row tabel database contoh seperti id_admin atau yang lainnya.
   }

   public function delete($table, $name)
   {
      return $this->dbh->query("DELETE FROM $table WHERE $name='$name'");
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

   public function CreateCode($tabel, $inisial)
   {
      $struktur = mysqli_query($this->dbh, "SELECT * FROM $tabel LIMIT 1");
      $field    = mysqli_fetch_field_direct($struktur, 0)->name;
      $panjang  = mysqli_fetch_field_direct($struktur, 0)->length;
      $qry  = $this->dbh->query("SELECT MAX(" . $field . ") FROM " . $tabel);
      $row  = mysqli_fetch_array($qry);

      if ($row[0] == "") {
         $angka = 0;
      } else {
         $angka = substr($row[0], strlen($inisial));
      }

      $angka++;
      $angka = strval($angka);
      $tmp   = "";
      for ($i = 1; $i <= ($panjang - strlen($inisial) - strlen($angka)); $i++) {
         $tmp = $tmp . "0";
      }

      return $inisial . $tmp . $angka;
   }

   public function form_input($type, $class, $name, placeholder, $value = null){
      # example simple no array
      $html = "<input type='$text' class='$class' name='$name' placeholder='$placeholder' value = '$value' />";
      return $html;
      
      # example array form_input($form_attribute);
      # $form_attribute = array('type' => 'text', 'class' => 'form-control', 'name' = > 'username', 'placeholder' = > 'username');
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
}
