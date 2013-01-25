<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/countryfunctions.php";

function getCountries()
{
	return dataaccess_getcountries();
}
?>