<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/ajax/ajax.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/social/facebook/php/facebook.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/parameters.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';


function checkFBAccount()
{
    ###De hele oefening is maar nodig als er nog geen ingelogde gebruiker is
    if(!isset($_SESSION['currentuser']))
    {
        ###Via de logic layer halen we de gebruikersgegevens op
        ###En als er een gebruikersaccount is koppelen we die aan $_SESSION['currentuser']
        $errorcode = Login_FB();

        if(isset($_SESSION['currentuser']))
        {
            $result = new ajaxResponse('ok');

            ###Eerst kijken of er een Facebook sessie is
            $config = array();

            $config['appId'] = getFacebookAppID();
            $config['secret'] = getFacebookSappId();

            $facebook = new Facebook($config);

            $fbUser = $facebook->getUser();

            $result->addField('userId');

            $row = array();
            $row['userId']=$fbUser;

            $result->addData($row);


            return $result->getXML();
        }
        else
        {
            $resultnegative = new ajaxResponse('error');


                $resultnegative->addErrorMessage('error', $errorcode);


            return $resultnegative->getXML();
        }
    }
    else 
    {
            $resultnegative = new ajaxResponse('error');


            $resultnegative->addErrorMessage('error', '3');


            return $resultnegative->getXML();
    }
}


?>
