<?php
###CONTROLCENTER2
###COMPONENT SELECTOR SCRIPT

###Dit script gaat kijken in de configuratiefile en selecteert aan de hand daarvan
###een data component voor verbinding met de database

###De functie GetDatabaseType() haalt het databasetype op uit de configuratiefile. Op die manier
###blijven de gegevens beschermd.
require_once $_SERVER['DOCUMENT_ROOT']."/core/fileaccess/database.php";
$databasetype = GetDatabaseType();

#Nu we het databasetype kennen moeten we zoeken of er een datacomponent bestaat met deze naam
#Datacomponents worden opgeslagen onder /core/dataconnection/components/$_DATABASE['type']/component.php
if(file_exists($_SERVER['DOCUMENT_ROOT']."/core/dataconnection/components/".strtolower($databasetype)."/component.php"))
{
require_once $_SERVER['DOCUMENT_ROOT']."/core/dataconnection/components/".strtolower($databasetype)."/component.php";
}
else
{
	#Het databasecomponent is niet gevonden => Exception
	require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
	throw new CC2Exception("Could not connect to database","Datacomponent $databasetype does not exist");
}
?>