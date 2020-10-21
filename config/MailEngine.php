<?php

namespace Lib;

require_once "./vendor/PHPMailer-master/src/PHPMailer.php";
require_once "./config/ConfigPhpMailer.php";

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Config\Configuration;

class MailEngine{

    public static function CreateMail(){
        $mail = new PHPMailer(true);


        //Config
        $mail -> SMTPDebug = Configuration::smtpConfig['SMTPDebug'];
        if (Configuration::smtpConfig['isSMTP']) $mail ->isSMTP();
        $mail -> Host = Configuration::smtpConfig['Host'];
        $mail -> SMTPAuth = Configuration::smtpConfig['SMTPAuth'];
        $mail -> Username = Configuration::smtpConfig['Username'];
        $mail -> Password = Configuration::smtpConfig['Password'];
        $mail -> SMTPSecure = Configuration::smtpConfig['SMTPSecure'];
        $mail -> Port = Configuration::smtpConfig['Port'];
        $mail -> CharSet = 'UTF-8';

        //copie administrateur
        $mail -> setFrom(Configuration::smtpConfig['From']['Address'],Configuration::smtpConfig['From']['Name']);
        return $mail;
    }
    public static function send(string $subject, string $from, string $to, string $message){
        $mail = MailEngine :: CreateMail();
        //Configuration::smtpConfig['Administrator']['Address'],Configuration::smtpConfig['Administrator']['Name']
        $mail->addAddress($to);
        $mail->addReplyTo($from);
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = "<p>". htmlspecialchars($message)."</p>" ;
        $mail->AltBody = strip_tags($message);
        try {
            $mail -> send();
        }
        catch (Exception $e){
            throw new Exception($e->getMessage());
        }


    }
    public static function sendConfirmation( $subject, $expediteur){
        $mail = MailEngine :: CreateMail();
        $mail->addAddress($expediteur);
        $mail->addReplyTo(Configuration::smtpConfig['From']['Address']);
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $message = '<p>Votre question concernant: ' .$subject .' a bien été prise en compte par nos services.</p>' ;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);
        try {
            $mail -> send();
        }
        catch (Exception $e){
            throw new Exception($e->getMessage());
        }

    }
}
