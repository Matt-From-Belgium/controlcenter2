<?php
function data_getVoorstellingstekst($voorstellingsnummer)
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/exception.php';
	
	$query = "SELECT voorstellingen.datumtijd FROM voorstellingen WHERE voorstellingen.id=@voorstellingsnummer";
	
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute('voorstellingsnummer',$voorstellingsnummer);
	$db->executeQuery();
	
	if($db->getNumRows() ==1)
	{
		return $db->getScalar();
	}
	else
	{
		if($db->getNumRows() == 0)
		{
			throw new CC2Exception('The ticketsystem caused an error','voorstellingsnummer is ongeldig');
		}
		else
		{
			throw new CC2Exception('The ticketsystem caused an error','ongeldig aantal resultaten op getVoorstellingstekst');			
		}
	}
}

function data_getVoorstellingen()
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
	
	$query = "SELECT voorstellingen.id,voorstellingen.datumtijd,voorstellingen.volzet FROM voorstellingen WHERE voorstellingen.datum > DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
	$db = new dataconnection();
	$db->setQuery($query);
	$db->executeQuery();
	
	$result = $db->getResultArray();
	
	foreach($result as $key=>$value)
	{
		list($id,$datumtijd,$volzet)=$result[$key];
		
		$voorstelling['voorstellingsnummer']=$id;
		$voorstelling['voorstellingstekst']=$datumtijd;
		$voorstelling['volzet']=$volzet;
		
		$voorstellingen[] = $voorstelling;
	}
	
	return $voorstellingen;
}

function reservatieToevoegen($reservatie)
{	
	###De gegevens worden naar de database geschreven als backup en voor het nemen van statistieken
	$db = new dataconnection();
	$query = 'INSERT INTO tickets (datum,naam,voornaam,mail,straat,huisnummer,gemeente,aantal,voorstelling,referral) VALUES (CURDATE(),"@naam","@voornaam","@mail","@straat","@huisnummer","@gemeente",@aantal,@voorstelling,@referral)';
	$db->setQuery($query);
	$db->setAttribute("naam",$reservatie->getNaam());
	$db->setAttribute("voornaam",$reservatie->getVoornaam());
	$db->setAttribute("mail",$reservatie->getMailadres());
	$db->setAttribute("straat",$reservatie->getStraat());
	$db->setAttribute("huisnummer",$reservatie->getHuisNr());
	$db->setAttribute("gemeente",$reservatie->getGemeente());
	$db->setAttribute("aantal",$reservatie->getAantalTickets());
	$db->setAttribute("voorstelling",$reservatie->getVoorstelling());
	$db->setAttribute("referral",$reservatie->getReferral());
	$db->ExecuteQuery();
		
	###We willen de gebruiker een reservatienummer geven => we moeten dit ophalen
	$reservatieid = $db->getLastId();
	return $reservatieid;
}

function data_openReservatie($reservatienummer)
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/exception.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatieclass.php';
	
	$query = 'SELECT tickets.id,tickets.datum,tickets.naam,tickets.voornaam,tickets.mail,tickets.aantal,tickets.voorstelling,tickets.status FROM tickets WHERE tickets.id=@reservatienummer';
	
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute('reservatienummer',$reservatienummer);
	$db->executeQuery($query);
	
	if($db->getNumRows()==1)
	{
		###De functie geeft een verwacht resultaat
		###We moeten nu een reservatieobject opbouwen
		$result = $db->getResultArray();
		list($id,$datum,$naam,$voornaam,$mail,$aantal,$voorstelling,$status) = $result[0];
		
		$reservatie = new reservatie($id);
		$reservatie->setDatum($datum);
		$reservatie->setNaam($naam);
		$reservatie->setVoornaam($voornaam);
		$reservatie->setMailadres($mail);
		$reservatie->setAantalTickets($aantal);
		$reservatie->setVoorstelling($voorstelling);
		$reservatie->setStatus($status);
		
		return $reservatie;
	}
	else
	{
		if($db->getNumRows()==0)
		{
			###Geen resultaat gevonden => false
			return false;
		}
		else
		{
			###Geen geldig resultaat => exception
			throw new CC2Exception('Fout in het reservatiesysteem',"Resultaat van de query moet 1 of geen rijen zijn");
		}
	}
}

function data_reservatieBetaald($reservatienummer)
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
	
	$query = "UPDATE `tickets` SET `status` = 'Definitief' WHERE `tickets`.`id` =@reservatienummer";
	
	$db= new dataconnection();
	$db->setQuery($query);
	$db->setAttribute('reservatienummer',$reservatienummer);
	$db->executeQuery();
}

function data_reservatieAnnuleren($reservatienummer)
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
	
	$query = "UPDATE `tickets` SET `status` = 'Manueel geannuleerd' WHERE `tickets`.`id` =@reservatienummer";
	
	$db= new dataconnection();
	$db->setQuery($query);
	$db->setAttribute('reservatienummer',$reservatienummer);
	$db->executeQuery();
}

function data_reservatieHeractiveren($reservatienummer)
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
	
	$query = "UPDATE `tickets` SET `status` = 'Wacht op betaling' WHERE `tickets`.`id` =@reservatienummer";
	
	$db= new dataconnection();
	$db->setQuery($query);
	$db->setAttribute('reservatienummer',$reservatienummer);
	$db->executeQuery();
}
?>