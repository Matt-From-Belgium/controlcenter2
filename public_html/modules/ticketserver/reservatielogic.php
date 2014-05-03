<?php
require_once $_SERVER['DOCUMENT_ROOT']."/modules/ticketserver/reservatievalidator.php";
require_once $_SERVER['DOCUMENT_ROOT']."/modules/ticketserver/reservatieclass.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/email/email.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/parameters.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatiedata.php';


function ValidateReservatie($postarray)
{
	$reservatievalidator = new reservatievalidator();
	$reservatie = new reservatie();
	
	###object aanpaken met alle gegevens ivm de reservatie
	$reservatie->setNaam($postarray['naam']);
	$reservatie->setVoornaam($postarray['voornaam']);
	$reservatie->setMailadres($postarray['email']);
	$reservatie->setAantalTickets($postarray['aantal']);	
	$reservatie->setVoorstelling($postarray['voorstelling']);
	$reservatie->setReferral($postarray['referral']);
	$reservatie->setOpmerkingen($postarray['opmerkingen']);
	$reservatie->setStraat($postarray['straat']);
	$reservatie->setHuisNr($postarray['nummer']);
	$reservatie->setGemeente($postarray['gemeente']);
	
	###Nu moet het object gevalideerd worden
	$errormessages = $reservatievalidator->validateObject($reservatie);
	
	###Als de validatie geslaagd is is $errormessages leeg
	if(!is_array($errormessages))
	{
		###Totale prijs berekenen
		$totaal = $reservatie->getAantalTickets() * getEventPrice();

		###De functie ReservatieToevoegen schrijft de gegevens naar de databank en geeft het id terug.
		require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatiedata.php';
		
		$reservatieid=Reservatietoevoegen($reservatie);
		
		
		###Voorstellingstekst ophalen
		$voorstellingstekst = getVoorstellingstekst($postarray['voorstelling']);

		###Er moet een mail gestuurd worden naar het secretariaat
		$secmail = getAdminmailadres();
		
		$mailnaarsec = new Email("mail");
		$mailnaarsec->setTo($secmail);
		$mailnaarsec->setFrom($reservatie->getMailadres());
		$mailnaarsec->setSubject("Nieuwe reservatie ontvangen");
		$mailnaarsec->setVariable("reservatienummer",$reservatieid);
		$mailnaarsec->setVariable("naam",$postarray['naam']);
		$mailnaarsec->setVariable("voornaam",$reservatie->getVoornaam());
		$mailnaarsec->setVariable("email",$reservatie->getMailadres());
		$mailnaarsec->setVariable("aantal",$postarray['aantal']);
		$mailnaarsec->setVariable("voorstelling",$voorstellingstekst);
		$mailnaarsec->setVariable("opmerkingen",$reservatie->getOpmerkingen());
		$mailnaarsec->setMessageAddin("/modules/ticketserver/mailaddins/secretariaat.tpa");
		$mailnaarsec->Send();
		
		###Er moet een mail gestuurd worden naar de gebruiker
		$mailnaargebruiker = new Email("mail");
                $mailnaargebruiker->setSubject("Uw reservatie");
		$mailnaargebruiker->setTo($postarray['email']);
		$mailnaargebruiker->setFrom($secmail);
		$mailnaargebruiker->setVariable("title","Reservatiebevestiging");
		$mailnaargebruiker->setVariable("familienaam",$postarray['naam']);
		$mailnaargebruiker->setVariable("voornaam",$reservatie->getVoornaam());
		$mailnaargebruiker->setVariable("datum",$voorstellingstekst);
		$mailnaargebruiker->setVariable("aantal",$postarray['aantal']);
		$mailnaargebruiker->setVariable("totaal",$totaal);
		$mailnaargebruiker->setVariable("reservatienummer",$reservatieid);
		$mailnaargebruiker->setVariable('eventname', getEventName());
                $mailnaargebruiker->setVariable('eventlocation', getZaalAdres());
                $mailnaargebruiker->setVariable('bankaccount', getBankAccount());
                
		
		###Aantal dagen voor herinneringophalen
		$herinnering = getDagenVoorHerinnering();
		$mailnaargebruiker->setVariable('herinneringstermijn',$herinnering);
		
		$mailnaargebruiker->setMessageAddin("/modules/ticketserver/mailaddins/bevestiging.tpa");
		$mailnaargebruiker->Send();
	}
	
	return $errormessages;
}

function openReservatie($reservatienummer)
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatiedata.php';
	return data_openReservatie($reservatienummer);
}

