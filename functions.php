<?php


@ob_start();
@session_start();
header("Content-Type:text/html; Charset=utf8");
if (!function_exists("curl_init") || !function_exists("curl_exec") || !function_exists("curl_setopt")) {
    exit("Sunucunuz'da \"CURL\" kütüphanesi bulunmuyor. Lütfen hostinginize kurun.");
}
$xsd001 = "d20a8a4f793b68fa3f2fdaea7be0ad08";
$xsd002 = "ce73fcd9aff4512af53f83d0c5148d70";
$xsd003 = "ae60c1d0abee81eccac89c14072edbf0";
$xsd004 = "b735453157f38d34b65d935ff57d8082";
$xsd005 = "cc72f0acf964f91b93e76b25dd97d77c";
$xsd006 = "527cbb94538f6768970d0fc630ee0acf";
$xsd007 = "39b1203c5b79029c43302200d3803d44";
$xsd008 = "c7676f1c1b4039680f8f23801fe15c95";
$xsd009 = "e9f6f6589c0c38dbfaead4cc72cfd35d";
$xsd010 = "bae960ab5ab76210a41d8f104cec5d4a";
require_once "settings/configure.php";
require_once "settings/modules.php";
require_once "methods/mdetect.php";
require_once "methods/msagete.php";
require_once "methods/aiolo.php";
require_once "methods/akhroe.php";
require_once "methods/nereu.php";
require_once "methods/kyziko.php";
require_once "methods/magnes.php";
require_once "methods/learkho.php";
$gvn = @new msagete_security();
$fonk = @new learkho_functions();
$pg = @new nereu_bootPagination();
$pagent = @new pagenate();
$gayarlar = $db->query("SELECT * FROM gayarlar_19541956")->fetch(PDO::FETCH_OBJ);
$protokol = $gayarlar->smtp_protokol != "" ? true : false;
define("MAIL_HOST", $gayarlar->smtp_host);
define("MAIL_PORT", $gayarlar->smtp_port);
define("MAIL_SECURE", $protokol);
define("MAIL_SMTPSecure", $gayarlar->smtp_protokol);
define("MAIL_USER", $gayarlar->smtp_username);
define("MAIL_PASSWORD", $gayarlar->smtp_password);
define("MAIL_FROMNAME", $gayarlar->smtp_fromname);
define("SMS_BASLIK", $gayarlar->sms_baslik);
define("SMS_USERNAME", $gayarlar->sms_username);
define("SMS_PASSWORD", $gayarlar->sms_password);
define("ADMIN_TEL", $gayarlar->rez_tel);
define("IYZICO_KEY", $gayarlar->iyzico_key);
define("IYZICO_SECRET_KEY", $gayarlar->iyzico_secret_key);
define("MAGAZA_NO", $gayarlar->paytr_magaza_no);
define("MAGAZA_KEY", $gayarlar->paytr_magaza_key);
define("MAGAZA_SALT", $gayarlar->paytr_magaza_salt);
define("THEME_DIR", "modules/");
$dil = $_COOKIE["dil"];
$dil = $gvn->harf_rakam($dil);
$dil = $fonk->kisalt($dil, 0, 15);
$dil_get = $_GET["dil"];
$dil_get = $gvn->harf_rakam($dil_get);
$dil_get = $fonk->kisalt($dil_get, 0, 15);
$lg = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
if ($dil != "") {
    $bakkvrmi = $db->prepare("SELECT kisa_adi FROM diller_19541956 WHERE kisa_adi=?");
    $bakkvrmi->execute(array($dil));
    if ($bakkvrmi && $bakkvrmi->rowCount() < 1) {
        $defdil = $db->query("SELECT kisa_adi FROM diller_19541956 ORDER BY id ASC")->fetch(PDO::FETCH_OBJ);
        $dil = $defdil->kisa_adi;
        setcookie("dil", $dil, time() + 60 * 60 * 24 * 30);
    }
}
if ($gayarlar->default_dil == "oto" && $dil == "") {
    $bakkvrmi = $db->prepare("SELECT kisa_adi FROM diller_19541956 WHERE kisa_adi=?");
    $bakkvrmi->execute(array($lg));
    if ($bakkvrmi && 0 < $bakkvrmi->rowCount()) {
        setcookie("dil", $lg, time() + 60 * 60 * 24 * 30);
        $dil = $lg;
    } else {
        $defdil = $db->query("SELECT kisa_adi FROM diller_19541956 ORDER BY id ASC")->fetch(PDO::FETCH_OBJ);
        $dil = $defdil->kisa_adi;
        setcookie("dil", $dil, time() + 60 * 60 * 24 * 30);
    }
} else {
    if ($gayarlar->default_dil != "oto" && $dil == "") {
        $dil = $gayarlar->default_dil;
        setcookie("dil", $dil, time() + 60 * 60 * 24 * 30);
    }
}
if ($dil_get != "") {
    $dilsorgu = $db->prepare("SELECT * FROM diller_19541956 WHERE kisa_adi=:dil");
    $dilsorgu->execute(array("dil" => $dil_get));
    if ($dilsorgu && $dilsorgu->rowCount() != 0) {
        setcookie("dil", $dil_get, time() + 60 * 60 * 24 * 30);
        $dil = $dil_get;
        $xs = str_replace(array("&dil=" . $dil, "?dil=" . $dil), array("", ""), $_SERVER["REQUEST_URI"]);
        header("Location:" . $xs);
    }
}
$latx = @file_get_contents(THEME_DIR . "diller/" . $dil . ".txt");
$latx = $latx == "" ? @file_get_contents("../" . THEME_DIR . "diller/" . $dil . ".txt") : $latx;
$dayarlar = $db->prepare("SELECT * FROM ayarlar_19541956 WHERE dil=:dil");
$dayarlar->execute(array("dil" => $dil));
$dayarlar = $dayarlar->fetch(PDO::FETCH_OBJ);
$ck_acid = $_COOKIE["acid"];
$ck_acpw = $_COOKIE["acpw"];
$ck_scret = $_COOKIE["acsecret"];
$ss_acid = $_SESSION["acid"];
$ss_acpw = $_SESSION["acpw"];
if ($ss_acid != "" && $ss_acpw != "") {
	/*sadece site sahibinin admin e girebilmesi için site_id_555=000 yapıldı*/
    $kontrol = $db->prepare("SELECT * FROM hesaplar WHERE site_id_555=000 AND id=:id AND parola=:parola AND durum=0");
    $kontrol->execute(array("id" => $ss_acid, "parola" => $ss_acpw));
    if ($kontrol && 0 < $kontrol->rowCount()) {
        $hesap = $kontrol->fetch(PDO::FETCH_OBJ);
    } else {
        accountlogout();
    }
} else {
    if ($ck_acid != "" && $ck_acpw != "") {
        $usecret = $fonk->login_secret_key($ck_acid, $ck_acpw);
	/*sadece site sahibinin admin e girebilmesi için site_id_555=000 yapıldı*/
        $kontrol = $db->prepare("SELECT * FROM hesaplar WHERE site_id_555=000 AND id=:id AND parola=:parola AND login_secret=:secret AND durum=0");
        $kontrol->execute(array("id" => $ck_acid, "parola" => $ck_acpw, "secret" => $usecret));
        if (0 < $kontrol->rowCount()) {
            $hesap = $kontrol->fetch(PDO::FETCH_OBJ);
            $_SESSION["acid"] = $ck_acid;
            $_SESSION["acpw"] = $ck_acpw;
        } else {
            accountlogout();
        }
    }
}
if (!function_exists("curl_init") || !function_exists("curl_exec") || !function_exists("curl_setopt")) {
    exit("PHP Curl Library not found");
}
define("__DOMAIN__", $domain);
require_once THEME_DIR . "codes_required.php";
$gunler = explode(",", dil("GUNLER"));
$aylar = array("");
$aylarx = explode(",", dil("AYLAR"));
$aylar = array_merge($aylar, $aylarx);
$periyod = array("gunluk" => dil("TX520"), "aylik" => dil("TX521"), "yillik" => dil("TX522"));
$reklam_alanlari = array("Yok", "Anasayfa Reklam Alanı 1 728 x 90", "Anasayfa Reklam Alanı 2 728 x 90", "İlan Listesi Üstü 728 x 90", "Sayfa Detay Sağ 336 x 280", "İlan Detay Özellikler Altı 728 x 90", "Hesap Bilgilerim 728 x 90", "Favori ilanlarım 728 x 90", "Aktif ilanlarım 728 x 90", "Pasif ilanlarım 728 x 90", "Profil Detay Üstü 728 x 90", "Danışmanlarım 728 x 90");
$emlkdrm = explode("<+>", dil("EMLK_DRM"));
list($emstlk, $emkrlk, $emgkrlk) = $emlkdrm;
class home_security
{

