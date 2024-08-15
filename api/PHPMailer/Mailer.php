<?php

// Include required PHPMailer files
include_once 'includes/PHPMailer.php';
include_once 'includes/SMTP.php';
include_once 'includes/Exception.php';

// Define namespaces
use PHPMailer\PHPMailer\PHPMailer;

class NewsletterSender {
    private $mail;
    private $db;

    private $username;

    public function __construct($username) {
        $this->username = $username;
        $this->mail = new PHPMailer();
        $this->configureSMTP();
    }

    private function configureSMTP() {
        $this->mail->isSMTP();
        $this->mail->Host = "smtp.gmail.com";
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = "tls";
        $this->mail->Port = "587";
        $this->mail->Username = "eatnowesgi@gmail.com";
        $this->mail->Password = "xnnyrtxavcmospvk";
        $this->mail->isHTML(true);
        $this->mail->setFrom('eatnowesgi@gmail.com', $this->username);
    }

    public function sendNewsletter($subject, $htmlString, $emailArray) {
        // Set email subject and body
        $this->mail->Subject = $subject;
        $this->mail->Body = $htmlString;

        if($emailArray == null){
            exit_with_message("Email can't be null");
        }

        // Fetch recipients from the database
        //$recipients = $this->getRecipients();
        $recipients = $emailArray;

        $send = [];
        $send["max"] = count($recipients);
        $failed = [];

        foreach ($recipients as $index => $recipient) {
            $this->mail->addAddress($recipients[$index]);

            if ($this->mail->send()) {
                $send["sent"] += 1;
            } else {
                array_push($failed, $recipients[$index]);
            }

            // Clear addresses for the next iteration
            $this->mail->clearAddresses();
        }

        // Close SMTP connection
        $this->mail->smtpClose();

        return ["Failed to send" => $failed, "Mail Sent" => $send];
    }

    private function getRecipients() {
        return [""];
    }
}

/*
 * Exemple :
 * $newsletterSender = new NewsletterSender("Testing PA");
 * $newsletterSender->sendNewsletter("Manger des trucs", "J'aime bien les pâtes");
 */

?>
