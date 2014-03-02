<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/ajax/ajax.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/social/facebook/php/facebook.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/parameters.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';


function checkFBAccount()
{
    ###Eerst kijken we of er wel een ingelogde gebruiker is.
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
            ###Er is dus reeds een ingelogde gebruiker. We moeten dus niks doen.
        
            ###MAAR: als de huidige gebruiker nog geen facebookaccount gekoppeld heeft
            #doen we dat nu. Wanneer een gebruiker geen basistoegang heeft gegeven
            #komen we niet in dit codepad want dan zal de javascriptcode dit script niet aanroepen
            $userfbid = $_SESSION['currentuser']->getFacebookID();
            if(empty($userfbid))
            {
               
                
               ###We halen het facebook ID op
               $config['appId'] = getFacebookAppID();
               $config['secret'] = getFacebookSappId();
               $facebook = new Facebook($config);
               $fbUser = $facebook->getUser();
               
               ###en we voegen het toe aan de gebruiker
               $editeduser = $_SESSION['currentuser'];
               $editeduser->setFacebookID($fbUser);
               
               ###En we schrijven de gewijzigde gebruiker naar de database
               $errormessages=editUserObject($editeduser);
               if(is_array($errormessages))
               {
                   $arrayoutput = print_r($errormessages,true);
                   throw new Exception('Facebookaccount kon niet automatisch gekoppeld worden. De inhoud van errormessages is $arrayoutput');
               }
            }
            else {
                $currentuser = $_SESSION['currentuser'];
                $currentuser = print_r($currentuser, TRUE);
                throw new Exception("fbid is gelijk aan $currentuser");
            }
        
            $resultnegative = new ajaxResponse('error');
            $resultnegative->addErrorMessage('error', '3');
            return $resultnegative->getXML();
    }
}


?>
