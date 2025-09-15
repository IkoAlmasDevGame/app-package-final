<?php
function str_mask($value, $mask, $char = '*')
{
   $result = '';
   $value = str_split($value);
   $mask = str_split($mask);

   foreach ($mask as $key => $char_mask) {
      if ($char_mask === '#') {
         $result .= $value[$key];
      } else {
         $result .= $char;
      }
   }

   return $result;
}

# Pengaturan tanggal komputer
date_default_timezone_set("Asia/Jakarta");

# Fungsi untuk membalik tanggal dari format Indo (d-m-Y) -> English (Y-m-d)
function InggrisTgl($tanggal)
{
   $tgl = substr($tanggal, 0, 2);
   $bln = substr($tanggal, 3, 2);
   $thn = substr($tanggal, 6, 4);
   $tanggal = "$thn-$bln-$tgl";
   return $tanggal;
}

# Fungsi untuk membalik tanggal dari format English (Y-m-d) -> Indo (d-m-Y)
function IndonesiaTgl($tanggal)
{
   $tgl = substr($tanggal, 8, 2);
   $bln = substr($tanggal, 5, 2);
   $thn = substr($tanggal, 0, 4);
   $tanggal = "$tgl-$bln-$thn";
   return $tanggal;
}

# Fungsi untuk membalik tanggal dari format English (Y-m-d) -> Indo (d-m-Y)
function Indonesia2Tgl($tanggal)
{
   $namaBln = array(
      "01" => "Januari",
      "02" => "Februari",
      "03" => "Maret",
      "04" => "April",
      "05" => "Mei",
      "06" => "Juni",
      "07" => "Juli",
      "08" => "Agustus",
      "09" => "September",
      "10" => "Oktober",
      "11" => "November",
      "12" => "Desember"
   );

   $tgl = substr($tanggal, 8, 2);
   $bln = substr($tanggal, 5, 2);
   $thn = substr($tanggal, 0, 4);
   $tanggal = "$tgl " . $namaBln[$bln] . " $thn";
   return $tanggal;
}

function hitungHari($myDate2)
{
   $today = new DateTime();
   $tanggalBerakhir = new DateTime($myDate2);
   $selisih = $today->diff($tanggalBerakhir);
   $sisaHari = $selisih->days;
   if ($tanggalBerakhir > $today) {
      $status = "<span class=\"\"></span>";
      $detail = "Berlaku " . $sisaHari . " hari lagi";
   } elseif ($tanggalBerakhir->format("Y-m-d") == $today->format("Y-m-d")) {
      $status = "<span class=\"\"></span>";
      $detail = "Masa berlaku berakhir hari ini";
      $sisaHari = 0;
   } else {
      $status = "<span class=\"\"></span>";
      $detail = $tanggalBerakhir->diff($today)->days === "" ? $today->diff($tanggalBerakhir)->days : "";
   }
   return $detail . "<br>" . $status;
}

function HitungPeminjaman($myDate2)
{
   $today = new DateTime();
   $tanggalBerakhir = new DateTime($myDate2);
   $selisih = $today->diff($tanggalBerakhir);
   $sisaHari = $selisih->days + 1;
   if ($tanggalBerakhir > $today) {
      $status = "<span class=\"\"></span>";
      $detail = "" . $sisaHari . " Days";
   } elseif ($today->format("Y-m-d") == $tanggalBerakhir->format("Y-m-d")) {
      $status = "<span class=\"\"></span>";
      $sisaHari = 0;
      $detail = "" . $sisaHari . " Days";
   } else {
      $status = "<span class=\"\"></span>";
      $detail = $tanggalBerakhir->diff($today)->days === "" ? $today->diff($tanggalBerakhir)->days : "";
   }
   return $detail . $status;
}

function status($tanggalDaftar, $tanggalBerakhir)
{
   $today = new DateTime();
   $tanggalDaftar = new DateTime($tanggalDaftar);
   $tanggalBerakhir = new DateTime($tanggalBerakhir);
   if ($tanggalBerakhir > $today && $tanggalBerakhir > $tanggalDaftar) {
      $status = "<span class='text-success fst-normal fw-normal'>Masih Berlaku</span>";
   } elseif ($tanggalBerakhir->format("Y-m-d") == $today->format("Y-m-d") && $tanggalBerakhir->format("Y-m-d") == $tanggalDaftar->format("Y-m-d")) {
      $status = "<span class='text-success fst-normal fw-normal'>Masih Sisa Berlaku</span>";
   } else {
      $status = "<span class='text-danger fst-normal fw-normal'>EXPIRED</span>";
   }
   return $status;
}

