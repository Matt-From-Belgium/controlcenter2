<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
    require_once $_SERVER['DOCUMENT_ROOT'].'/core/presentation/general/commonfunctions.php';
    
    ###Deze pagina vangt de gebruiker op nadat er op 'login met Facebook' werd geklikt
    ###De gebruiker werd doorgelust naar een pagina van Facebook waar hij/zij toegang heeft gegeven tot de site
    ###Ook wanneer de gebruiker niet akkoord is gegaan komt hij/zij hier terecht
   
    ###We komen naar dit script vanuit de redirect url op Facebook
    require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
    Facebook\FacebookSession::setDefaultApplication(getFacebookAppID(), getFacebookSappId());
    
    #use Facebook\FacebookSession;
    #Facebook\FacebookSession::setDefaultApplication(getFacebookAppID(), getFacebookSappId());
    
    ###We creëren een redirect url terwijl we die niet nodig hebben
    #Facebook klasse vereist dit...
    #en dan moet die nog identiek zijn ook.
    if(getSSLenabled())
            {
                $prefix = 'https://';
            }
            else
            {
                $prefix = 'http://';
            }
    
    ##BUGFIX: als d64 niet meegeleverd was in de redirect_uri mag hij er hier ook niet bij staan want 
    #$redirect moet gelijk zijn aan de $redirect dat we doorgaven in login.php
    if(!empty($_GET['d64']))
    {
        $redirect = $prefix.$_SERVER['HTTP_HOST'].'/core/logic/usermanagement/fblogincallback.php?d64='.  $_GET['d64'];
        $decodedurl = base64_decode(str_pad(strtr($_GET['d64'], '-_', '+/'), strlen($_GET['d64']) % 4, '=', STR_PAD_RIGHT));
    }
    else
    {
        $redirect = $prefix.$_SERVER['HTTP_HOST'].'/core/logic/usermanagement/fblogincallback.php';
    }
    
    
    
    $helper = new Facebook\FacebookRedirectLoginHelper($redirect, $appId = getFacebookAppID(), $appSecret = getFacebookSappId());
    
    $fbSession = $helper->getSessionFromRedirect();
    #print_r($fbSession);
   
    if($fbSession)
    {
        #Er is een access token
        ###De gebruiker heeft zijn Facebook account gekoppeld
        ###Nu gaan we proberen om de gebruiker in te loggen
        ###Dat kan natuurlijk enkel als er op onze server een gebruiker gekoppeld zit aan 
        ###Die Facebook account
        
        ###Resultcode krijgt bij succesvolle login het userid, maar belangrijker is dat er dan een
        #$_SESSION['currentuser'] is
        #als die er niet is zal resultcode een errorcode zijn
        #1: er is geen gebruikersaccount gekoppeld aan het FB-id
        #2: er is wel een account gekoppeld maar die moet geactiveerd worden door de admin
        $resultcode=login_FB($fbSession);
        
                if($_SESSION['currentuser'])
                {
                    ###login is geslaagd, we mogen een redirect uitvoeren naar de bestemmingspagina
                    $header = $decodedurl;
                    header('location: '.$header);
                }
                else
                {
                    ###login is niet geslaagd, nu gaan de kijken naar $resultcode
                    switch($resultcode)
                    {
                        case 1:
                            ###Er is nog geen gebruikersaccount aan deze facebook account gekoppeld
                            ###Als EXT registration aanstaat mag de gebruiker een nieuwe account aanmaken
                            if(getSelfRegisterStatus())
                            {
                                ###De gebruiker mag zichzelf registreren
                                if(isset($decodedurl))
                                {
                                    $location = '/core/presentation/usermanagement/accounts/extregistration.php?fb=1&d='.$decodedurl.'&t='.$fbSession->getAccessToken();
                                }
                                else
                                {
                                    $location = '/core/presentation/usermanagement/accounts/extregistration.php?fb=1&t='.$fbSession->getAccessToken();
                                }
                                
                                header('location: '.$location);
                            }
                            else {
                                ###Gebruiker mag geen account maken, we kunnen niets doen
                                showMessage(LANG_ERROR_EXTREG_DISABLED_HEADER, LANG_ERROR_EXTREG_DISABLED_MESSAGE);
                            }

                            break;
                        case 2:
                                        ###De gebruiker heeft zijn account wel geactiveerd maar de admin moet nog zijn/haar toestemming geven
                                        require_once $_SERVER['DOCUMENT_ROOT']."/core/presentation/general/commonfunctions.php";
                                        showMessage("Admin moet nog activeren","Uw account is nog niet bruikbaar. Een administrator zal uw gegevens nakijken en uw account activeren. Wij streven ernaar om alle accounts binnen de 24 uur te activeren");
                                        exit();
                            break;
                    }
                }
    }
    else 
    {
      ###Gebruiker is niet akkoord gegaan met de toegang
      ###Dus terug naar de loginpagina
     $header = 'location: /core/presentation/usermanagement/accounts/login.php?d='.$_GET['d'];
     
     header($header);
     
    }
   
    /*
    ###initialisatie van de PHP SDK
    $config = array();

        $config['appId'] = getFacebookAppID();
        $config['secret'] = getFacebookSappId();
        
        $facebook = new Facebook($config);
    
    ###Eerst moeten we dus nagaan of de gebruiker ons toegang heeft verleend
    if($facebook->getUser())
    {
        ###De gebruiker heeft zijn Facebook account gekoppeld
        ###Nu gaan we proberen om de gebruiker in te loggen
        ###Dat kan natuurlijk enkel als er op onze server een gebruiker gekoppeld zit aan 
        ###Die Facebook account
        
        ###Resultcode krijgt bij succesvolle login het userid, maar belangrijker is dat er dan een
        #$_SESSION['currentuser'] is
        #als die er niet is zal resultcode een errorcode zijn
        #1: er is geen gebruikersaccount gekoppeld aan het FB-id
        #2: er is wel een account gekoppeld maar die moet geactiveerd worden door de admin
        
        $resultcode = Login_FB();
        
        if($_SESSION['currentuser'])
        {
            ###login is geslaagd, we mogen een redirect uitvoeren naar de bestemmingspagina
            $header = $_GET['d'];
            header('location: '.$header);
        }
        else
        {
            ###login is niet geslaagd, nu gaan de kijken naar $resultcode
            switch($resultcode)
            {
                case 1:
                    ###Er is nog geen gebruikersaccount aan deze facebook account gekoppeld
                    ###Als EXT registration aanstaat mag de gebruiker een nieuwe account aanmaken
                    if(getSelfRegisterStatus())
                    {
                        ###De gebruiker mag zichzelf registreren
                        $location = '/core/presentation/usermanagement/accounts/extregistration.php?fb=1&d='.$_GET['d'];
                        header('location: '.$location);
                    }
                    else {
                        ###Gebruiker mag geen account maken, we kunnen niets doen
                        showMessage(LANG_ERROR_EXTREG_DISABLED_HEADER, LANG_ERROR_EXTREG_DISABLED_MESSAGE);
                    }
                    
                    break;
                case 2:
                                ###De gebruiker heeft zijn account wel geactiveerd maar de admin moet nog zijn/haar toestemming geven
				require_once $_SERVER['DOCUMENT_ROOT']."/core/presentation/general/commonfunctions.php";
				showMessage("Admin moet nog activeren","Uw account is nog niet bruikbaar. Een administrator zal uw gegevens nakijken en uw account activeren. Wij streven ernaar om alle accounts binnen de 24 uur te activeren");
				exit();
                    break;
            }
        }
        

        echo $facebook->getUser();
    }

 else {
      ###Gebruiker is niet akkoord gegaan met de toegang
      ###Dus terug naar de loginpagina
     $header = 'location: /core/presentation/usermanagement/accounts/login.php?d='.$_GET['d'];
     
     header($header);
     
   }*/
?>