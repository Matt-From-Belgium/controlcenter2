<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';

checkPermission('fotoalbum', 'manage albums');

$html = new htmlpage('frontend');
$html->LoadAddin('/modules/fotoalbum/addins/managealbums.tpa');
$html->PrintHTML();

?>
