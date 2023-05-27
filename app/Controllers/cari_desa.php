<?= $this->include('partials/modal_ubah_kredit_kegiatan') ?>
<?php
include("config.php");
  
$tampil=mysql_query("SELECT * FROM kdDesa WHERE kdKec=’$_POST[kdKec]‘");
$jml=mysql_num_rows($tampil);
if($jml > 0){
echo"<option selected>- Pilih Kota -</option>";
while($r=mysql_fetch_array($tampil)){
echo "<option value=$r[kdDesa]>$r[nmDesa]</option>";
}}
else{
    echo "<option selected>- Data Wilayah Tidak Ada, Pilih Yang Lain -</option>";
}