<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/email/email.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
	
	$mail = new Email();
	$mail->setTo("webmaster@detoverlantaarn.be");
	$mail->setSubject("Welcome to guitarbrain.com");
	
	
	$mail->setMessageAddin("/debug/test.tpa");
	$mail->setVariable("test","hello");
	$mail->Send();
?>