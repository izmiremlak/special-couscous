<?php
if($_POST){
if($hesap->id != "" AND $hesap->tipi != 0){

$id			= $gvn->rakam($_GET["id"]);
$snc		= $db->prepare("SELECT * FROM abloklar WHERE id=:ids");
$snc->execute(array('ids' => $id));

if($snc->rowCount() > 0 ){
$snc		= $snc->fetch(PDO::FETCH_OBJ);
}else{
die();
}


$sira			= $gvn->zrakam($_POST["sira"]);
$icon			= $_POST["icon"];
$aciklama		= $_POST["aciklama"];
$baslik			= $gvn->html_temizle($_POST["baslik"]);
$url			= $gvn->html_temizle($_POST["url"]);


if($fonk->bosluk_kontrol($baslik) == true OR $fonk->bosluk_kontrol($aciklama) == true OR $fonk->bosluk_kontrol($sira) == true){
die($fonk->ajax_uyari("Lütfen tüm alanları eksiksiz doldurun."));
}



$resim1tmp		= $_FILES['resim']["tmp_name"];
$resim1nm		= $_FILES['resim']["name"];


if($resim1tmp != ""){
$randnm			= strtolower(substr(md5(uniqid(rand())), 0,10)).$fonk->uzanti($resim1nm);
$resim			= $fonk->resim_yukle(false,'resim',$randnm,'../uploads',$gorsel_boyutlari['abloklar']['orjin_x'],$gorsel_boyutlari['abloklar']['orjin_y']);

## veritabanı işlevi
$avgn			= $db->prepare("UPDATE abloklar SET resim=:image WHERE id=:id");
$avgn->execute(array('image' => $resim, 'id' => $snc->id));
if($avgn){
$fonk->ajax_tamam('Resim Güncellendi');
?><script type="text/javascript">
$(document).ready(function(){
$('#resim_src').attr("src","../uploads/thumb/<?=$resim;?>");
});
</script><?
}

}


$dzn			= $db->prepare("UPDATE abloklar SET sira=?,icon=?,baslik=?,aciklama=?,url=? WHERE id=".$snc->id);
$dzn->execute(array($sira,$icon,$baslik,$aciklama,$url));
    
	

if($dzn){
$fonk->ajax_tamam("Anasayfa Blok Güncellendi.");
}else{
$fonk->ajax_hata("Bir hata oluştu.");
}




}
}