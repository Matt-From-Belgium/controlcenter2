<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/abonneevalidator.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/nieuwsbriefvalidator.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/data/datafuncties.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/email/email.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/filevalidator.php';

function abonneeToevoegen(abonnee $abonnee)
{
	$validator = new abonneeValidator();
	$errormessages = $validator->validateObject($abonnee);
	if(count($errormessages)==0)
	{
		echo "alles ok";
		
		###We voegen de abonnee toe aan de databank
		$nieuweabonnee = data_createAbonnee($abonnee);

		
		###We versturen de bevestigingsmail
		$bevestigingsmail = new email("mail");
		$bevestigingsmail->setSubject("Welkom bij jestaatnietalleen.be");
		$bevestigingsmail->setTo($abonnee->getMailadres());
		$bevestigingsmail->setMessageAddin("/modules/nieuwsbrief/addins/bevestigingsmail.tpa");
		$bevestigingsmail->setVariable("key",$nieuweabonnee->getKey());

		
		$bevestigingsmail->Send();
		
		return $nieuweabonnee;		
	}
	else
	{
		return $errormessages;
	}
}

function getAbonneeByKey($key)
{
	return data_getAbonneeByKey($key);
}

function getAbonnementenLijst()
{
	return data_getAbonnementenlijst();
}

function editAbonnee(abonnee $abonnee)
{
	data_editAbonnee($abonnee);
}

function confirmAbonnee(abonnee $abonnee)
{
	data_confirmAbonnee($abonnee);
	
	$bevestigingsmail = new email("mail");
	$bevestigingsmail->setSubject("Aanmelding voltooid!");
	$bevestigingsmail->setTo($abonnee->getMailadres());
	$bevestigingsmail->setMessageAddin("/modules/nieuwsbrief/addins/voltooidmail.tpa");
	$bevestigingsmail->Send();
}

function addNieuwsbrief($bestand,nieuwsbrief $nieuwsbrief)
{
	#echo $bestand['nieuwsbriefbestand']['tmp_name'];
    
	###Het object moet gecontroleerd worden
	$validator = new nieuwsbriefvalidator();
	$errorlist = $validator->validateObject($nieuwsbrief);
        
        $filevalidator = new fileValidator();
        $filevalidator->setExtension('docx');
        $filevalidator->setMaxSize(10);
        
        $fileerrors=$filevalidator->validateFile($bestand['nieuwsbriefbestand']);
        
        $errorlistmerged=array_merge($errorlist,$fileerrors);
	
	if(count($errorlistmerged)==0)
	{
	###Alles in orde, mag toegevoegd worden
	$nieuwsbriefid=data_addNieuwsbrief($nieuwsbrief);
        move_uploaded_file($bestand['nieuwsbriefbestand']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/file/nieuwsbrieven/'.$nieuwsbriefid.'.pdf');
      
	}
	else
	{
	###fouten teruggeven aan de presentation layer
	return $errorlistmerged;
	}	
}

?>