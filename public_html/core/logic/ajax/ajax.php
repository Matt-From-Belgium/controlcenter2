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
            
                  ###We versturen het foutrapport
                    $debugmailadress = getDebugMailadress();

                    $errorreport = new Email();
                    $errorreport->setTo($debugmailadress);
                    $errorreport->setMessageAddin('/core/presentation/general/addins/debugmail.tpa');
                    $errorreport->setSubject(LANG_ERROR_REPORT);
                    $errorreport->setVariable('message', $ex->getMessage());
                    $errorreport->setVariable('file', $ex->getFile());
                    $errorreport->setVariable('line', $ex->getLine());
                    $errorreport->setVariable('post', print_r($_POST,true));
                    $errorreport->setVariable('get', print_r($_GET,true));
                    $errorreport->Send();
        }
}
else 
{
	echo "both destination and phpfunction need a value";
}
?>