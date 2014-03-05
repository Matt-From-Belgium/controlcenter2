<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html = new htmlpage('frontend');
$html->LoadAddin('/addins/kerstconcert.tpa');
$html->loadScript('/modules/fotoalbum/presentation/showalbum.js');

$html->PrintHTML();
?>
