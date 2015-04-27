<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html = new htmlpage('frontend');
$html->LoadAddin('/pages/addins/sectoren.tpa');
$html->loadCSS('/pages/css/sectoren.css');
$html->PrintHTML();



?>