    public function tcNoCheck($tckimlik)
    {
        $olmaz = array("11111111110", "22222222220", "33333333330", "44444444440", "55555555550", "66666666660", "7777777770", "88888888880", "99999999990");
        if ($tckimlik[0] == 0 || !ctype_digit($tckimlik) || strlen($tckimlik) != 11) {
            return false;
        }
        $a = 0;
        while ($a < 9) {
            $ilkt = $ilkt + $tckimlik[$a];
            $a = $a + 2;
        }
        $a = 1;
        while ($a < 9) {
            $sont = $sont + $tckimlik[$a];
            $a = $a + 2;
        }
        $a = 0;
        while ($a < 10) {
            $tumt = $tumt + $tckimlik[$a];
            $a = $a + 1;
        }
        if (($ilkt * 7 - $sont) % 10 != $tckimlik[9] || $tumt % 10 != $tckimlik[10]) {
            return false;
        }
        foreach ($olmaz as $olurmu) {
            if ($tckimlik == $olurmu) {
                return false;
            }
        }
        return true;
    }
}
class home_functions
{
    private $lang_data = array();

    public function get_lang($lang, $key)
    {
        $lang_data = $this->lang_data;
        if (!isset($lang_data[$lang])) {
            $lang_data[$lang] = file_get_contents(__DIR__ . "/modules/diller/" . $lang . ".txt");
        }
        $latx = $lang_data[$lang];
        preg_match("@" . $key . "={(.*?)}@si", $latx, $res);
        return $res[1];
    }

