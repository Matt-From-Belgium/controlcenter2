<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";

if(dataaccess_checkUserPassword("matthiasba","nmcrCAbs"))
{
	echo "ok";
}
else
{
	echo "niet ok";
}
?>