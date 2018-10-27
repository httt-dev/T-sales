<?php



class PHPMailer
{
    public function __construct()
    {
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function sendmail()
    {
        include('PHPMailer/class.smtp.php'); 
         include "PHPMailer/class.phpmailer.php"; 
        
        
        
        $mail             = new PHPMailer();
        $mail->IsSMTP(); 
        echo "a"; die;
        $objMail = new PHPMailer\PHPMailer\PHPMailer();
        return $objMail;
    }
}

?>