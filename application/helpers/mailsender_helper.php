<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendemail($email_tujuan, $pesan = null, $subject = null, $nama_pengirim = 'Keuangan BQN', $html = false, $email_pengirim = 'kamscode@kamscodelab.tech', $param = [])
{
    require_once get_path('third', 'phpmailer/src/Exception.php');
    require_once get_path('third', 'phpmailer/src/PHPMailer.php');
    require_once get_path('third', 'phpmailer/src/SMTP.php');

    $mail = new PHPMailer(TRUE);

    try {
        /* Set the mail sender. */
        $mail->IsSMTP();
        $mail->Host = "mail.kamscodelab.tech";
        $mail->isHTML($html);
        // optional
        // used only when SMTP requires authentication  
        $mail->SMTPAuth = true;
        $mail->Username = 'dev.kamscode@kamscodelab.tech';
        $mail->Password = 'kambing15';
        $email_tujuan = strtolower($email_tujuan);
        $mail->setFrom($email_pengirim, $nama_pengirim);

        /* Add a recipient. */
        $mail->addAddress($email_tujuan);

        /* Set the subject. */
        $mail->Subject = $subject;

        /* Set the mail message body. */
        if($html){
            ob_start();
            include_view($pesan, $param);
            $pesan = ob_get_clean();
        }
        $mail->Body = $pesan;
        // $mail->SMTPDebug = 3;

        $mail->send();
        return ['message' => 'Berhasil mengirim email', 'sts' => true];
    } catch (Exception $e) {
        return ['message' => $e->errorMessage(), 'sts' => false];
        echo $e->errorMessage();
    } catch (\Exception $e) {
        return ['message' => $e->getMessage(), 'sts' => false];
    }
}
