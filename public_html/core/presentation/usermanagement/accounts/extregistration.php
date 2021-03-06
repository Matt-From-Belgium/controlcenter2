<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/core/presentation/general/commonfunctions.php';

###Enkel wanneer CORE_USER_EXT_REGISTRATION gelijk is aan 1 mag registratie mogelijk zijn
if(getSelfRegisterStatus())
{
		##Is dit de eerst keer dat het formulier wordt weergegeven of werden er reeds gegevens verstuurd
		#naar de server?

		if(isset($_POST['submit']))
		{
                    ###Wanneer er met Facebook geregistreerd wordt kunnen we dat herkennen aan de waarde 
                    #$_POST['facebookid']
                    if(empty($_POST['facebookid']))
                    {
                        #Facebookid heeft geen waarde => normale loginprocedure
                        
			#ValidateUser() controleert de gebruiker en schrijft deze weg naar de database als 
			#de gegevens correct zijn. 
			#Wanneer errors een array is betekent het dat er fouten zijn opgetreden
			#Wanneer errors de waarde true heeft dan is de gebruiker toegevoegd.

			$errors = AddUserEXT($_POST);		
                    }
                    else
                    {
                        ###We gaan proberen om de account via facebook te creëren
                        ###We hebben eerst een FB sessie nodig.
                        ###Ofwel komt die uit javascript, ofwel is er een token meegeleverd vanuit redirect
                        require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
                        Facebook\FacebookSession::setDefaultApplication(getFacebookAppID(), getFacebookSappId());

                        if(!isset($_GET['t']))
                        {
                            $helper = new Facebook\FacebookJavaScriptLoginHelper();

                            ###We halen de javascript sessie op
                            $session = $helper->getSession();
                        }
                        else {
                            $session = new Facebook\FacebookSession($_GET['t']);
                        }
                        
                        $errors = addUserEXT_FB($_POST,$session);
                    }
                    
		}
		
		#Het formulier moet enkel worden weergegeven wanneer er nog geen input werd gedetecteerd of
		#wanneer er input met fouten is.
		if((!isset($_POST['submit'])) or ((is_array($errors))))
		{
                    
			$html = new htmlpage("frontend");
		
			$html->setVariable("errorlist",$errors);

                        ###De variabele fbintegration zal ervoor zorgen dat de link voor facebooklogin zichtbaar is
                        ###maar als $_GET['fb']=1 dan komt de gebruiker uit het inlogscherm en werd er al gekozen voor login met facebook
                        ###We moeten de link dan niet nog eens tonen, dat schept verwarring
                        if((getFacebookLoginStatus() && !isset($_GET['fb'])) )
                        {
                        $html->setVariable('fbintegration', true);
                        }             

                        if(isset($_GET['t']) || isset($_POST['facebookid']))
                        {
                            $html->setVariable('serversideFB', 'trie');
                        }
                        
                        $html->setVariable("userid",-1);
			$html->setVariable("username",$_POST['username']);

                        if($_POST['password1'] == 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e')
                        {
                            
                            ###Er moet  opnieuw gehashed worden want er zal nieuwe ingave gebeuren
                            $html->setVariable("phash",'1');
                            $html->setVariable("password1","");
                            $html->setVariable("password2","");
                        }
                        else
                        {
                            if($_POST['submit'])
                            {
                                ###Als de waarde van het wachtwoord niet overeenkomt met de hash van null dan moet een waarde teruggegeven worden
                                $html->setVariable("password1",$_POST['password1']);
                                $html->setVariable("password2",$_POST['password2']);
                                #wachtwoord had een waarde dus deze wordt teruggegeven, ze moet niet opnieuw gehasht worden
                                #Als de gebruiker de velden toch wil aanpassen zal het javascript hashing opnieuw activeren
                                #als een hacker dat proces zou tegengaan maakt dat niet uit dan zal de account mogelijk onbruikbaar blijken
                                $html->setVariable("phash",'0');
                            }
                            else
                            {
                                ###Het gaat hier om eerste presentatie
                                #DE variabele phash is een indicator voor het javascript dat het wachtwoord niet
                                #nog eens gehashed moet worden. Hier gaat het om eerste weergave => hashen is nodig
                                $html->setVariable("phash",'1');
                            }
                        }
                        
                        
			$html->setVariable("mail",$_POST['mail']);
			$html->setVariable("firstname",$_POST['firstname']);
			$html->setVariable("lastname",$_POST['lastname']);
                        $html->setVariable('facebookid', $_POST['facebookid']);
                        
                        ###als fb=1 zullen er automatisch gegevens ingevuld worden in het formulier
                        #Echter, manueel ingevulde waarden hebben voorrang
                        if(getFacebookLoginStatus() and (($_GET['fb']==1) or (isset($_POST['facebookid']))))
                        {
                            
                            ###Als dit 2e invoer is zal er door de eerdere code al een facebooksessie zijn 
                            
                            
                            require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
                            Facebook\FacebookSession::setDefaultApplication(getFacebookAppID(), getFacebookSappId());
                            
                            
                                if(isset($_GET['t']))
                                {
                                    $token = $_GET['t'];
                                    $session = new Facebook\FacebookSession($token);
                                }
                                else
                                {
                                    /*$helper = new Facebook\FacebookJavaScriptLoginHelper();
                                    $session = $helper->getSession();*/
                                }
                            
                            
                            $user_profile = (new Facebook\FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(Facebook\GraphUser::className());
                            
                            if($user_profile)
                            {
                                $html->setVariable('facebookid',$user_profile->getId());
                                $html->setVariable('firstname',$user_profile->getFirstName());
                                $html->setVariable('lastname',$user_profile->getLastName());
                                
                                if(!isset($_POST['username']))
                                {
                                    $html->setVariable("username",$user_profile->getFirstName().' '.$user_profile->getLastName());
                                }

                                if(!isset($_POST['mail']))
                                {
                                   $html->setVariable('mail', $user_profile->getProperty('email'));
                                }
                            }
                            /*$config = array();

                            $config['appId'] = getFacebookAppID();
                            $config['secret'] = getFacebookSappId();

                            $facebook = new Facebook($config);
                            
                            if($facebook->getUser())
                            {
                                    $userdetails = $facebook->api('/me','GET');
                                    
                                    $html->setVariable('facebookid',$userdetails['id']);
                                    $html->setVariable('firstname',$userdetails['first_name']);
                                    $html->setVariable('lastname',$userdetails['last_name']);
                                    
                                    if(!isset($_POST['username']))
                                    {
                                        $html->setVariable("username",$userdetails['first_name'].' '.$userdetails['last_name']);
                                    }
                                    
                                    if(!isset($_POST['mail']))
                                    {
                                       $html->setVariable('mail', $userdetails['email']);
                                    }
                                    
                                    
                            }*/
                        }
                        
                        
			$html->LoadAddin("/core/presentation/usermanagement/accounts/addins/extregform.tpa");
                        $html->loadScript('/core/logic/usermanagement/hashpwd.final.js');
                        $html->loadScript('/core/logic/usermanagement/hash.final.js');
                        $html->loadScript('/core/presentation/usermanagement/accounts/fbRegister.final.js');
		
			$html->PrintHTML();
		}
		else
		{
			###De input was correct, er moet enkel een bevestigingspagina worden weergegeven.
			/*$html = new htmlpage("backend");
			$html->LoadAddin("/core/presentation/general/addins/message.tpa");
			$html->setVariable("messagetitle",LANG_USER_ADDED_TITLE);
			$html->setVariable("message",LANG_USER_ADDED);
			$html->PrintHTML();
                        */
                    
                        ###We moeten de gebruiker nu informeren of wat er verder moet gebeuren
                        ###In een aantal gevallen moet de gebruiker zijn/haar account bevestigen
                        ###In een aantal gevallen moet de admin goedkeuren.
                    
                        $message = LANG_USER_EXT_ADDED;
                        
                        if(getAdminActivationParameter())
                        {
                            $message .= LANG_USER_EXT_ADMIN_CHECK;
                        }
                        
                        if(getUserActivationParameter() && !isset($_POST['facebookid']))
                        {
                            $message .= LANG_USER_EXT_USER_CHECK;
                        }
                        
                        if(isset($_GET['d']))
                        {
                            showMessage(LANG_USER_EXT_ADDED_TITLE,$message,$_GET['d'],LANG_USER_EXT_CONTINUE);
                        }
                        else
                        {
                            showMessage(LANG_USER_EXT_ADDED_TITLE,$message,'/',LANG_USER_EXT_CONTINUE);
                        }
		}
}
else
{
	showMessage(LANG_ERROR_EXTREG_DISABLED_HEADER, LANG_ERROR_EXTREG_DISABLED_MESSAGE);
}
?>
