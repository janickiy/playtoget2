<?php

session_start();

include "libraries/SimpleCaptcha.class.php";

$captcha = new SimpleCaptcha();

// OPTIONAL Change configuration...
$captcha->wordsFile = 'libraries/captcha/words/en.php';
//$captcha->session_var = 'secretword';
//$captcha->imageFormat = 'png';
//$captcha->lineWidth = 3;
//$captcha->scale = 3; $captcha->blur = true;
$captcha->resourcesPath = "libraries/captcha";

// OPTIONAL Simple autodetect language example
/*
if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $langs = array('en', 'es');
    $lang  = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    if (in_array($lang, $langs)) {
        $captcha->wordsFile = "words/$lang.php";
    }
}
*/

// Image generation
$captcha->CreateImage();