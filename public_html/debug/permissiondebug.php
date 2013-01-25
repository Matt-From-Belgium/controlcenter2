<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';

try
 {
checkPermission('','login during interruption');
echo "ok";
 }
 catch(CC2Exception $ex)
 {
	 echo $ex->getExtendedMessage();
 }
?>