<?php

namespace models;

use core\Database2;

class Example_model
{
   protected $db = null;
   private static $instance = null;

   public function __construct()
   {
      $this->db = Database2::getInstance();
   }

   public static function getInstance()
   {
      if (self::$instance == null) {
         self::$instance = new Example_model();
      }
      return self::$instance;
   }

   // public function GetByExample($table, $where, $id, $OrderBy){
   //    $resW = $this->db->qPrepare2("SELECT * FROM $table WHERE $where = ? order by $OrderBy asc");
   //    $resW->execute(array($id));
   //    $res = $resW->fetchAll();
   //    return $res;
   // }
}
