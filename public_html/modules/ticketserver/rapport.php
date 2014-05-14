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



$queryvoorstellingen = "SELECT voorstellingen.id,voorstellingen.datumtijd from voorstellingen";
$db->setQuery($queryvoorstellingen);
$db->ExecuteQuery();

$voorstellingsarray= array();
$result = $db->GetResultArray();


foreach($result as $value)
{
    $nieuwevoorstelling = array();
    $nieuwevoorstelling['tekst']=$value['datumtijd'];
    
    $ticketsvoorstelling = "select tickets.status,sum(tickets.aantal) as 'count' from tickets where tickets.voorstelling=@voorstellingid GROUP BY tickets.status ";
    $db2 = new DataConnection();
    $db2->setQuery($ticketsvoorstelling);
    $db2->setAttribute('voorstellingid', $value['id']);
    $db2->ExecuteQuery();
    
    $voorstellingsinfo = $db2->GetResultArray();
    
    $statusoverzicht = array();
    $statusoverzicht['Definitief']=0;
    $statusoverzicht['Wacht op betaling'] =0;
    $statusoverzicht['Wacht op betaling (herinnering verstuurd)'] = 0;
    $statusoverzicht['Automatisch geannuleerd'] = 0;
    $statusoverzicht['Manueel geannuleerd']=0;
            
    foreach($voorstellingsinfo as $info)
    {
        $statusoverzicht[$info['status']]= $info['count'];
    }
    
    //Door het ENUM gebruik in de database moeten we hier knoeien om een correcte array te krijgen
    //We hebben nu een array $statusoverzicht[status] maar we moeten een array krijgen met numerieke keys
    //Daarom gaan we deze array nu doorlopen, op die manier worden de waarden in de juiste volgorde in de array geplaatst
    
    $aantalticketsoverzicht = array();
    
    foreach($statusoverzicht as $overzicht)
    {
        $nieuwevoorstelling['status'][]['aantal'] = $overzicht;
    }
    
    print_r($nieuwevoorstelling);
    
    $voorstellingsarray[] = $nieuwevoorstelling;
}




###Overzicht genereren van definitieve reservaties
$queryDefinitieveReservaties = "select tickets.id,voorstellingen.datumtijd,tickets.naam,tickets.voornaam,tickets.aantal,tickets.mail from tickets left join voorstellingen ON tickets.voorstelling = voorstellingen.id where tickets.status='Definitief' order by tickets.voorstelling";
$db->setQuery($queryDefinitieveReservaties);
$db->ExecuteQuery();

$definitievereservaties = $db->GetResultArray();

###Verdeling per gemeente
$queryGemeenten = "SELECT postcodes.gemeente,sum(tickets.aantal) as 'count' from tickets LEFT JOIN postcodes ON tickets.gemeente = postcodes.id GROUP BY tickets.gemeente";
$db->setQuery($queryGemeenten);
$db->ExecuteQuery();

$gemeenteverdeling = $db->GetResultArray();

###Aan het einde van de rit versturen we het rapport
$secmail = getReportingMailadres();

$rapport = new Email('mail');
$rapport->setTo($secmail);
$rapport->setMessageAddin('/modules/ticketserver/mailaddins/rapport.tpa');
$rapport->setSubject('Rapport ticketverkoop');

$rapport->setVariable('samenvatting', $voorstellingsarray);
$rapport->setVariable('definitief', $definitievereservaties);
$rapport->setVariable('gemeenten', $gemeenteverdeling);

$rapport->Send();