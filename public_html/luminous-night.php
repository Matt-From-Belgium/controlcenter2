<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html = new htmlpage('minimalistic');
$html->LoadAddin('/addins/luminous-night.tpa');
$html->loadCSS('/modules/fotoalbum/presentation/css/showphoto.css');
$html->loadCSS('/extracss/luminous.css');
$html->loadScript('/modules/fotoalbum/presentation/showalbum.js');

$html->PrintHTML();
?>
