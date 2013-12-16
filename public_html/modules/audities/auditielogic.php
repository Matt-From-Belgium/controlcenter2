<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditiedata.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditiekandidaat.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/kandidaatvalidator.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/email/email.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataaccess/parameters.php';

function addKandidaat(auditieKandidaat $kandidaat)
{
	###Inhoud van het object wordt gevalideerd
	$validator = new kandidaatValidator();
	$errorlist = $validator->validateObject($kandidaat);
	

	
	if(count($errorlist)==0)
	{
		###Geen errors mail mag verstuurd worden en record toegevoegd aan de database
		$kandidaat=data_addKandidaat($kandidaat);
		
		###Bevestigingsmail naar kandidaat
		$bevestigingsmail = new Email();
		$bevestigingsmail->setTo($kandidaat->getMailadres());
		$bevestigingsmail->setSubject("CHANTage audities");
		$bevestigingsmail->setMessageAddin('/modules/audities/aanvraagmail.tpa');
		$bevestigingsmail->setVariable('voornaam',$kandidaat->getVoornaam());
		$bevestigingsmail->setVariable('key',$kandidaat->getKey());
		$bevestigingsmail->Send();
		
		###Bevestigingsmail naar administrator
		$administratormail = dataaccess_getParameter('AUDITIES_ADMIN_MAIL')->getValue();
		
		$adminmail = new Email();
		$adminmail->setTo($administratormail);
		$adminmail->setSubject("Auditiepakket aangevraagd");
		$adminmail->setMessageAddin('/modules/audities/adminmail.tpa');
		$adminmail->setVariable('naam',$kandidaat->getNaam());
		$adminmail->setVariable('voornaam',$kandidaat->getVoornaam());
		$adminmail->setVariable('mail',$kandidaat->getMailadres());
		$adminmail->setVariable('stemgroep',$kandidaat->getStemgroep());
		$adminmail->Send();
		
		
		return $kandidaat;
	}
	else
	{
		return $errorlist;
	}

}

function checkKey($key)
{
	###Deze functie controleert of de sleutel bestaat en geeft de kandidaatgegevens terug
	$kandidaat = data_getKandidaatByKey($key);
	
	if($kandidaat instanceOf auditieKandidaat)
	{
		return $kandidaat;
	}
	else
	{
		return false;
	}
}

function bevestigInschrijving(defAuditieKandidaat $kandidaat)
{
	### Eerst controleren of alles ingevuld is
	$validator = new defKandidaatValidator();
	
	$errorlist = $validator->validateObject($kandidaat);
	
	if(count($errorlist)>0)
	{
		###fouten, deze moeten teruggegeven worden
		return $errorlist;
	}
	else
	{
		###ok, we mogen bevestigen
		dataBevestigInschrijving($kandidaat);
		
		###Mail naar kandidaat
		$mailnaarkandidaat = new email();
		$mailnaarkandidaat->setTo($kandidaat->getMailadres());
		$mailnaarkandidaat->setSubject("Je bent ingeschreven voor de audities");
		$mailnaarkandidaat->setMessageAddin('/modules/audities/kandidaatuitgebreidemail.tpa');
		$mailnaarkandidaat->setVariable('voornaam',$kandidaat->getVoornaam());
		$mailnaarkandidaat->Send();
		
		###Mail naar admin
		$administratormail = dataaccess_getParameter('AUDITIES_ADMIN_MAIL')->getValue();
		$mailnaaradmin = new Email();
		$mailnaaradmin->setTo($administratormail);
		$mailnaaradmin->setSubject('Inschrijving voor auditie ontvangen');
		$mailnaaradmin->setMessageAddin('/modules/audities/uitgebreidadminmail.tpa');
		$mailnaaradmin->setVariable('naam',$kandidaat->getNaam());
		$mailnaaradmin->setVariable('voornaam',$kandidaat->getVoornaam());
		$mailnaaradmin->setVariable('mail',$kandidaat->getMailadres());
		$mailnaaradmin->setVariable('stemgroep',$kandidaat->getStemgroep());
		$mailnaaradmin->setVariable('adres',$kandidaat->getAdres());
		$mailnaaradmin->setVariable('telefoonnummer',$kandidaat->getGSM());
		$mailnaaradmin->setVariable('geboortedatum',$kandidaat->getGeboortedatum());
		$mailnaaradmin->setVariable('hoogstenoot',$kandidaat->getHoogstenoot());
		$mailnaaradmin->setVariable('laatstenoot',$kandidaat->getLaagstenoot());
		$mailnaaradmin->setVariable('partiturenlezen',$kandidaat->getPartiturenLezen());
		$mailnaaradmin->setVariable('ervaring',$kandidaat->getErvaring());
		$mailnaaradmin->setVariable('zangles',$kandidaat->getZangles());
		$mailnaaradmin->setVariable('instrument', $kandidaat->getInstrument());
		$mailnaaradmin->setVariable('ervaringinstrument',$kandidaat->getErvaringInstrument());
		$mailnaaradmin->setVariable('motivatie',$kandidaat->getMotivatie());
		$mailnaaradmin->Send();
	}
}

function inschrijvingBevestigd(auditieKandidaat $kandidaat)
{
	return dataBevestigd($kandidaat);
}

function getAuditiepakketStatus()
{
    ###Deze functie gaat via de parameters na of de auditiemodule geactiveerd is
    $auditiestatus = dataaccess_GetParameter('AUDITIES_AANVRAAG_ACTIEF');
    
    return $auditiestatus->getValue();
}


function getAuditieInschrijvingStatus()
{
    ###Deze functie gaat via de parameters na of de auditiemodule geactiveerd is
    $auditiestatus = dataaccess_GetParameter('AUDITIES_INSCHRIJVING_ACTIEF');
    
    return $auditiestatus->getValue();
}

function getAuditieMasterSwitchStatus()
{
    ###Deze functie haalt de parameter AUDITIES_MASTER_SWITCH op, wanneer deze op 0 staat kunnen zelfs ingeschreven kandidaten de pagina niet raadplegen
    $masterswitch = dataaccess_GetParameter("AUDITIES_MASTER_SWITCH");
    return $masterswitch->getValue();
}

/*
###DEBUG
$auditiekandidaat = new auditieKandidaat("Bauw","Matthias","matthias.bauw@gmail.com",4);

try
 {
print_r(addKandidaat($auditiekandidaat));
 }
 catch(CC2Exception $ex)
 {
	 echo $ex->getExtendedMessage();
 }
*/
/*
###DEBUG bevestigInschrijving
$auditiekandidaat = checkKey("2a5781dfd8068670921800c8e0b55b5c");

$definitievekandidaat = new defAuditieKandidaat($auditiekandidaat);


$definitievekandidaat->setAdres('Lange Veldstraat 10 \n 8600 DIksmuide');
$definitievekandidaat->setGSM('0472377267');
$definitievekandidaat->setGeboortedatum(1984,10,18);
$definitievekandidaat->setHoogstenoot('geen idee');
$definitievekandidaat->setLaagstenoot('geen idee');
$definitievekandidaat->setpartiturenLezen('Y');
$definitievekandidaat->setervaring('musicals en dergelijke');
$definitievekandidaat->setzangles('Y');
$definitievekandidaat->setinstrument('Ja, gitaar');
$definitievekandidaat->setErvaringInstrument('weinig');
$definitievekandidaat->setMotivatie('tja ik wil wel maar kan ik?');


print_r($definitievekandidaat);
bevestigInschrijving($definitievekandidaat);
*/
?>