function reservatieBetaald($reservatienummer)
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatiedata.php';
	data_reservatieBetaald($reservatienummer);
	
	###Er moet een mail verstuurd worden naar degene die besteld heeft
	$mailnaargebruiker = new Email('mail');
	
	$reservatie = openReservatie($reservatienummer);
	$voorstellingstekst = getVoorstellingstekst($reservatie->getVoorstelling());
	$secmail = getAdminmailadres();
        
        $mailnaargebruiker->setMessageAddin("/modules/ticketserver/mailaddins/betalingontvangen.tpa");
	$mailnaargebruiker->setTo($reservatie->getMailadres());
	$mailnaargebruiker->setFrom($secmail);
	$mailnaargebruiker->setSubject('Uw reservatie - bevestiging van uw betaling');
	
        $mailnaargebruiker->setVariable("title","Reservatiebevestiging");
	$mailnaargebruiker->setVariable("familienaam",$reservatie->getNaam());
	$mailnaargebruiker->setVariable("voornaam",$reservatie->getVoornaam());
	$mailnaargebruiker->setVariable("datum",$voorstellingstekst);
	$mailnaargebruiker->setVariable("aantal",$reservatie->getAantalTickets());
	$mailnaargebruiker->setVariable("reservatienummer",$reservatie->getId());
	$mailnaargebruiker->setVariable('eventname',  getEventName());
        $mailnaargebruiker->setVariable('eventlocation',  getZaalAdres());
        
	$mailnaargebruiker->Send();	
}

function reservatieAnnuleren($reservatienummer)
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatiedata.php';
	
	data_reservatieAnnuleren($reservatienummer);
	
	$mailnaargebruiker = new Email('mail');
	
	$reservatie = openReservatie($reservatienummer);
	$voorstellingstekst = getVoorstellingstekst($reservatie->getVoorstelling());
	$secmail = getAdminmailadres();

	$mailnaargebruiker->setTo($reservatie->getMailadres());
	$mailnaargebruiker->setFrom($secmail);
	$mailnaargebruiker->setSubject('Uw reservatie - ANNULATIE');
	$mailnaargebruiker->setFrom($secmail);
	$mailnaargebruiker->setVariable("title","Reservatiebevestiging");
	$mailnaargebruiker->setVariable("familienaam",$reservatie->getNaam());
	$mailnaargebruiker->setVariable("voornaam",$reservatie->getVoornaam());
	$mailnaargebruiker->setVariable("datum",$voorstellingstekst);
	$mailnaargebruiker->setVariable("aantal",$reservatie->getAantalTickets());
	$mailnaargebruiker->setVariable("reservatienummer",$reservatie->getId());
        $mailnaargebruiker->setVariable("eventname",  getEventName());
        $mailnaargebruiker->setVariable('adminmail', getAdminmailadres());
        
	$mailnaargebruiker->setMessageAddin("/modules/ticketserver/mailaddins/annulatie.tpa");
	$mailnaargebruiker->Send();	
}

function reservatieHeractiveren($reservatienummer)
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatiedata.php';
	data_reservatieHeractiveren($reservatienummer);
}

function getVoorstellingstekst($voorstellingsnummer)
{
	return data_getVoorstellingstekst($voorstellingsnummer);
}

function getAdminmailadres()
{
	return dataaccess_getParameter('TICKETS_SECMAIL')->getValue();
}

function getDagenVoorHerinnering()
{
	return dataaccess_getParameter('TICKETS_HERINNERING')->getValue();
}

function getDagenVoorAnnulatie()
{
	return dataaccess_getParameter('TICKETS_ANNULATIE')->getValue();
}

function getVoorstellingen()
{
	return data_getVoorstellingen();
}

function getEventName()
{
    $eventname = dataaccess_GetParameter('TICKETS_EVENT_NAME');
    return $eventname->getValue();
}

function getEventPrice()
{
    $eventprice = dataaccess_GetParameter('TICKETS_PRIJS');
    return $eventprice->getValue();
}

function getTelephoneNR()
{
    $telefoonnr = dataaccess_GetParameter('TICKETS_TELEFOONNR');
    return $telefoonnr->getValue();
            
}

function getZaalAdres()
{
    $zaaladres = dataaccess_GetParameter('TICKETS_ZAALADRES');
    return $zaaladres->getValue();
}

function getBankAccount()
{
    $accountnr = dataaccess_GetParameter('TICKETS_BANKNR');
    return $accountnr->getValue();
}

function getTicketSaleStarted()
{
    $time_as_string = dataaccess_GetParameter('TICKETS_STARTDATE')->getValue();
    
    $timezone = new DateTimeZone('Europe/Brussels');

    #We hebben de startdatum opgehaald en creëren nu een datetime object
    $startdate = DateTime::createFromFormat('YmdGi', $time_as_string, $timezone);
    
    #We halen nu de systeemdatum op volgens onze tijdzone
    $now = new DateTime('now', $timezone);
    
    if($now>$startdate)
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>