use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';
$mail = new PHPMailer(true);

$mail ->SMTPDebug = 2;
$mail ->isSMTP();
$mail ->Host = 'smtp.gmail.com';
$mail ->SMTPAuth = true;
$mail ->Username = 'user@example.com';
$mail ->Password = 'secret';
$mail ->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail ->Port = 465;

if (isset($_POST['kirim'])) {
    $mail->setFrom('tutormubatekno@gmail.com', 'Tutorial Muba Tekno');
    $mail->addAddress($_POST['email_penerima']);
}

 if (isset($_POST['kirim'])) {
     if (create_barang($_POST) > 0) {
         echo "<script>
                     alert('Email Berhasil Dikirim');
                     document.location.href = 'index.php'; 
                   </script>";
     } else {
         echo "<script>
                     alert('Email Gagal Dikirim');
                     document.location.href = 'index.php';
                   </script>";
     }
 }