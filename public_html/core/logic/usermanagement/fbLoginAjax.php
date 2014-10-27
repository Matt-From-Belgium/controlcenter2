<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/ajax/ajax.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/parameters.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';


function checkFBAccount()
{
    ###Eerst kijken we of er wel een ingelogde gebruiker is.
    if(!isset($_SESSION['currentuser']))
    {
        ##Er is geen ingelogde gebruiker. We willen kijken of er een actieve FB sessie is en die
        ##doorgeven aan logic layer om in te loggen
        ##Wanneer de gebruiker specifiek kiest voor inloggen met facebook gebeurt dit via redirecthelper
        ##zie fbLogincallback.php
        ##Wanneer we via javascript willen gaan gebruiken we de sessie die via de SDK tot stand werd gebracht
        require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
        Facebook\FacebookSession::setDefaultApplication(getFacebookAppID(), getFacebookSappId());

        $helper = new Facebook\FacebookJavaScriptLoginHelper();

        ###We halen de javascript sessie op
        $session = $helper->getSession();
        
        ###Via de logic layer halen we de gebruikersgegevens op
        ###En als er een gebruikersaccount is koppelen we die aan $_SESSION['currentuser']
        $errorcode = Login_FB($session);

        if(isset($_SESSION['currentuser']))
        {
            $result = new ajaxResponse('ok');

            ###nalv update naar nieuwe API: geen idee waarom we hier nog eens
            ###het facebook id ophalen, vervangen door userid.
            /*###Eerst kijken of er een Facebook sessie is
            $config = array();

            $config['appId'] = getFacebookAppID();
            $config['secret'] = getFacebookSappId();

            $facebook = new Facebook($config);

            $fbUser = $facebook->getUser();

            $result->addField('userId');

            $row = array();
            $row['userId']=$fbUser;

            $result->addData($row);*/

            $result->addField('userId');
            
            $row = array();
            $row['userId']=$_SESSION['currentuser']->getId();
            
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

               /*###We halen het facebook ID op
               $config['appId'] = getFacebookAppID();
               $config['secret'] = getFacebookSappId();
               $facebook = new Facebook($config);
               $fbUser = $facebook->getUser();
               */
                
                require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
                Facebook\FacebookSession::setDefaultApplication(getFacebookAppID(), getFacebookSappId());

                $helper = new Facebook\FacebookJavaScriptLoginHelper();

                ###We halen de javascript sessie op
                $session = $helper->getSession();
                
                $user_profile = (new Facebook\FacebookRequest(
                $session, 'GET', '/me'
              ))->execute()->getGraphObject(Facebook\GraphUser::className());
                
                $fbUser=$user_profile->getId();
                
               ###en we voegen het toe aan de gebruiker
               $editeduser = $_SESSION['currentuser'];
               $editeduser->setFacebookID($fbUser);
                
                
               
               ###En we schrijven de gewijzigde gebruiker naar de database
               $errormessages=editUserObject($editeduser);
               if(is_array($errormessages))
               {
                   $arrayoutput = print_r($errormessages,true);
                   throw new Exception("Facebookaccount kon niet automatisch gekoppeld worden. De inhoud van errormessages is $arrayoutput");
               }
            }

        
            $resultnegative = new ajaxResponse('error');
            $resultnegative->addErrorMessage('error', '3');
            return $resultnegative->getXML();
    }
}


?>
