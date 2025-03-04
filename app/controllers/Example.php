<?php

namespace controllers;

use model\Example_model;

class Example
{
   protected $konfig;
   public function __construct()
   {
      $this->konfig = new Example_model();
   }
}