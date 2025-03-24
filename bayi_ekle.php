<?php
if($hesap->id != "" AND $hesap->tipi != 0){
if($_POST){

$lokasyon		= $gvn->html_temizle($_POST["lokasyon"]);
$sira			= $gvn->zrakam($_POST["sira"]);
$adres			= $gvn->html_temizle($_POST["adres"]);
$telefon		= $gvn->html_temizle($_POST["telefon"]);
$gsm			= $gvn->html_temizle($_POST["gsm"]);
$email			= $gvn->html_temizle($_POST["email"]);
$google_maps	= $gvn->html_temizle($_POST["google_maps"]);


if($fonk->bosluk_kontrol($lokasyon) == true){
die($fonk->ajax_hata("Lütfen tüm alanları eksiksiz doldurun."));
}


try {

$ekle			= $db->prepare("INSERT INTO subeler_bayiler_19541956 SET turu=?,lokasyon=?,sira=?,adres=?,telefon=?,gsm=?,email=?,google_maps=?,dil=? ");
$ekle->execute(array('1',$lokasyon,$sira,$adres,$telefon,$gsm,$email,$google_maps,$dil));


$fonk->ajax_tamam("Bayi Eklendi.");
$fonk->yonlendir("index.php?p=bayiler",1500);

}catch(PDOException $e) {
$fonk->ajax_hata($e->getMessage());
}



}
}