    public function SayiDuzelt($number)
    {
        return str_replace(",", ".", number_format($number));
    }

    public function json_encode_tr($str)
    {
        return json_encode($str, JSON_UNESCAPED_UNICODE);
    }

    public function dil_aktar($tablo_adi, $dili, $yeni_dili, $refler = array())
    {
        global $db;
        global $gvn;
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $fields = $db->query("DESCRIBE " . $tablo_adi);
            $fields = $fields->fetchAll(PDO::FETCH_OBJ);
            $kolonlar = array();
            foreach ($fields as $field) {
                $adi = $field->Field;
                if ($adi != "id") {
                    $kolonlar[] = $adi;
                }
            }
            $xprepare = implode(",", $kolonlar);
            $prepare = str_replace(",", "=?,", $xprepare) . "=?";
            $eklenenler = array();
            $ekwhere = "";
            $veriler = $db->prepare("SELECT * FROM " . $tablo_adi . " WHERE " . $ekwhere . "dil=? ORDER BY id ASC");
            $veriler->execute(array($dili));
            while ($row = $veriler->fetch(PDO::FETCH_ASSOC)) {
                unset($execute);
                $execute = array();
                foreach ($kolonlar as $kolon) {
                    if ($kolon != "id") {
                        if ($kolon == "dil") {
                            $execute[] = $yeni_dili;
                        } else {
                            if ($kolon == "ustu") {
                                $ustu = $gvn->zrakam($row["ustu"]);
                                $ustune = $gvn->zrakam($eklenenler[$ustu]);
                                $execute[] = $ustune == 0 ? $ustu : $ustune;
                            } else {
                                if ($kolon == "kategori_id" || $kolon == "galeri_id") {
                                    $execute[] = $gvn->zrakam($refler[0][$row[$kolon]]);
                                } else {
                                    if ($kolon == "sayfa" || $kolon == "sayfa_id") {
                                        $execute[] = $gvn->zrakam($refler[1][$row[$kolon]]);
                                    } else {
                                        $execute[] = $row[$kolon];
                                    }
                                }
                            }
                        }
                    }
                }
                $kaydet = $db->prepare("INSERT INTO " . $tablo_adi . " SET " . $prepare);
                $kaydet->execute($execute);
                if ($eklenenler[$row["id"]] == "") {
                    $nid = $db->lastInsertId();
                    $eklenenler[$row["id"]] = $nid;
                }
            }
            return $eklenenler;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function selectbox_menu_list($kat_id = 0, $sub = false, $count = 0, $selected_id = 0)
    {
        global $db;
        $sql = $db->query("SELECT id,baslik FROM menuler_19541956 WHERE ustu=" . $kat_id . " AND dil='" . $GLOBALS["dil"] . "' ORDER BY sira ASC");
        if ($sql && 0 < $sql->rowCount()) {
            $count = $sub == true ? $count + 1 : $count;
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $activited = $row[id] == $selected_id ? "selected" : "";
                echo "\n<option value='" . $row[id] . "' " . $activited . ">" . str_repeat("-", $count) . " " . $row[baslik] . "</option>\n";
                $this->selectbox_menu_list($row["id"], true, $count, $selected_id);
            }
        }
    }

