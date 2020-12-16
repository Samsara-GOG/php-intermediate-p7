<?php

use Symfony\Component\Yaml\Yaml;

require_once __DIR__. '/../vendor/autoload.php';

$config = Yaml::parseFile(__DIR__.'/../config/config.yaml');

// Create the Transport
// $transport = (new Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
$transport = (new Swift_SmtpTransport($config['smtp']['server'], $config['smtp']['port'], $config['smtp']['protocol']))
    ->setUsername($config['smtp']['login'])
    ->setPassword($config['smtp']['password'])
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Create a message
$counter = 1;
$message = (new Swift_Message('Hello swiftmailer'))
    ->setFrom(['osiris_ks@yahoo.fr' => 'Jérôme Demuynck'])
    ->setTo(['samsaraonaya@gmail.com', 'tonyf62@gmail.com' => 'Samsara'])
    ->setBody("Message de test {$counter}")
  ;

// Send the message
$result = $mailer->send($message);
