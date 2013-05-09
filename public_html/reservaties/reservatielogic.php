<?php
require_once $_SERVER['DOCUMENT_ROOT']."/reservaties/reservatievalidator.php";
require_once $_SERVER['DOCUMENT_ROOT']."/reservaties/reservatieclass.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/email/email.php";

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
	
	###Nu moet het object gevalideerd worden
	$errormessages = $reservatievalidator->validateObject($reservatie);
	
	###Als de validatie geslaagd is is $errormessages leeg
	if(!is_array($errormessages))
	{
		###Totale prijs berekenen
		$totaal = $reservatie->getAantalTickets() * 8;
		
		###Er moet een mail gestuurd worden naar het secretariaat
		$mailnaarsec = new Email("mail");
		$mailnaarsec->setTo("webmaster@detoverlantaarn.be");
		$mailnaarsec->setSubject("Reservatie Spring Awakening");
		$mailnaarsec->setVariable("naam",$postarray['naam']);
		$mailnaarsec->setVariable("voornaam",$reservatie->getVoornaam());
		$mailnaarsec->setVariable("email",$reservatie->getEmail());
		$mailnaarsec->setVariable("aantal",$postarray['aantal']);
		$mailnaarsec->setVariable("voorstelling",$reservatie->getVoorstellingTekst());
		$mailnaarsec->setVariable("opmerkingen",$reservatie->getOpmerkingen());
		$mailnaarsec->setMessageAddin("/reservaties/mailaddins/secretariaat.tpa");
		$mailnaarsec->Send();
		
		###Er moet een mail gestuurd worden naar de gebruiker
		$mailnaargebruiker = new Email("mail");
		$mailnaargebruiker->setTo($postarray['email']);
		$mailnaargebruiker->setVariable("title","Reservatiebevestiging");
		$mailnaargebruiker->setVariable("familienaam",$postarray['naam']);
		$mailnaargebruiker->setVariable("voornaam",$reservatie->getVoornaam());
		$mailnaargebruiker->setVariable("datum",$reservatie->getVoorstellingTekst());
		$mailnaargebruiker->setVariable("aantal",$postarray['aantal']);
		$mailnaargebruiker->setVariable("totaal",$totaal);
		$mailnaargebruiker->setSubject("Uw reservatie voor Spring Awakening");
		$mailnaargebruiker->setMessageAddin("/reservaties/mailaddins/bevestiging.tpa");
		$mailnaargebruiker->Send();
		
		###De gegevens worden naar de database geschreven als backup en voor het nemen van statistieken
		$db = new dataconnection();
		$query = "INSERT INTO springawakening (naam,voornaam,aantal,voorstelling,referral) VALUES ('@naam','@voornaam',@aantal,@voorstelling,@referral)";
		$db->setQuery($query);
		$db->setAttribute("naam",$reservatie->getNaam());
		$db->setAttribute("voornaam",$reservatie->getVoornaam());
		$db->setAttribute("aantal",$reservatie->getAantalTickets());
		$db->setAttribute("voorstelling",$reservatie->getVoorstelling());
		$db->setAttribute("referral",$reservatie->getReferral());
		$db->ExecuteQuery();
	}
	
	return $errormessages;
}
?>