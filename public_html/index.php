<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html = new htmlpage('frontend');
$html->LoadAddin('/pages/addins/index.tpa');
$html->loadCSS('/templates/jestaatnietalleen/css/index.css');
$html->PrintHTML(); 
?>
