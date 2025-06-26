<?php

namespace core;

class Server_Connection
{
   protected $ipaddress;

   public function __construct()
   {
      $this->ipaddress = '';
   }

   //menampilkan ip address menggunakan function $_SERVER
   public function get_client_ip()
   {
      if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
         $this->ipaddress = $_SERVER['HTTP_CLIENT_IP'];
      } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
         // Bisa jadi ada beberapa IP dipisah koma, ambil yang pertama
         $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
         $this->ipaddress = trim($ipList[0]);
      } else {
         $this->ipaddress = $_SERVER['REMOTE_ADDR'];
      }
      return $this->ipaddress;
   }

   //menampilkan jenis web browser pengunjung
   public function get_client_browser()
   {
      $userAgent = $_SERVER['HTTP_USER_AGENT'];
      if (strpos($userAgent, 'Firefox') !== false) {
         return 'Mozilla Firefox';
      } elseif (strpos($userAgent, 'Chrome') !== false) {
         return 'Google Chrome';
      } elseif (strpos($userAgent, 'Safari') !== false) {
         return 'Apple Safari';
      } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
         return 'Opera';
      } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
         return 'Internet Explorer';
      }
      return 'Unknown Browser';
   }
}