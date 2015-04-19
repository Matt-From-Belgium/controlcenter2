<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html= new htmlpage('frontend');
$html->LoadAddin('/addins/publicaties.tpa');

$html->loadCSS('/pages/css/publicaties-desktop.css');
$html->loadScript('pages/javascript/publicaties.js');

$html->PrintHTML();

?>
