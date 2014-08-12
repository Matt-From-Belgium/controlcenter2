<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/ajaxresponse.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/email/email.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/ajax.php";

if(isset($_POST['destination']) && isset($_POST['phpfunction']))
{
        if(dataaccess_checkWhiteList($_POST['destination'], $_POST['phpfunction']))
        {
            require_once $_SERVER['DOCUMENT_ROOT'].$_POST['destination'];

            try
            {
                $functionResult = $_POST['phpfunction']();

                ###DEBUG: de functie kon misbruikt worden door clientside Javascript
                if(substr_count($functionResult,'<response>')>=1)
                {
                    ###het antwoord bevat de XML-response tag van ajaxresponse=>verder gaan
                    echo $functionResult;
                }
                else {
                    ###geen response-tag => poging om variabelen op te halen die niet publiek zijn?
                        $exceptionResponse = new ajaxResponse('error');
                        $exceptionResponse->addErrorMessage('test', 'response not XML');
                        echo $exceptionResponse->getXML(); 
                }

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
            ###de combinatie bestand - phpfunctie staat niet op de whitelist. Hacking?
            $ajaxresponse = new ajaxResponse('error');
            
            if(getDebugMode())
            {
                ###Debugmode is enabled. We geven de foutmelding terug in ajaxformaat
 
                $ajaxresponse->addErrorMessage('test', "De combinatie ".$_POST[destination]." en ".$_POST['phpfunction']." komen niet voor op de whitelist");
            
            }
            else 
            {
                $ajaxresponse->addErrorMessage('test', "Serverfout");
            }
            echo $ajaxresponse->getXML();
            
            ###We willen ook een foutrapport uitlokken dus gooien we nog een exception
            //throw new Exception("De combinatie ".$_POST[destination]." en ".$_POST['phpfunction']." komen niet voor op de whitelist");
            $ex = new Exception('test');
            //CC_Send_Error_report($ex);
        }
}
else 
{
	echo "both destination and phpfunction need a value";
}
?>