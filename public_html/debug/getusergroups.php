<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";

$test=getUsergroups();

print_r($test);
?>