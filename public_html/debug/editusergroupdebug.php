<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/core/dataaccess/usermanagement/userfunctions.php";

try
{
$usergroup = dataaccess_getUserGroup($_GET['id']);
echo $usergroup->getName();

$taskarray = $usergroup->getPermissions();

print_r($taskarray);
}
catch(CC2Exception $ex)
{
	echo $ex->getExtendedMessage();
}
?>
