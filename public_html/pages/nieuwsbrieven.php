<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/nieuwsbrieflogic.php';

###Promotieberichtje in het nieuwsbriefformulier
$promotext = getNewsPromoText();


$html = new htmlpage('backend');
$html->LoadAddin('/pages/addins/nieuwsbrieven.tpa');
$html->setVariable('promotext', $promotext);
$html->PrintHTML();



?>

