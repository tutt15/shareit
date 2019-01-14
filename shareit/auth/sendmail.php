<?php 
    require_once $_SERVER['DOCUMENT_ROOT'].'/library/phpmailer/PHPMailer.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/library/phpmailer/Exception.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/library/phpmailer/OAuth.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/library/phpmailer/POP3.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/library/phpmailer/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
?>
<?php
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'sharitthanhbui@gmail.com';                 // SMTP username
        $mail->Password = 'Vanthanh2727';                           // SMTP password
        // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        // $mail->Port = 587;                                    // TCP port to connect to
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to
     
        //Recipients
        $mail->CharSet = "UTF-8";
        $mail->setFrom('sharitthanhbui@gmail.com', 'Bui Van Thanh');
        $mail->addAddress('thanhbui2727@gmail.com');     // Add a recipient
        // $mail->addAddress('buivanthanh722@gmail.com');               // Name is optional
        // $mail->addReplyTo('thanhbui2727@gmail.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
     
        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
     
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
     
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
?>