<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html = new htmlpage('frontend');
$html->LoadAddin('/addins/beatles.tpa');
$html->loadScript('/modules/fotoalbum/presentation/showalbum.js');
$html->loadScript('/scripts/beatles.js');
$html->loadScript('/core/presentation/usermanagement/accounts/fbRegister.js');
$html->PrintHTML();
?>
