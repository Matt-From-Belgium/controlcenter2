<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";

try
{
$html = new htmlpage("frontend");
$html->LoadAddin('/addins/3musketiers.tpa');
$html->printHTML();
}
catch(CC2Exception $err)
{
	echo $err->getExtendedMessage();
}
?>