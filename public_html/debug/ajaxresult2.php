<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/ajaxresponse.php';

	function echoresult()
	{
		$response = new ajaxResponse('ok');
		$response->addField('testveld');
		$antwoord['testveld']="hallo daar ik ben testveld";
		
		$response->addData($antwoord);
		echo $response->getXML();
	}
?>