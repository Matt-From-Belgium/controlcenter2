<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/uservalidator.php";
	
	try
	{
	$validator = new UserValidator;
	header('Content-type: text/xml');
	echo $validator->ValidateField($_POST['fieldname'],$_POST['value'],$_POST['id']);
	}
	catch(CC2Exception $ex)
	{
		echo $ex->getExtendedMessage();
	}
?>