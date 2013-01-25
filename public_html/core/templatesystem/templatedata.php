<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
function DataAccess_AliasGetlinkeddir($alias)
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataconnection/componentselector.php";
	$db = new dataconnection();
	
	$query = "SELECT templatealiases.directory FROM templatealiases WHERE templatealiases.name='@alias'";
	$db->setQuery($query);
	
	$db->setAttribute("alias",$alias);
	
	$db->ExecuteQuery();
	if($db->getNumRows() >0)
	{
	return $db->getScalar();
	}
	else
	{
	return false;
	}

}
?>