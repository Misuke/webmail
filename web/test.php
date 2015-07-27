<?php
require_once __DIR__ .'/../vendor/autoload.php';

use Kazan\Mailer\Mailer;
use Kazan\Vocalizer\Vocalizer;

$mailer    = new Mailer();
$vocalizer = new Vocalizer();

$mailer->getUnseenMessages();
var_dump($mailer->getMessage(5891));

// $messages = json_decode($mailer->getUnseenMessages());
// if (count($messages) > 0) {
//     $vocalizer->synthesizeText('en', $messages[0]->subject);
// }

var_dump($messages);
