<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/contact/berichtvalidator.php";
	
	try
	{
	$validator = new berichtvalidator;
	header('Content-type: text/xml');
	echo $validator->ValidateField($_POST['fieldname'],$_POST['value'],0);
	}
	catch(CC2Exception $ex)
	{
		echo $ex->getExtendedMessage();
	}
?>