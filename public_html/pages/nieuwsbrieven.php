<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/nieuwsbrieflogic.php';

###Promotieberichtje in het nieuwsbriefformulier
$promotext = getNewsPromoText();


$html = new htmlpage('frontend');
$html->LoadAddin('/pages/addins/nieuwsbrieven.tpa');
$html->loadScript('/modules/nieuwsbrief/scripts/facebook.js');
$html->setVariable('promotext', $promotext);

//Facebooklink enkel tonen wanneer integratie actief is
if($html->getFacebookIntegration())
{
    $html->setVariable('fbintegration', true);
}

$html->PrintHTML();



?>

