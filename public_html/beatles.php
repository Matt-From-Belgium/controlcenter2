<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/beatleslogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatielogic.php';



$fbAppId = getFacebookAppID();
$fbNameSpace = getFacebookNameSpace();


$html = new htmlpage('frontend');

/*
if(!getTicketSaleStarted())
{
    $html->LoadAddin('/addins/beatles.tpa');
}
else {
    $html->loadAddin('/addins/beatles-fase2.tpa');
}*/

$html->LoadAddin('/addins/beatles-fase3.tpa');

$html->loadScript('/modules/fotoalbum/presentation/showalbum.js');
$html->loadScript('/scripts/beatles.final.js');
$html->loadScript('/core/presentation/usermanagement/accounts/fbRegister.final.js');

if(isset($_COOKIE['beatlesvoted']))
{
    $html->setVariable('votedcookie', 1);
    
    ###Er is gestemd, we tonen de keuze van de gebruiker
    $votedsong = searchBeatlesSongById($_COOKIE['beatlesvoted']);
    $html->setVariable('votedTitle', $votedsong->getTitle());
}
else
{
   $html->setVariable((votedcookieno), 1);
}

if(!isset($_GET['id']))
{
###Geen id gezet dus we moeten een concertobject aanmaken
$html->addCustomMeta('og:type', $fbNameSpace.':concert');
$html->addCustomMeta('fb:app_id', $fbAppId);
$html->addCustomMeta('og:url', 'http://www.projectkoorchantage.be/beatles.php');
$html->addCustomMeta('og:title','Projectkoor CHANTage brengt Beatles-concert');
$html->addCustomMeta('og:image', 'http://www.projectkoorchantage.be/images/beatles/beatlesFB2.png');
$html->addCustomMeta('og:description',"In 1964 beleefden 'The Beatles' hun grote doorbraak. Ze werden de grondleggers van de hedendaagse popmuziek. En toch... zo heeft u hen nog nooit gehoord. Projectkoor CHANTage brengt u een mix van hun meest gekende nummers.");
}
else
{
    ###ER is een id gezet, dus dit is een link van iemand die via FB zijn keuze heeft gedeeld
    $selectedsong = searchBeatlesSongById($_GET['id']);
    
    $html->addCustomMeta('og:type', 'music.song');
    $html->addCustomMeta('og:locale', 'nl_NL');
    $html->addCustomMeta('og:url', "http://www.projectkoorchantage.be/beatles.php?id=".$selectedsong->getID());
    $html->addCustomMeta('og:title',"Mijn favoriete Beatles-hit: ".$selectedsong->getTitle());
    $html->addCustomMeta('og:image', 'http://www.projectkoorchantage.be/images/beatles/beatlesFB2.png');
    $html->addCustomMeta('og:description',"Wat is jouw favoriete Beatles-hit? Stem hier!");

}
$html->PrintHTML();


?>
