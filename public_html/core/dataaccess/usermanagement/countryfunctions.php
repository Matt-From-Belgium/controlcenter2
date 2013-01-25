<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/dataconnection/componentselector.php";

function dataaccess_getcountries()
{
	#Deze functies haalt de landcodes op uit de database en geeft ze terug in een array
	$db = new dataconnection();
	$query = "SELECT countries.code FROM countries";
	$db->setQuery($query);
	$db->ExecuteQuery();
	
	$resultarray = $db->getResultArray();
	
	foreach($resultarray as $key=>$value)
	{
		list($code) = $resultarray[$key];
		$codelist[] = $code;
	}
	
	return $codelist;
}
?>