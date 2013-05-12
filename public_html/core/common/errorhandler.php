<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/parameters.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/email/email.php';

function CC_Exception_Handler(Exception $ex)
{
    ###We versturen het foutrapport
    ###BUGFIX: moet voor het gooien van de exception komen als debug mode actief is anders wordt 
    ###de mail niet verstuurd aangezien de exception alles stil legt.
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
    
    
     if(getDebugMode())
            {
                    ###We mogen de exception terug geven zoals normaal
                    throw $ex;
            }
            else
            {            
                    echo 'serverfout';
            }
            

}

set_exception_handler("CC_Exception_Handler");
?>