    public function admin_menu_listesi($kat_id = 0)
    {
        global $db;
        $sql = $db->query("SELECT * FROM menuler_19541956 WHERE ustu=" . $kat_id . " AND dil='" . $GLOBALS["dil"] . "' ORDER BY sira ASC");
        if ($sql && 0 < $sql->rowCount()) {
            echo "\n<ul>\n";
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $rwsayfa = $db->query("SELECT id,url FROM sayfalar WHERE site_id_555=999 AND id=" . $row["sayfa"] . " ORDER BY id DESC");
                $rwsayfa = $rwsayfa->fetch(PDO::FETCH_OBJ);
                $mlink = $GLOBALS["dayarlar"]->permalink == "Evet" ? $rwsayfa->url . ".html" : "index.php?p=sayfa&id=" . $rwsayfa->id;
                echo "<li>\r\n<a href=\"index.php?p=menuler&sil=";
                echo $row["id"];
                echo "\"><i style=\"color:#ea0000;\" class=\"md md-delete\"></i></a>\r\n<a href=\"index.php?p=menuler&duzenle=";
                echo $row["id"];
                echo "\"><i style=\"color:#555;\" class=\"md md-mode-edit\"></i></a>\r\n<a style=\"font-size:14px;line-height:18px;\" href=\"index.php?p=menuler&duzenle=";
                echo $row["id"];
                echo "\">» ";
                echo $row["baslik"];
                echo "</a>\r\n";
                $this->admin_menu_listesi($row["id"]);
                echo "</li>\r\n";
            }
            echo "</ul>\n";
        }
    }

    public function iyzico_cek()
    {
        require_once "methods/iyzico_app/IyzipayBootstrap.php";
        require_once "methods/iyzico_app/samples/Sample.php";
        IyzipayBootstrap::init();
    }

    public function paytr_frame($adsoyad, $email, $adres, $telefon, $baslik, $tutar, $oid)
    {
        $merchant_id = MAGAZA_NO;
        $merchant_key = MAGAZA_KEY;
        $merchant_salt = MAGAZA_SALT;
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
        }
        $user_ip = $ip;
        $merchant_oid = $oid;
        $email = $email == "" ? "yok@example.com" : $email;
        $payment_amount = $tutar;
        $no_installment = 0;
        $max_installment = 9;
        $user_name = $adsoyad == "" ? "Yok" : $adsoyad;
        $user_address = $adres == "" ? "Adres tanımlanmadı" : $adres;
        $user_phone = $telefon == "" ? "05000000000" : $telefon;
        $merchant_ok_url = PAYTR_OK_URL;
        $merchant_fail_url = PAYTR_FAIL_URL;
        $user_basket = base64_encode(json_encode(array(array($baslik, $tutar, 1))));
        $debug_on = 0;
        $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment;
        $paytr_token = base64_encode(hash_hmac("sha256", $hash_str . $merchant_salt, $merchant_key, true));
        $post_vals = array("merchant_id" => $merchant_id, "user_ip" => $user_ip, "merchant_oid" => $merchant_oid, "email" => $email, "payment_amount" => $payment_amount, "paytr_token" => $paytr_token, "user_basket" => $user_basket, "debug_on" => $debug_on, "no_installment" => $no_installment, "max_installment" => $max_installment, "user_name" => $user_name, "user_address" => $user_address, "user_phone" => $user_phone, "merchant_ok_url" => $merchant_ok_url, "merchant_fail_url" => $merchant_fail_url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $xresult = @curl_exec($ch);
        if (curl_errno($ch)) {
            echo "PAYTR IFRAME connection error. err:" . curl_error($ch);
        }
        curl_close($ch);
        $result = json_decode($xresult, 1);
        if ($result[status] == "success") {
            $token = $result[token];
            echo "        <style>\r\n        .os_icerik .row {width:50%;margin:auto;}\r\n        </style>\r\n\t\t<iframe src=\"https://www.paytr.com/odeme/guvenli/";
            echo $token;
            echo "\" id=\"paytriframe\" frameborder=\"0\" scrolling=\"no\" style=\"width: 100%;\"></iframe>\r\n\t<script type=\"text/javascript\" src=\"methods/iframeResizer.min.js\"></script>\r\n\t\t<script type=\"text/javascript\">\r\n\t\t\tiFrameResize({\r\n\t\t\t\tlog                     : true,                  // Enable console logging\r\n\t\t\t\tinPageLinks             : true,\r\n\t\t\t\tresizedCallback         : function(messageData){ // Callback fn when resize is received\r\n\t\t\t\t\t\$('p#callback').html(\r\n\t\t\t\t\t\t'<b>Frame ID:</b> '    + messageData.iframe.id +\r\n\t\t\t\t\t\t' <b>Height:</b> '     + messageData.height +\r\n\t\t\t\t\t\t' <b>Width:</b> '      + messageData.width +\r\n\t\t\t\t\t\t' <b>Event type:</b> ' + messageData.type\r\n\t\t\t\t\t);\r\n\t\t\t\t},\r\n\t\t\t\tmessageCallback         : function(messageData){ // Callback fn when message is received\r\n\t\t\t\t\t\$('p#callback').html(\r\n\t\t\t\t\t\t'<b>Frame ID:</b> '    + messageData.iframe.id +\r\n\t\t\t\t\t\t' <b>Message:</b> '    + messageData.message\r\n\t\t\t\t\t);\r\n\t\t\t\t\talert(messageData.message);\r\n\t\t\t\t\tdocument.getElementsByTagName('iframe')[0].iFrameResizer.sendMessage('Hello back from parent page');\r\n\t\t\t\t},\r\n\t\t\t\tclosedCallback         : function(id){ // Callback fn when iFrame is closed\r\n\t\t\t\t\t\$('p#callback').html(\r\n\t\t\t\t\t\t'<b>IFrame (</b>'    + id +\r\n\t\t\t\t\t\t'<b>) removed from page.</b>'\r\n\t\t\t\t\t);\r\n\t\t\t\t}\r\n\t\t\t});\r\n\r\n\t\t</script>\r\n\t\t";
        } else {
            echo "Error failed. reason:" . $result[reason];
            print_r($result);
            echo "\nResponse: " . $xresult;
        }
    }

    public function UyelikAyarlar()
    {
        global $gayarlar;
        $uyarlar = $gayarlar->uyelik_ayarlar;
        $uyarlar = json_decode($uyarlar, true);
        return $uyarlar;
    }
}
function awT0AGQGvU7C6R7wmcyqiatrCL($a, $b)
{
    return $a . "-" . $b . "e7PifOlmW5N9YsBm03ww49RF";
}

