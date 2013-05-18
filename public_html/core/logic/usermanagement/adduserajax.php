<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/uservalidator.php";
	
	$validator = new UserValidator;
	header('Content-type: text/xml');
	echo $validator->ValidateField($_POST['fieldname'],$_POST['value'],$_POST['id']);
	
	
?>