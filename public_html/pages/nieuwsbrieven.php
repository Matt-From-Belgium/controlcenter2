<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/nieuwsbrieflogic.php';

###Promotieberichtje in het nieuwsbriefformulier
$promotext = getNewsPromoText();


$html = new htmlpage('frontend');
$html->LoadAddin('/pages/addins/nieuwsbrieven.tpa');
$html->loadScript('/modules/nieuwsbrief/scripts/facebook.js');
$html->loadCSS('/pages/css/nieuwsbrieven-desktop.css','/pages/css/nieuwsbrieven-mobile.css');
$html->addCustomMeta('description', 'Werk je in een Kleine of Middelgrote onderneming en wil je op de hoogste blijven van belangrijke wijzigingen? Abonneer je dan hier op de nieuwsbrief.');
$html->addCustomMeta('og:type', 'website');
$html->addCustomMeta('og:url', 'http://www.jestaatnietalleen.be/pages/nieuwsbrieven.php');
$html->addCustomMeta('og:title', 'Nieuwsbrief voor KMO-werknemers');
$html->addCustomMeta('og:description', 'Werk je in een Kleine of Middelgrote onderneming en wil je op de hoogste blijven van belangrijke wijzigingen? Abonneer je dan hier op de nieuwsbrief.');
$html->addCustomMeta('og:image', 'http://www.jestaatnietalleen.be/images/facebookmetalogo.png');
  

$html->setVariable('promotext', $promotext);

//Facebooklink enkel tonen wanneer integratie actief is
if($html->getFacebookIntegration())
{
    $html->setVariable('fbintegration', true);
}

$html->PrintHTML();



?>