function a7r4pwkmiruvs1zxuihdj1xt39f()
{
    return "A_ssDz<bOdaIMTt:)C`/j0+PM^Y)cEm*JEJo?P^;|}#";
}
function anryqdhtfasxg5vcsz5ffnojswd()
{
    return "+&xuWhIpO&y+duB=Icvt9o3cpG>?ge~are&KzK+Po^|";
}
function avwq8tdlxclndtfebdst2yxmbfb()
{
    return "q[gct3iysutg[U-f5XO<-KFnbe?</as:sAZvXdlhzDs";
}
function atnpvax1gcoessuoriqgtzeoeqp()
{
    return "K[C:E/&Kod85>]tfw[B>-7mOuzCuV6VXKVLZZ/cr(TL";
}
function aauwf4iemaiddz8yg4leyvsu7yv()
{
    return "MG2HTy:`~`LpI?GY9Sc4}kj=~VN9<nC9)G}*g1TAw%9";
}
function alkzosk6dbmtpga7yicrxj98u4m()
{
    return "}7v)%4te#PL/S=2<j^y#6t2Hj^1%HovP|n=sVI`U[_Z";
}
function adfizwwskfs8iqw1dzgf66riafr()
{
    return "ZN5fk:;|ME1<L`iEWVp|P74M|e:}rIua&5H!luVnkF|";
}
function apjcbsvy1e5dif9eng8ydnfxaaq()
{
    return "Bo]jP!yJEzc=iZsE`N|iuO!h8B4VZWRT;L0Uk48K^yz";
}
function axaonjpuqzxf50d3htwxw5lbjof()
{
    return "VMtQ~Jg!}&]Y(}tPjX&v;H@J1|0wRaKuJpW*r[28%#5";
}
function avp1jajhjukpk19osq3pmvswbex()
{
    return "Atu^t6LV7Xk]bRZ/7#Gm4rFIJ[AW|P_GPGpu**uG~Lz";
}
function a7wxkms97qdy0mle9cyxosfqop0()
{
    return "-p?XpmkOAn[CQtePhW`_B0)/AYv#^a*yCN@?;;C[+ge";
}
function aq16mlg6jxd0v1fewlh4vlqka7h()
{
    return "/-TPT62X8hm#*j&=#/d@<5F(KqVF5^t>E:@|rPdyt:6";
}
function akua0vj1nr3zu1ca67mtoo1rg3c()
{
    return "EU[}mE[lTO]CSjKtGkS&KI0_c&gfb>*CIOqWxgzA#Qw";
}
function anzkrsm9iakoaavz8swzfa7gocw()
{
    return "OoM2kI?+Zp7ou+xRI#[vd9Z;/lX5@?g(nlGn|FY3Qjb";
}
function aumoykpgnobut9sslb8zmrzfpmw()
{
    return "leN7`3>:b([rn)u_;gEEQT}E8W*syo_EGhEqZo0vJ5l";
}
function atwxzt0ejkjp9gmljxldam3mona()
{
    return "eByMoAd[lt<`z|J>Xi=wGXY3wk+V!#:~;Kk4MEhx!Aq";
}
function amqldxy06nnb1iy9e7lzne1agom()
{
    return "_d?9}6FNsiB=ppaU^RgMll-_yC0*i--8l1>2J?VtD*g";
}
function adobnsmahhlqaz2pdxwumqlhqzu()
{
    return "LtxycrSi4dBUJA6QQu4g8~E&BeH(MO^2dWsA4kES7tv";
}
function awkkzvastjd6lgueodxozepypas()
{
    return "8vxRL#~NL-H#fBK]40O2S}ZNf1@_kAWK#qXxW4=?s!n";
}

