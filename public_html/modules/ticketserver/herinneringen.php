<?php
###Herinneringen is een cronjob => $_SERVER['DOCUMENT_ROOT'] = '/' => manueel aanpassen
$directoryvanditscript = dirname(__FILE__);
$directoryvanditscript = explode('/modules',$directoryvanditscript);
$_SERVER['DOCUMENT_ROOT']=$directoryvanditscript[0];


require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/email/email.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatielogic.php';

$db = new dataconnection();

###De query haalt de reservaties op die meer dan 7 dagen geleden zijn ingevoerd en die nog niet zijn betaald.
$herinnering = getDagenVoorHerinnering();
$annulatie = getDagenVoorAnnulatie();

$query = "SELECT id,datum,voornaam,naam,mail,aantal FROM tickets WHERE DATE_SUB( CURDATE( ) , INTERVAL @herinnering DAY ) >= tickets.datum AND tickets.status='Wacht op betaling'";
$db->setQuery($query);
$db->setAttribute('herinnering',$herinnering);
$db->executeQuery();

$result = $db->getResultArray();
$aantalherinneringen = $db->getNumRows();

if($aantalherinneringen>0)
{
	$secmailadres = getAdminmailadres();
	###Er moeten herinneringen verstuurd worden
	foreach($result as $key=>$value)
	{
		###Eén voor één worden de reservaties die aan de criteria voldoen 
		list($id,$datum,$voornaam,$naam,$mail,$aantal) = $result[$key];
		
		$reservatie['id']=$id;
		$reservatie['naam']=$naam;
		$reservatie['voornaam']=$voornaam;
	
		$reservaties[]=$reservatie;
	
		echo "$id|$datum|$voornaam|$naam|$mail\n";
	
		###Er wordt een mail verstuurd naar de klant om te melden dat de betaling nog niet werd ontvangen.
		$mailnaarklant = new email("mail");
		$mailnaarklant->setFrom($secmailadres);
		$mailnaarklant->setTo($mail);
		$mailnaarklant->setSubject("Uw reservatie - herinnering");
		$mailnaarklant->setVariable('familienaam',$naam);
		$mailnaarklant->setVariable('dagenvoorherinnering',$herinnering);
		$mailnaarklant->setVariable('dagenvoorannulatie',$annulatie);
	
		$prijs = $aantal * 8;
	
		$mailnaarklant->setVariable('prijs',$prijs);
		$mailnaarklant->setVariable('reservatienummer',$id);
	
		$mailnaarklant->setMessageAddin('/modules/ticketserver/mailaddins/herinnering.tpa');
		$mailnaarklant->Send();
	
		###De status van de reservatie moet gewijzigd worden in Wacht op betaling (herinnering verstuurd)
		$query="UPDATE tickets SET tickets.status = 'Wacht op betaling (herinnering verstuurd)' WHERE tickets.id=@id";
	
		$db = new dataconnection();
		$db->setQuery($query);
		$db->setAttribute('id',$id);
		$db->executeQuery();
	}
	###Er wordt een mail naar het secretariaat gestuurd om te melden dat er herinneringen werden verstuurd.

	
	$mailnaarsec = new email("mail");
	$mailnaarsec->setTo($secmailadres);
	$mailnaarsec->setSubject('Er werden betalingsherinneringen verstuurd');
	$mailnaarsec->setMessageAddin('/modules/ticketserver/mailaddins/secherinneringen.tpa');
	$mailnaarsec->setVariable('herinneringen',$reservaties);
	$mailnaarsec->Send();
	

	echo $aantalherinneringen." herinneringen verstuurd";
}
else
{
	echo "Het was niet nodig om herinneringen te versturen";
}


?>