<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/core/social/facebook/php/facebook.php';
    require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
    require_once $_SERVER['DOCUMENT_ROOT'].'/core/presentation/general/commonfunctions.php';
    
    ###Deze pagina vangt de gebruiker op nadat er op 'login met Facebook' werd geklikt
    ###De gebruiker werd doorgelust naar een pagina van Facebook waar hij/zij toegang heeft gegeven tot de site
    ###Ook wanneer de gebruiker niet akkoord is gegaan komt hij/zij hier terecht
    
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
     
   }
?>