function curl_cek($uri, $post = NULL)
{
    $headers = array();
    $headers[] = "user-agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.124 Safari/537.36";
    $timeout = 2;
    $a = curl_init();
    curl_setopt($a, CURLOPT_URL, $uri);
    curl_setopt($a, CURLOPT_REFERER, "http://" . $_SERVER["SERVER_NAME"]);
    if ($post != "") {
        curl_setopt($a, CURLOPT_POST, true);
        curl_setopt($a, CURLOPT_POSTFIELDS, $post);
    }
    curl_setopt($a, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($a, CURLOPT_RETURNTRANSFER, true);
    @curl_setopt($a, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($a, CURLOPT_TIMEOUT, $timeout);
    $b = curl_exec($a);
    curl_close($a);
    return $b;
}

function dil($key)
{
    global $latx;
    preg_match("@" . $key . "={(.*?)}@si", $latx, $res);
    return $res[1];
}

function AccountLogOut()
{
    unset($_SESSION["acid"]);
    unset($_SESSION["acpw"]);
    setcookie("acid", "", time() - 1);
    setcookie("acpw", "", time() - 1);
    setcookie("acsecret", "", time() - 1);
}

function diff_day($start = "", $end = "")
{
    $dStart = new DateTime($start);
    $dEnd = new DateTime($end);
    $dDiff = $dStart->diff($dEnd);
    return $dDiff->days;
}

function crypt_chip($action, $string, $salt = "")
{
    if ($salt != "bHBlN3RxK0p3aUZhZWxyZmFpdHFlZGdZa1FiRUsyNkVreC9zWVVORTcwLzA2R3g0TlFqTURuNW1Oem1zdjBoZw==") {
        return false;
    }
    $key = "0|.%J.MF4AMT\$(.VU1J" . $salt . "O1SbFd\$|N83JG" . str_replace("www.", "", $_SERVER["SERVER_NAME"]) . ".~&/-_f?fge&";
    $output = false;
    $encrypt_method = "AES-256-CBC";
    if ($key === NULL) {
        $secret_key = "NULL";
    } else {
        $secret_key = $key;
    }
    $secret_iv = "p3aUZhZWxyZmFpdH";
    $key = hash("sha256", $secret_key);
    $iv = substr(hash("sha256", $secret_iv), 0, 16);
    if ($action === "encrypt") {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else {
        if ($action === "decrypt") {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
    }
    return $output;
}

function get_license_file_data($reload = false)
{
    global $temp_lfile;
    if ($reload || !$temp_lfile) {
        if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "LICENSE")) {
            return false;
        }
        $checkingFileData = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "LICENSE");
        if ($checkingFileData) {
            $checkingFileData = crypt_chip("decrypt", $checkingFileData, "bHBlN3RxK0p3aUZhZWxyZmFpdHFlZGdZa1FiRUsyNkVreC9zWVVORTcwLzA2R3g0TlFqTURuNW1Oem1zdjBoZw==");
        }
        if ($checkingFileData) {
            $temp_lfile = json_decode($checkingFileData, true);
            return $temp_lfile;
        }
        return false;
    }
    return $temp_lfile;
}

function license_run_check($licenseData = array())
{
    if ($licenseData && isset($licenseData["next-check-time"])) {
        $now_time = date("Y-m-d H:i:s");
        $next_time = date("Y-m-d H:i:s", strtotime($licenseData["next-check-time"]));
        $difference = diff_day($next_time, $now_time);
        if ($difference < 2) {
            $now_time = strtotime(date("Y-m-d H:i:s"));
            $next_time = strtotime($next_time);
            if ($now_time < $next_time) {
                return false;
            }
        }
    }
    return true;
}

function use_license_curl($address, &$error_msg)
{
    $s_l = strpos($address, "?");
    $data = substr($address, $s_l + 1);
    $address = substr($address, 0, $s_l);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $address);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $result = @curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        return false;
    }
    curl_close($ch);
    return $result;
}

