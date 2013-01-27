<?php
###Annulaties is een cronjob => $_SERVER['DOCUMENT_ROOT'] = '/' => manueel aanpassen
$directoryvanditscript = dirname(__FILE__);
$directoryvanditscript = split('/modules',$directoryvanditscript);
$_SERVER['DOCUMENT_ROOT']=$directoryvanditscript[0];


require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/email/email.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatielogic.php';

$db = new dataconnection();

###De query haalt de reservaties op die meer dan TICKETS_HERINNERING + TICKETS_ANNULATIE dagen geleden zijn ingevoerd en die nog niet zijn betaald.
$herinnering = getDagenVoorHerinnering();
$annulatie = getDagenVoorAnnulatie();

$totaalaantaldagen = $herinnering + $annulatie;

$query = "SELECT id,datum,voornaam,naam,mail,aantal,voorstelling FROM tickets WHERE DATE_SUB( CURDATE( ) , INTERVAL @herinnering DAY ) >= tickets.datum AND tickets.status='Wacht op betaling (herinnering verstuurd)'";
$db->setQuery($query);
$db->setAttribute('herinnering',$totaalaantaldagen);
$db->executeQuery();

$result = $db->getResultArray();
$aantalannulaties = $db->getNumRows();

if($aantalannulaties>0)
{
	$secmailadres = getAdminmailadres();
	###Er moeten herinneringen verstuurd worden
	foreach($result as $key=>$value)
	{
		###Eén voor één worden de reservaties die aan de criteria voldoen 
		list($id,$datum,$voornaam,$naam,$mail,$aantal,$voorstelling) = $result[$key];
		
		$reservatie['id']=$id;
		$reservatie['naam']=$naam;
		$reservatie['voornaam']=$voornaam;
		$reservatie['aantal']=$aantal;
		$reservatie['datum']= getVoorstellingsTekst($voorstelling);
	
		$reservaties[]=$reservatie;
	
		echo "$id|$datum|$voornaam|$naam|$mail\n";
	
		###Er wordt een mail verstuurd naar de klant om te melden dat de betaling nog niet werd ontvangen.
		$mailnaarklant = new email("mail");
		$mailnaarklant->setTo($mail);
		$mailnaarklant->setFrom($secmailadres);
		$mailnaarklant->setSubject("Uw reservatie - ANNULATIE");
		$mailnaarklant->setVariable('familienaam',$naam);
		$mailnaarklant->setVariable('aantaldagen',$totaalaantaldagen);
		$mailnaarklant->setVariable('datum',getVoorstellingsTekst($voorstelling));
		$mailnaarklant->setVariable('aantal',$aantal);
	
		$prijs = $aantal * 8;
	
		$mailnaarklant->setVariable('prijs',$prijs);
		$mailnaarklant->setVariable('reservatienummer',$id);
	
		$mailnaarklant->setMessageAddin('/modules/ticketserver/mailaddins/autoannulatie.tpa');
		$mailnaarklant->Send();
	
		###De status van de reservatie moet gewijzigd worden in Wacht op betaling (herinnering verstuurd)
		$query="UPDATE tickets SET tickets.status = 'Automatisch geannuleerd' WHERE tickets.id=@id";
	
		$db = new dataconnection();
		$db->setQuery($query);
		$db->setAttribute('id',$id);
		$db->executeQuery();
	}
	###Er wordt een mail naar het secretariaat gestuurd om te melden dat er herinneringen werden verstuurd.

	
	$mailnaarsec = new email("mail");
	$mailnaarsec->setTo($secmailadres);
	$mailnaarsec->setSubject('Er werden reservaties geannuleerd');
	$mailnaarsec->setMessageAddin('/modules/ticketserver/mailaddins/secannulaties.tpa');
	$mailnaarsec->setVariable('herinneringen',$reservaties);
	$mailnaarsec->Send();
	

	echo $aantalherinneringen." annulaties uitgevoerd";
}
else
{
	echo "Het was niet nodig om annulaties te versturen";
}


?>