<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/abonnement.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/abonnee.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/data/datafuncties.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/abonneevalidator.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/nieuwsbrief.php';
	
	$abonnementen = data_getAbonnementenLijst();
	

	$nieuwsbrief = new nieuwsbrief(-1,9,2012,$abonnementen,'');
	
	print_r($nieuwsbrief);
	
	try
	{
	data_addNieuwsbrief($nieuwsbrief);
	}
	catch(CC2Exception $ex)
	{
		echo $ex->getExtendedMessage();
	}
?>