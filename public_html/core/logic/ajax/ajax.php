<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/ajaxresponse.php";


if(isset($_POST['destination']) && isset($_POST['phpfunction']))
{
	require_once $_SERVER['DOCUMENT_ROOT'].$_POST['destination'];
	$_POST['phpfunction']();
}
else 
{
	echo "both destination and phpfunction need a value";
}
?>