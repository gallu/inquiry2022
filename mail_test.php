<?php  // mail_test.php

require_once __DIR__ . '/vendor/autoload.php';

// Create the Transport
$transport = new Swift_SmtpTransport('localhost', 25);
// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Create a message
$message = (new Swift_Message('Wonderful Subject'))
  ->setTo('XXXXXX@example.com')
  ->setBody('Here is the message itself')
  ;

// Send the message
$result = $mailer->send($message);
var_dump($result);
