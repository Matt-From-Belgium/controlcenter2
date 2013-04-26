<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/ajaxresponse.php";


if(isset($_POST['destination']) && isset($_POST['phpfunction']))
{

	require_once $_SERVER['DOCUMENT_ROOT'].$_POST['destination'];

        try
        {
	$functionResult = $_POST['phpfunction']();
        echo $functionResult;
        }
        catch(Exception $ex)
        {
            $exceptionResponse = new ajaxResponse('error');
            $exceptionResponse->addErrorMessage('test', $ex->getMessage());
            echo $exceptionResponse->getXML();
        }
}
else 
{
	echo "both destination and phpfunction need a value";
}
?>