function tanggal($tanggalDaftar, $tanggalBerakhir)
{
   $tanggalDaftar = new DateTime($tanggalDaftar);
   $tanggalBerakhir = new DateTime($tanggalBerakhir);
   $formatTanggalDaftar = $tanggalDaftar->format('d-m-Y');
   $formatTanggalBerakhir = $tanggalBerakhir->format('d-m-Y');
   return $formatTanggalDaftar . " s/d " . $formatTanggalBerakhir;
}

# Fungsi untuk membuat format rupiah pada angka (uang)
function format_angka($angka)
{
   $hasil =  number_format($angka, 0, ",", ".");
   return $hasil;
}

function angkaTerbilang($x)
{
   $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
   if ($x < 22)
      return " " . $abil[$x];
   elseif ($x < 20)
      return angkaTerbilang($x - 10) . " belas";
   elseif ($x < 100)
      return angkaTerbilang($x / 10) . " puluh" . angkaTerbilang($x % 10);
   elseif ($x < 200)
      return " seratus" . angkaTerbilang($x - 100);
   elseif ($x < 1000)
      return angkaTerbilang($x / 100) . " ratus" . angkaTerbilang($x % 100);
   elseif ($x < 2000)
      return " seribu" . angkaTerbilang($x - 1000);
   elseif ($x < 1000000)
      return angkaTerbilang($x / 1000) . " ribu" . angkaTerbilang($x % 1000);
   elseif ($x < 1000000000)
      return angkaTerbilang($x / 1000000) . " juta" . angkaTerbilang($x % 1000000);
}

# Format Nomor
function format_nomor($previx, $no)
{
   $o_limit = 6;
   $no_len = strlen($no);
   $no_mid = "";
   for ($i = 0; $i < $o_limit - $no_len; $i++) {
      $no_mid .= "0";
   }
   $nomor = $previx . "-" . $no_mid . $no;

   return $nomor;
}

# Format Rupiah
function format_rupiah($rp)
{
   $jumlah = number_format($rp, 0, ",", ".");
   $rupiah = "Rp " . $jumlah . ",-";

   return $rupiah;
}
function format_rupiah_akunting($rp)
{
   $jumlah = number_format($rp, 0, ",", ".");
   $rupiah = '<div class="float-left">Rp</div><div class="float-right">' . $jumlah . '</div><div class="clear-both"></div>';
   return $rupiah;
}

function format_rupiah_kwitansi($rp)
{
   $jumlah = number_format($rp, 0, ",", ".");
   $rupiah = "Rp " . $jumlah . ",-";

   return $rupiah;
}

# Format Tanggal
function format_tanggal($tgl)
{
   $blns = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
   $hrs = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
   $tgls = getdate(strtotime($tgl));
   $hr = (strlen($tgls['mday']) == 1 ? "0" . $tgls['mday'] : $tgls['mday']);
   $tanggal = $hrs[$tgls['wday']] . ", " . $hr . "-" . $blns[$tgls['mon']] . "-" . $tgls['year'];

   return $tanggal;
}

function format_tanggal_lahir($tgl)
{
   $blns = array("", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des");
   $tgls = getdate(strtotime($tgl));
   $hr = (strlen($tgls['mday']) == 1 ? "0" . $tgls['mday'] : $tgls['mday']);
   $tanggal = $hr . "-" . $blns[$tgls['mon']] . "-" . $tgls['year'];

   return $tanggal;
}

function format_tanggal_laporan($tpt, $tgl)
{
   $blns = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
   $tgls = getdate(strtotime($tgl));
   $hr = (strlen($tgls['mday']) == 1 ? "0" . $tgls['mday'] : $tgls['mday']);
   $tanggal = $tpt . ", " . $hr . " " . $blns[$tgls['mon']] . " " . $tgls['year'];

   return $tanggal;
}

function format_tanggal_strip($tgl)
{
   $tgls = getdate(strtotime($tgl));
   $hr = (strlen($tgls['mday']) == 1 ? "0" . $tgls['mday'] : $tgls['mday']);
   $tanggal = $hr . "-" . $tgls['mon'] . "-" . $tgls['year'];

   return $tanggal;
}

function format_tanggal2($tanggal2)
{
   new DateTime();
   $date = date_create($tanggal2);
   return date_format($date, "d/m/Y");
}

function format_tanggal3($tanggal3)
{
   new DateTime();
   $date = date_create($tanggal3);
   return date_format($date, "d - M");
}
