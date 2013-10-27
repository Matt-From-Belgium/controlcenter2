<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditiekandidaat.php';

function data_addKandidaat(auditieKandidaat $kandidaat)
{
	$query = 'INSERT INTO auditiekandidaten (secretkey,voornaam,naam,mailadres,stemgroep) VALUES ("@secretkey","@voornaam","@naam","@mailadres",@stemgroep)';
	$db = new dataconnection();
	$db->setQuery($query);
	
	$db->setAttribute("secretkey",$kandidaat->getKey());
	$db->setAttribute("voornaam",$kandidaat->getVoornaam());
	$db->setAttribute("naam",$kandidaat->getNaam());
	$db->setAttribute("mailadres",$kandidaat->getMailadres());
	$db->setAttribute("stemgroep",$kandidaat->getStemgroepInt());
	
	$db->executeQuery();
	$kandidaat->setId($db->getLastId());
	
	return $kandidaat;
}

function data_getKandidaatByKey($key)
{
	###Deze functie haalt een kandidaat op op basis van de geheime sleutel
	$query = "SELECT id,secretkey,voornaam,naam,mailadres,stemgroep FROM auditiekandidaten WHERE auditiekandidaten.secretkey='@key'";
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute('key',$key);
	$db->executeQuery();
	
	$result = $db->getResultArray();
	
	if($db->getNumRows()==1)
	{
		$record = $result[0];

		$kandidaat = new auditiekandidaat($record['voornaam'],$record['naam'],$record['mailadres'],$record['stemgroep'],$key);
		return $kandidaat;
	}
	else
	{
		return false;
	}
}

function dataBevestigInschrijving(defAuditieKandidaat $kandidaat)
{
	$query = 'UPDATE auditiekandidaten SET adres="@adres",gsm="@gsm",geboortedatum="@geboorte",hoogstenoot="@hoogstenoot",laagstenoot="@laagstenoot",partiturenlezen="@partiturenlezen",ervaring="@ervaring",zangles="@zangles",instrument="@instrument",ervaringinstrument="@ervaringinstrument",motivatie="@motivatie",definitief="Y" WHERE secretkey="@key"';
	
	$db= new dataconnection();
	$db->setQuery($query);
	
	$db->setAttribute('adres',$kandidaat->getAdres());
	$db->setAttribute('gsm',$kandidaat->getGSM());
	$db->setAttribute('geboorte',$kandidaat->getGeboortedatum());
	$db->setAttribute('hoogstenoot',$kandidaat->getHoogsteNoot());
	$db->setAttribute('laagstenoot',$kandidaat->getLaagsteNoot());
	$db->setAttribute('partiturenlezen',$kandidaat->getPartiturenLezen());
	$db->setAttribute('ervaring',$kandidaat->getErvaring());
	$db->setAttribute('zangles',$kandidaat->getZangles());
	$db->setAttribute('instrument',$kandidaat->getInstrument());
	$db->setAttribute('ervaringinstrument',$kandidaat->getErvaringInstrument());
	$db->setAttribute('motivatie',$kandidaat->getMotivatie());
	$db->setAttribute('key',$kandidaat->getKey());
	
	try
	{
	$db->executeQuery();
	}
	catch(CC2Exception $ex)
	{
		echo $ex->getExtendedMessage();
	}
}

function dataBevestigd(auditieKandidaat $kandidaat)
{
	$query = "SELECT definitief from auditiekandidaten WHERE secretkey='@key'";
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute('key',$kandidaat->getKey());
	
	try
	{
	$db->executeQuery();
	}
	catch(CC2Exception $ex)
	{
		$ex->getExtendedMessage();
	}
	
	$waarde = $db->getScalar();
	
	if($waarde == 'Y')
	{

		return true;

	}
	
	else
	{

		return false;
	}
}

function dataMailReedsGekend($mailadres)
{
	$query = "SELECT auditiekandidaten.id from auditiekandidaten WHERE auditiekandidaten.mailadres='@mailadres'";
	
	$mailadres = trim(strtolower($mailadres));
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute('mailadres',$mailadres);
	$db->executeQuery();
	
	if($db->getNumRows()>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/*###DEBUG mailReedsGEkend
if(mailReedsGekend('matthias@projectkoor.be'))
{
	echo "niet ok";
}
else
{
	echo "ok";
}
*/

/*
###DEBUG
$kandidaat = data_GetKandidaatByKey("2a5781dfd8068670921800c8e0b55b5c");

echo dataBevestigd($kandidaat);
*/
/*
###DEBUG
$auditiekandidaat = new auditieKandidaat("Bauw","Matthias","matthias.bauw@gmail.com",4);
echo $auditiekandidaat->getKey();
print_r($auditiekandidaat);

try
{
addAuditiekandidaat($auditiekandidaat);
}
catch(CC2Exception $ex)
{
	echo $ex->getExtendedMessage();
}
*/
/*
###Debug getKandidaatByKey

print_r(data_getKandidaatByKey("378f4b06c832f4c35943b82fd9f1a55b"));
*/

/*
###DEBUG uitgebreideinschrijving
$auditiekandidaat = data_getKandidaatByKey("2a5781dfd8068670921800c8e0b55b5c");

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

try
{
dataBevestigInschrijving($definitievekandidaat);
}
catch(CC2Exception $ex)
{
	echo $ex->getExtendedMessage();
}
*/
?>