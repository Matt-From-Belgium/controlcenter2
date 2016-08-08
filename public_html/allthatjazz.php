<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatielogic.php';

$html = new htmlpage('minimalistic');


$fbAppId = getFacebookAppID();
$fbNameSpace = getFacebookNameSpace();
$html->addCustomMeta('og:type', $fbNameSpace.':concert');
$html->addCustomMeta('fb:app_id', $fbAppId);
$html->addCustomMeta('og:url', 'http://www.projectkoorchantage.be/allthatjazz.php');
$html->addCustomMeta('og:title','All That Jazz');
$html->addCustomMeta('og:image', 'http://www.projectkoorchantage.be/images/allthatjazz/allthatjazzfb.png');
$html->addCustomMeta('og:description',"In oktober 2016 brengen wij voor u een jazzconcert onder de noemer 'All That Jazz' We kiezen voor een licht en toegankelijk concert met de leukste jazzdeuntjes die ooit werden gemaakt. U kan genieten van onze interpretatie van nummers zoals 'New York', 'Pink Panther', 'In the mood' en andere klassiekers.");


$html->loadCSS('/modules/fotoalbum/presentation/css/showphoto.css','/modules/fotoalbum/presentation/css/showphoto-mobile.css');
$html->loadCSS('/extracss/allthatjazz.css','/extracss/allthatjazz.css');
$html->loadScript('/modules/fotoalbum/presentation/showalbum.js');
$html->loadScript(('/scripts/luminous.js'));

$html->LoadAddin('/addins/allthatjazz.tpa');


$html->PrintHTML();
?>
