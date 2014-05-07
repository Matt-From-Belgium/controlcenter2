<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";

checkPermission('', 'algemeen beheerscherm');

$html = new htmlpage('backend');
$html->LoadAddin('/pages/addins/admin.tpa');
$html->PrintHTML();

