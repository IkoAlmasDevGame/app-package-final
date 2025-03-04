<?php

namespace model;

use core\Database;

class Example_model
{
   protected $tabel = ""; # database in website phpmyadmin
   protected $dbh;

   public function __construct()
   {
      $this->dbh = new Database;
   }
}