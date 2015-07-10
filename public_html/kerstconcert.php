<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html = new htmlpage('frontend');
$html->LoadAddin('/addins/kerstconcert.tpa');
$html->loadCSS('/modules/fotoalbum/presentation/css/showphoto.css','/modules/fotoalbum/presentation/css/showphoto-mobile.css');
$html->loadScript('/modules/fotoalbum/presentation/showalbum.js');

$html->PrintHTML();
?>
