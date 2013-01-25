<?php
##CONTROLCENTER2
##Dit bestand bevat de functies om de database configuratieparameters op te halen.
Function GetDatabaseType()
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
	
	require_once $_SERVER['DOCUMENT_ROOT']."/core/pathtoconfig.php";
	
	$pathtoconfigfile = $pathtoconfig."/dbconfig.php";
	
	if(file_exists($pathtoconfigfile))
	{
	require $pathtoconfigfile;
	}
	else
	{
		throw new CC2Exception("The Controlcenter2 Config File could not be found","The Controlcenter2 Config File could not be found, please edit /core/pathtoconfig.php with the right path");
	}

	return $_DATABASE['type'];
}

Function GetDatabaseParameters()
{
	include $_SERVER['DOCUMENT_ROOT']."/core/pathtoconfig.php";
	require $pathtoconfig."/dbconfig.php";

	$parameters['host']= $_DATABASE['host'];
	$parameters['database'] = $_DATABASE['database'];
	$parameters['user'] = $_DATABASE['user'];
	$parameters['password']= $_DATABASE['password'];

	return $parameters;
}
?>