function istatistik_fonksiyonu()
{
    global $fonk;
    global $gvn;
    global $db;
    $bugun = $fonk->this_date();
    $sql = $db->query("SELECT id FROM sayfa_ge_19541956 WHERE tarih < DATE_SUB(CURDATE(), INTERVAL 30 DAY) ");
    if ($sql && 0 < $sql->rowCount()) {
        $db->query("DELETE FROM sayfa_ge_19541956 WHERE tarih < DATE_SUB(CURDATE(), INTERVAL 30 DAY) ");
    }
    $sql = $db->query("SELECT id FROM ziyaret_ip_19541956 WHERE tarih < DATE_SUB(CURDATE(), INTERVAL 30 DAY) ");
    if ($sql && 0 < $sql->rowCount()) {
        $db->query("DELETE FROM ziyaret_ip_19541956 WHERE tarih < DATE_SUB(CURDATE(), INTERVAL 30 DAY) ");
    }
    $bgnzyrt = $db->query("SELECT id FROM sayfa_ge_19541956 WHERE tarih='" . $bugun . "' ");
    if ($bgnzyrt && 0 < $bgnzyrt->rowCount()) {
        $bgn = $bgnzyrt->fetch(PDO::FETCH_OBJ);
        $sql = $db->query("SELECT id FROM ziyaret_ip_19541956 WHERE tarih='" . $bugun . "' AND ip='" . $fonk->IpAdresi() . "' ");
        if ($sql && $sql->rowCount() < 1) {
            $tklvrm = ",tekil=tekil+1";
            $db->query("INSERT INTO ziyaret_ip_19541956 SET tarih='" . $bugun . "',ip='" . $fonk->IpAdresi() . "' ");
        }
        $db->query("UPDATE sayfa_ge_19541956 SET toplam=toplam+1" . $tklvrm . " WHERE id=" . $bgn->id);
    } else {
        $bgnzyrt = $db->prepare("INSERT INTO sayfa_ge_19541956 SET tarih=:bugun,toplam='1',tekil='1' ");
        $bgnzyrt->execute(array("bugun" => $bugun));
    }
}

?>