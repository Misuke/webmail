<?php
require_once __DIR__ .'/../vendor/autoload.php';

use Kazan\Mailer\Mailer;
use Kazan\Vocalizer\Vocalizer;

if (isset($_GET['module'])) {
    if ($_GET['module'] == 'mailer') {
        handlerMailer();
    } elseif ($_GET['module'] == 'vocalizer') {
        handlerVocalizer($_GET['tts'], $_GET['lang']);
    } 
}

function handlerMailer() {
    $mailer = new Mailer();
    echo $mailer->getUnseenMessages();
}

function handlerVocalizer($tts, $lang = 'en') {
    $vocalizer = new Vocalizer();
    echo json_encode(array("filename" => $vocalizer->synthesizeText($lang, $tts)));
}
