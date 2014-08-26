<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';

checkPermission('fotoalbum', 'manage albums');

$html = new htmlpage('backend');
$html->LoadAddin('/modules/fotoalbum/addins/managealbums.tpa');
$html->enableAjax();
$html->loadCSS('/modules/fotoalbum/presentation/css/editphoto.css');
$html->loadScript('fotoalbums.js');
$html->PrintHTML();
?>
