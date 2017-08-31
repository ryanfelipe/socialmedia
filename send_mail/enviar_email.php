<?php
	require 'PHPMailer/PHPMailerAutoload.php';
	
	$email = ($_POST['email']);
	$Mailer = new PHPMailer();
	$Mailer->IsSMTP();
	$Mailer->isHTML(true);
	$Mailer->Charset = 'UTF-8';
	$Mailer->SMTPAuth = true;	
	$Mailer->Host = 'smtp.kinghost.net';
	$Mailer->Port = 587;
	$Mailer->Username = 'capitalfuturo@capitalfuturo.club';
	$Mailer->Password = 'victor19871987';
	$Mailer->From = 'capitalfuturo@capitalfuturo.club';
	$Mailer->FromName = 'CapitalFuturo';
	$Mailer->Subject = 'Titulo - Recuperar Senha';
	$Mailer->Body = 'Recuperação de senha efetivada';
	$Mailer->AltBody = 'Recuperação de senha efetivada';
	$Mailer->AddAddress($email);
	
	if($Mailer->Send()){
		echo "Senha alterada com sucesso!";
	}else{
		echo "Erro no envio do e-mail de confirmação: " . $Mailer->ErrorInfo;
	}
	
?>