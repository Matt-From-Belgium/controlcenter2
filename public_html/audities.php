<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html = new htmlpage('frontend');
/*$html->LoadAddin('/addins/audities.tpa');*/
$html->loadAddin('/modules/audities/geenaudities.tpa');
$html->setVariable('mailadres', 'inschrijvingen@projectkoorchantage.be');
$html->PrintHTML();
?>
