<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer
$container['mail'] = function ($container) {
    $settings = $container->get('settings')['settings']['System']['Mail'];

    // https://github.com/PHPMailer/PHPMailer
    $mail = new PHPMailer;

    $mail->Host       = $settings['host'];
    $mail->SMTPAuth   = $settings['auth'];     // I set false for localhost
    $mail->SMTPSecure = $settings['secure'];   // set blank for localhost
    $mail->Port       = $settings['port'];     // 25 for local host
    $mail->Username   = $settings['username']; // I set sender email in my mailer call
    $mail->Password   = $settings['password'];
    $mail->CharSet    = 'UTF-8';

    // ne legyen X-Mailer header
    // (ha ures akkor sajatot hasznal, ha whitespace akkor semmit)
    $mail->XMailer = ' ';

    $mail->isHTML(true);

    $mail->From       = $settings['fromEmail'];
    $mail->FromName   = $settings['fromName'];

    if ($settings['errorsToEmail'])
    {
        $mail->Sender = $settings['errorsToEmail'];
        $mail->addCustomHeader('Errors-To', $settings['errorsToEmail'] );
        $mail->addCustomHeader('Return-Path', $settings['errorsToEmail'] );
    }

    $mail->SMTPKeepAlive = true;
    $mail->SMTPAutoTLS = true;

    $mail->SMTPOptions = array(
      'ssl' => array(
        'verify_peer'      => false,
        'verify_peer_name' => false,
      ),
    );

    return $mail;
};
