<?php

function myPostIsset($key) {

    if (!isset($_POST[$key]) || empty($_POST[$key]))
        return false;

    return true;
}

function translatePersian($str) {

    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

    $num = range(0, 9);
    $convertedPersianNums = str_replace($persian, $num, $str);
    $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

    return $englishNumbersOnly;
}

function MiladyToShamsi($time, $date = ""){

    include_once 'jdate.php';

    if(empty($date)) {
        $date = $time->format('Y-m-d');
        $date = explode('-', $date);
    }
    return gregorian_to_jalali($date[0],$date[1],$date[2],'-');
}

function generateActivationCode() {
    return rand(10000, 99999);
}

function sendSMS($destNum, $text, $templateId, $text2 = "", $text3 = "") {

    if($destNum[0] == "0" && $destNum[1] == "9") {

        require __DIR__ . '/../../../vendor/autoload.php';

        try {
            $api = new \Kavenegar\KavenegarApi("4B6A58494B6A5A6C6D68426D34777059397239587746754A5A394577596D5046684F6F63684934652F45343D");
            $result = $api->VerifyLookup($destNum, $text, $text2, $text3, $templateId);

            if ($result) {
                foreach ($result as $r) {
                    return $r->messageid;
                }
            }
        } catch (\Kavenegar\Exceptions\ApiException $e) {
            return -1;
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            return -1;
        }
        return -1;
    }

    return -1;
}

function makeValidInput($input) {
//    $input = addslashes($input);
//    $input = trim($input);
//    if(get_magic_quotes_gpc())
//        $input = stripslashes($input);
//    $input = htmlspecialchars($input);
    return $input;
}

function uploadCheck($target_file, $name, $section, $limitSize, $ext) {
    $err = "";
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

//    $check = getimagesize($_FILES[$name]["tmp_name"]);
    $uploadOk = 1;

//    if($check === false) {
//        $err .= "فایل ارسالی در قسمت " . $section . " معتبر نمی باشد" .  "<br />";
//        $uploadOk = 0;
//    }


    if ($uploadOk == 1 && $_FILES[$name]["size"] > $limitSize) {
        $limitSize /= 1000000;
        $limitSize .= "MB";
        $err .=  "حداکثر حجم مجاز برای بارگذاری تصویر " .  " <span>" . $limitSize . " </span>" . "می باشد" . "<br />";
    }

    $imageFileType = strtolower($imageFileType);

    if($ext != -1 && $imageFileType != $ext)
        $err .= "شما تنها فایل های $ext. را می توانید در این قسمت آپلود نمایید" . "<br />";
    return $err;
}

function upload($target_file, $name, $section) {

    try {
        move_uploaded_file($_FILES[$name]["tmp_name"], $target_file);
    }
    catch (Exception $x) {
        return "اشکالی در آپلود تصویر در قسمت " . $section . " به وجود آمده است" . "<br />";
    }
    return "";
}

function convertStringToDate($date) {
    return $date[0] . $date[1] . $date[2] . $date[3] . '/' . $date[4] . $date[5] . '/' . $date[6] . $date[7];
}

function convertDateToString($date) {
    $subStrD = explode('/', $date);
    return $subStrD[0] . $subStrD[1] . $subStrD[2];
}

function _custom_check_national_code($code) {

    if(!preg_match('/^[0-9]{10}$/',$code))
        return false;

    for($i=0;$i<10;$i++)
        if(preg_match('/^'.$i.'{10}$/',$code))
            return false;
    for($i=0,$sum=0;$i<9;$i++)
        $sum+=((10-$i)*intval(substr($code, $i,1)));
    $ret=$sum%11;
    $parity=intval(substr($code, 9,1));
    if(($ret<2 && $ret==$parity) || ($ret>=2 && $ret==11-$parity))
        return true;
    return false;
}