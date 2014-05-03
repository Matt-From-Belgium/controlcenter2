<?php
###Annulaties is een cronjob => $_SERVER['DOCUMENT_ROOT'] = '/' => manueel aanpassen
$directoryvanditscript = dirname(__FILE__);
$directoryvanditscript = explode('/modules',$directoryvanditscript);
$_SERVER['DOCUMENT_ROOT']=$directoryvanditscript[0];

require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/email/email.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatielogic.php';

###We sturen een rapport met alle uitgevoerde reservaties. We delen ze op per categorie

$db = new DataConnection();

$querysamenvatting = "SELECT tickets.status,sum(tickets.aantal) as 'count' from tickets group by tickets.status";
$db->setQuery($querysamenvatting);
$db->ExecuteQuery();
$samenvattingsarray = $db->GetResultArray();

###Overzicht genereren van definitieve reservaties
$queryDefinitieveReservaties = "select tickets.id,tickets.naam,tickets.voornaam,tickets.aantal,tickets.mail from tickets where tickets.status='Definitief'";
$db->setQuery($queryDefinitieveReservaties);
$db->ExecuteQuery();

$definitievereservaties = $db->GetResultArray();

###Verdeling per gemeente
$queryGemeenten = "SELECT postcodes.gemeente,sum(tickets.aantal) as 'count' from tickets LEFT JOIN postcodes ON tickets.gemeente = postcodes.id GROUP BY tickets.gemeente";
$db->setQuery($queryGemeenten);
$db->ExecuteQuery();

$gemeenteverdeling = $db->GetResultArray();

###Aan het einde van de rit versturen we het rapport
$secmail = getAdminmailadres();

$rapport = new Email('mail');
$rapport->setTo($secmail);
$rapport->setMessageAddin('/modules/ticketserver/mailaddins/rapport.tpa');
$rapport->setSubject('Rapport ticketverkoop');

$rapport->setVariable('samenvatting', $samenvattingsarray);
$rapport->setVariable('definitief', $definitievereservaties);
$rapport->setVariable('gemeenten', $gemeenteverdeling);

$rapport->Send();