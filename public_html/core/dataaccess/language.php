<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
function dataaccess_getlanguagestring($integer)
{
	if(is_numeric($integer))
	{
		require_once $_SERVER['DOCUMENT_ROOT']."/core/dataconnection/componentselector.php";
		$db = new dataconnection;
		$query = "SELECT languages.name from languages WHERE languages.id=@integer";
		
		$db->setQuery($query);
		$db->setAttribute("integer",$integer);
		$db->ExecuteQuery();
		
			if($db->getNumRows()>0)
			{
				return $db->getScalar();
			}
			else
			{
				throw new CC2Exception("Error while getting language","dataaccess_getlanguagestring() was unable to find a language with id $integer");
			}
	}
	else
	{
	throw new CC2Exception("Error while getting language","The argument you supplied for dataaccess_getlanguagestring() is not an integer");
	}
}
?>
