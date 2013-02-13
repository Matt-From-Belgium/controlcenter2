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

function addNieuwsbrief($bestand,nieuwsbrief $nieuwsbrief,array $abonnementen)
{
	#echo $bestand['nieuwsbriefbestand']['tmp_name'];
    
	###Het object moet gecontroleerd worden
	$validator = new nieuwsbriefvalidator();
	$errorlist = $validator->validateObject($nieuwsbrief);
        
        ###Het bestand moet gecontroleerd worden
        $filevalidator = new fileValidator();
        $filevalidator->setExtension('pdf');
        $filevalidator->setMaxSize(10);
        
        ###Er moet nagekeken worden of er wel abonnementen geselecteerd zijn
        $abonnementerror = array();
        if(count($abonnementen)==0)
        {
            $abonnementerror['message'] = "Er moet minstens één abonnement geselecteerd worden";
        }
        
        $fileerrors=$filevalidator->validateFile($bestand['nieuwsbriefbestand']);
        
        if(!is_array($fileerrors))
        {
            $fileerrors=array();
        }
        
        $errorlistmerged=array_merge($errorlist,$fileerrors,$abonnementerror);
	
	if(count($errorlistmerged)==0)
	{
	###Alles in orde, mag toegevoegd worden
	$nieuwsbriefid=data_addNieuwsbrief($nieuwsbrief,$abonnementen);
        move_uploaded_file($bestand['nieuwsbriefbestand']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/nieuwsbrieven/'.$nieuwsbriefid.'.pdf');
      
	}
	else
	{
	###fouten teruggeven aan de presentation layer
	return $errorlistmerged;
	}	
}

function getNieuwsbrieven($abonnement)
{
    $result= data_getnieuwsbrievenVoorAbonnement($abonnement);
    return $result;
}

 function getAbonnementbyKey($id)
 {
      $lijst=data_getAbonnementenlijst();
      if(isset($lijst[$id]))
      {
          return $lijst[$id];
      }
      else
      {
          return false;
      }
 }
 
 function deleteAbonnee(abonnee $abonnee)
 {
     ###Abonnee moet verwijderd worden
     data_deleteAbonnee($abonnee);
 }
 
 /*
 ###DEBUG
 $tezoeken = 2;
 $result=  data_getAbonnementbyKey(intval($tezoeken));
 
 if($result)
 {
     print_r($result);
 }
 
  * 
  */
?>