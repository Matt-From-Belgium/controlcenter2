<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatielogic.php';

$html = new htmlpage('minimalistic');


$fbAppId = getFacebookAppID();
$fbNameSpace = getFacebookNameSpace();
$html->addCustomMeta('og:type', $fbNameSpace.':concert');
$html->addCustomMeta('fb:app_id', $fbAppId);
$html->addCustomMeta('og:url', 'http://www.projectkoorchantage.be/luminous-night.php');
$html->addCustomMeta('og:title','Luminous Night of The Soul');
$html->addCustomMeta('og:image', 'http://www.projectkoorchantage.be/images/luminous/luminousfb.png');
$html->addCustomMeta('og:description',"Projectoor CHANTage neemt u mee van donker naar licht. Het filmisch aandoend programma bevat werk van gevestigde waarden uit de klassieke muziek. Tijdens dit sfeervolle concert zullen wij de herst even verjagen...");


$html->loadCSS('/modules/fotoalbum/presentation/css/showphoto.css');
$html->loadCSS('/extracss/luminous.css');
$html->loadScript('/modules/fotoalbum/presentation/showalbum.js');
$html->loadScript(('/scripts/luminous.js'));

if(getTicketSaleStarted())
{
    $html->LoadAddin('/addins/luminous-night_ticketverkoop_gestart.tpa');
}
else
{
    $html->LoadAddin('/addins/luminous-night.tpa');
}


$html->PrintHTML();
?>
