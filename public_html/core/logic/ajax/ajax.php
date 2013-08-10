<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/ajaxresponse.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/email/email.php";

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
            ###We versturen de errormail zoals gedefinieerd in /core/common/errorhandler.php
            CC_Send_Error_report($ex);
            
            if(getDebugMode())
            {
                ###Als debug mode geactiveerd is wordt de error teruggestuurd als antwoord
                $exceptionResponse = new ajaxResponse('error');
                $exceptionResponse->addErrorMessage('test', $ex->getMessage());
                echo $exceptionResponse->getXML();
            }
            else
            {                
                    ###Nu geven we nog antwoord aand het JavaScript dat de functie heeft aangeroepen, maar dat verraadt niets
                    $exceptionResponse = new ajaxResponse('error');
                    $exceptionResponse->addErrorMessage('test', 'Serverfout');
                    echo $exceptionResponse->getXML();                    
            }

        }
}
else 
{
	echo "both destination and phpfunction need a value";
}
?>