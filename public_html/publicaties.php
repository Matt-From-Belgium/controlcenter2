<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html= new htmlpage('frontend');
$html->LoadAddin('/addins/publicaties.tpa');

$html->PrintHTML();

?>
