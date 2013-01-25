<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";
session_start();


$currentusername = $_SESSION['currentuser']->getUsername();
echo $currentusername;

$permissions = $_SESSION['currentuser']->getUsergroups();
print_r($permissions);

$usergroup1 = getUsergroup($permissions[0]);
$permissions = $usergroup1->getPermissions();
print_r($permissions);
?>