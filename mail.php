<?php
    function SendMail($email,$subject,$body,$purpose){
        //Import PHPMailer classes into the global namespace
        //These must be at the top of your script, not inside a function
        require "PHPMailer/src/PHPMailer.php";
        require "PHPMailer/src/SMTP.php";
        require "PHPMailer/src/Exception.php";

        //Load Composer's autoloader

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->CharSet    = "utf-8";                                         //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'tranhoangdang.test@gmail.com';                     //SMTP username
            $mail->Password   = 'jmpntgtgefigstyp';                               //SMTP password
            $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('tranhoangdang.test@gmail.com', 'Tin tức PHP');
            /* $mail->addAddress('joe@example.net', 'Joe User'); */     //Add a recipient
            $mail->addAddress($email);               //Name is optional
            /* $mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com'); */

            //Attachments
            /* $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');   */  //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            /* $mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; */

            $mail->smtpConnect( array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_nam" => false,
                    "allow_self_signed" => true
                )
            ));

            $mail->send();
            if($purpose == 1){
                set_flash_session('mess_flash','Chúng tôi đã gửi đến địa chỉ email: ' . $email . ' một đường dẫn để kích hoạt tài khoản của bạn. Xin vui lòng hãy kiểm tra email để được hướng dẫn');
                redirect('register.php');
            }
            else if($purpose == 2){
                set_flash_session('mess_flash','Chúng tôi đã gửi đến địa chỉ email: ' . $email . ' một đường dẫn để khôi phục mật khẩu của bạn. Xin vui lòng hãy kiểm tra email để được hướng dẫn.');
                redirect('forget-password.php');
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
?>