<?php 
require(__DIR__."/../components/mailer/PHPMailerAutoload.php");
$addr = "toulouse.acoustics@gmail.com";
$addr = "jules.buret@gmail.com";
if (!isset($_POST['mail']) || !isset($_POST['subject']) || !isset($_POST['txt']) 
	|| empty($_POST['mail']) || empty($_POST['subject']) || empty($_POST['txt']) ) {
        	$data =  'Informations manquantes.';
}else{
	if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
        		$data =  'mail non valide.';
	}else{
		$mail = new PHPMailer();
		$mail->From = $_POST["mail"];
		$mail->FromName = $_POST["mail"];
		$mail->addAddress($addr);
		$mail->Subject = $_POST['subject'];
		$mail->Body = "\r\n\r\nRef: " . $_POST['txt'];
		if (!$mail->send()) {
		        $data = "Probleme lors de l'envoie : ' Mailer Error: " . $mail->ErrorInfo;
		}else{
		        $data = "true";
		}
	}
}

echo $data;

?>