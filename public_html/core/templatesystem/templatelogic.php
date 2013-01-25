<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatedata.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatefile.php";

function AliasGetlinkeddir($alias)
{
	return DataAccess_AliasGetlinkeddir($alias);
}

function GetTemplatehtml($directory)
{
	return fileaccess_gettemplatehtml($directory);
}

function GetAddinHTML($path)
{
	return fileaccess_GetAddinHTML($path);
}
?>
