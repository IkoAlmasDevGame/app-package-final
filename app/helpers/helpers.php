<?php
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

function hitungHari($myDate1, $myDate2)
{
   $myDate1 = strtotime($myDate1);
   $myDate2 = strtotime($myDate2);

   return ($myDate2 - $myDate1) / (24 * 3600);
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
   if ($x < 12)
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
   $rupiah = "Rp" . $jumlah;

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
   $rupiah = "Rp" . $jumlah . ",-";

   return $rupiah;
}

# Format Tanggal
function format_tanggal($tgl)
{
   $blns = array("", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des");
   $hrs = array("Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab");
   $tgls = getdate(strtotime($tgl));
   $hr = (strlen($tgls['mday']) == 1 ? "0" . $tgls['mday'] : $tgls['mday']);
   $tanggal = $hrs[$tgls['wday']] . ", " . $hr . "-" . $blns[$tgls['mon']] . "-" . $tgls['year'];

   return $tanggal;
}

function format_tanggal_lahir($tpt, $tgl)
{
   $blns = array("", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des");
   $tgls = getdate(strtotime($tgl));
   $hr = (strlen($tgls['mday']) == 1 ? "0" . $tgls['mday'] : $tgls['mday']);
   $tanggal = $tpt . ", " . $hr . "-" . $blns[$tgls['mon']] . "-" . $tgls['year'];

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