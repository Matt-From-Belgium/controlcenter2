<?php
#Sessie wordt  al gestart in userfunctions
#session_start();

require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";



###Dit script gaat na of de aangeleverde parameters correct zijn
###Indien de logingegevens correct zijn worden de gebruikersgegevens opgehaald en opgeslagen in
###De parameters zijn:
###		->u=gebruikersnaam
###		->p=password
###		->d=destination page

###Eerst moet gecontroleerd worden of alle parameters werden opgegeven


###De gebruikersgegevens kunnen nu getest worden.
if(isset($_POST['u']))
{
    #echo "POST heeft waarde";
    $u=$_POST['u'];
    $p=$_POST['p'];
    $d=$_POST['d'];
}

###Als de pagina rechtstreeks wordt geladen wordt het loginscherm onmiddellijk weergegeven. Dit scenario wordt ook uitgevoerd
###Wanneer een gebruiker een pagina probeert te openen die beveiligd is terwijl hij/zij niet ingelogd is.
if((!isset($_POST['u']))&&(!isset($_POST['p']))&&(!isset($_POST['d'])))
{
        ###Het script wordt rechtstreeks ingeladen => loginpagina weergeven.
        $html = new HTMLpage('frontend');
        $html->forceSSL();
        $html->LoadAddin('/core/presentation/usermanagement/accounts/addins/login.tpa');
        $html->loadScript('/core/logic/usermanagement/hash.final.js');
        $html->loadScript('/core/logic/usermanagement/hashpwd.final.js');
    
        ###We geven login links voor sociale netwerken een lege waarde, worden later opgevuld;
        $fbloginlink = null;
    
        if(getFacebookLoginStatus())
        {
            /*require_once $_SERVER['DOCUMENT_ROOT'].'/core/social/facebook/php/facebook.php';
            
            ###Als Facebook login mogelijk is dan genereren we een login link 
            $fbscope = getFacebookScope();
            $config = array();

            $config['appId'] = getFacebookAppID();
            $config['secret'] = getFacebookSappId();
            
            ###Als er een bestemmingspagina werd meegegeven moet deze ook doorgegeven worden
            
            if(getSSLenabled())
            {
                $prefix = 'https://';
            }
            else
            {
                $prefix = 'http://';
            }
            
            $redirect = $prefix.$_SERVER['HTTP_HOST'].'/core/logic/usermanagement/fblogincallback.php?d='.$_GET['d'];
            

            $params = array(
            'scope' => $fbscope,
            'redirect_uri' => $redirect
            );        

            $facebook = new Facebook($config);

            $fbloginlink = $facebook->getLoginUrl($params);
            $html->setVariable('fbloginlink', $fbloginlink);*/
            
            ###Als Facebook login mogelijk is dan genereren we een login link 
          
            if(getSSLenabled())
            {
                $prefix = 'https://';
            }
            else
            {
                $prefix = 'http://';
            }
            
            require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
            
            ###BUGFIX d64 moet enkel toegevoegd worden als er een redirect parameter is
            if(!empty($_GET['d']))
            {
                ###Door een Facebook bug encoderen we de bestemming zodat er geen / en = meer in komen.
                $encodedURL = rtrim(strtr(base64_encode($_GET['d']), '+/', '-_'), '=');

                $redirect = $prefix.$_SERVER['HTTP_HOST'].'/core/logic/usermanagement/fblogincallback.php?d64='.$encodedURL;
            }
            else
            {
                ###Er is geen redirect opgegeven
                $redirect = $prefix.$_SERVER['HTTP_HOST'].'/core/logic/usermanagement/fblogincallback.php';
            }
            
            $helper = new Facebook\FacebookRedirectLoginHelper($redirect, $appId = getFacebookAppID(), $appSecret = getFacebookSappId());
            
            ###We halen de gewenste scope op
            $fbscope = getFacebookScope();
            $fbscopeArray = explode(',', $fbscope);
            
            $fbloginlink = $helper->getLoginUrl($fbscopeArray);
            
            $html->setVariable('fbloginlink', $fbloginlink);
            
        }
        
        
    
	
	###Als het script rechtstreeks wordt geladen dan wordt de gebruiker gewoon terug naar de hoofdpagina geleid. Als een ander script
	###doorverwijst naar deze pagina (bijvoorbeeld wanneer er moet ingelogd worden om een pagina te bekijken dan kan de waarde $_GET['d']
	###ingesteld worden om de bestemmingspagina aan te passen.

	if(empty($_GET['d']))
	{
	$html->setVariable("d",'/');	
	}
	else
	{
	$html->setVariable("d",$_GET['d']);		
	}

	$html->printHTML();
}
else
{
        $loginreturn=Login(strtolower($u),$p,$d);
	###Er is logininformatie beschikbaar.
	if(!is_array($loginreturn))
	{
		###De loginprocedure is geslaagd, $_SESSION['currentuser'] is aangemaakt
		header("Location: $d");
	}
	else
	{
		###De loginprocedure is niet geslaagd, de gebruiker wordt naar de loginpagina gebracht.
                #De foutboodschap komt vanuit de logiclayer terug in een array.
		$html = new HTMLpage('frontend');
                $html->forceSSL();
		$html->LoadAddin('/core/presentation/usermanagement/accounts/addins/login.tpa');
                $html->loadScript('/core/logic/usermanagement/hash.final.js');
                $html->loadScript('/core/logic/usermanagement/hashpwd.final.js');
	
		###De destination moet meegekopieerd worden zodat een gebruiker bij de 2e poging nog altijd doorgaat naar
		###de bedoelde bestemmingspagina
		$html->setVariable("errorlist",$loginreturn);
		$html->setVariable("d",$d);
	
		$html->printHTML();
	}
}
?>