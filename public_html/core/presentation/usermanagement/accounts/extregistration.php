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
                        $errors = addUserEXT_FB($_POST);
                    }
                    
		}
		
		#Het formulier moet enkel worden weergegeven wanneer er nog geen input werd gedetecteerd of
		#wanneer er input met fouten is.
		if((!isset($_POST['submit'])) or ((is_array($errors))))
		{
                        require_once $_SERVER['DOCUMENT_ROOT'].'/core/social/facebook/php/facebook.php';
                    
			$html = new htmlpage("frontend");
		
			$html->setVariable("errorlist",$errors);

                        ###De variabele fbintegration zal ervoor zorgen dat de link voor facebooklogin zichtbaar is
                        if(getFacebookLoginStatus())
                        {
                        $html->setVariable('fbintegration', true);
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
                                echo "ok";
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
                            $config = array();

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
                                    
                                    
                            }
                        }
                        
                        
			$html->LoadAddin("/core/presentation/usermanagement/accounts/addins/extregform.tpa");
                        $html->loadScript('/core/logic/usermanagement/hashpwd.js');
                        $html->loadScript('/core/logic/usermanagement/hash.js');

		
			$html->PrintHTML();
		}
		else
		{
			###De input was correct, er moet enkel een bevestigingspagina worden weergegeven.
			$html = new htmlpage("backend");
			$html->LoadAddin("/core/presentation/general/addins/message.tpa");
			$html->setVariable("messagetitle",LANG_USER_ADDED_TITLE);
			$html->setVariable("message",LANG_USER_ADDED);
			$html->PrintHTML();
		}
}
else
{
	showMessage(LANG_ERROR_EXTREG_DISABLED_HEADER, LANG_ERROR_EXTREG_DISABLED_MESSAGE);
}
?>
