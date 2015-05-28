<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/presentation/general/commonfunctions.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use ReCaptcha\Captcha;
###Eerst wordt er nagegaan of het de eerste keer is dat de pagina wordt weergegeven.
if(!isset($_POST['submit']))
{
	if(isset($_GET['id']))
	{
            
	###Eerst worden de gegevens van de gebruiker opgehaald
                        $captcha = new Captcha();
                        $captcha->setPublicKey(getCaptchaPublicKey());
                        
			$user = getUser($_GET['id']);
			$html = new htmlpage("frontend");
                        $html->loadScript('/core/logic/usermanagement/hashpwd.js');
                        $html->loadScript('/core/logic/usermanagement/hash.final.js');
			$html->LoadAddin("/core/presentation/usermanagement/accounts/addins/activate.tpa");
		
			###De activatiepagina maakt gebruik van reCaptcha. De publieke sleutel moet worden
			###ingelezen om de captcha correct te doen werken
			
			$html->setVariable("captchacode",$captcha->displayHTML());
			$html->setVariable("sitename",getSiteName());
		
			$html->PrintHTML();

	
	}
	else
	{
	}
}
else
{
	###$_POST['submit'] heeft een waarde => er is data om te verwerken
	
	###Controle op de captcha
		
		#De private sleutel wordt opgehaald om de controle mogelijk te maken
		$captcha = new Captcha();
                $captcha->setPrivateKey(getCaptchaPrivateKey());
		
		
		if($captcha->isValid())
		{
			###De gebruiker heeft een correct antwoord gegeven op de captcha
			###Controle of de gebruikersnaam en wachtwoord correct is
			require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";
			
			###de gebruikersnaam moet overeenkomen met de gebruikersnaam van het aangeleverde id: een gebruiker kan zijn account
			###enkel met zijn eigen link activeren
			$user = getUser($_GET['id']);

			if(strtolower($user->getUsername()) == strtolower($_POST['username']))
			{
                                
				if(checkUserPassword($_POST['username'],$_POST['password']))
				{
					
					if(UserSelfActivation($user))
					{
						if(getAdminActivationParameter())
						{
							###Ook de administrator moet nog toestemming geven
							/*$html = new htmlpage("frontend");
							$html->LoadAddin('/core/presentation/general/addins/message.tpa');
							$html->setVariable("messagetitle",LANG_USER_SELF_ACTIVATION_ADMIN_NEEDED_TITLE);
							$html->setVariable("message",LANG_USER_SELF_ACTIVATION_ADMIN_NEEDED_BODY);
							$html->setVariable("link","/");
							$html->setVariable("linktext",LANG_USER_SELF_ACTIVATION_ADMIN_NEEDED_LINK);
							$html->PrintHTML();*/
						
							showMessage(LANG_USER_SELF_ACTIVATION_ADMIN_NEEDED_TITLE,LANG_USER_SELF_ACTIVATION_ADMIN_NEEDED_BODY,"/",LANG_USER_SELF_ACTIVATION_ADMIN_NEEDED_LINK);
						}
						else
						{
							###De account is bruikbaar
							/*$html = new htmlpage("frontend");
							$html->LoadAddin('/core/presentation/general/addins/message.tpa');
							$html->setVariable("messagetitle",LANG_USER_SELF_ACTIVATION_DONE_TITLE);
							$html->setVariable("message",LANG_USER_SELF_ACTIVATION_DONE_BODY);
							$html->PrintHTML();*/
							showMessage(LANG_USER_SELF_ACTIVATION_DONE_TITLE,LANG_USER_SELF_ACTIVATION_DONE_BODY);
						}
					}
				}
                                else
                                {
                                    echo 'gebruikersnaam wachtwoord nt ok';
                                }
			}
			else
			{
				echo "je moet met je eigen link activeren";
			}
		}
		else
		{
			###De gebruiker heeft geen correct antwoord gegeven op de captcha
			echo "niet ok";
		}
}
?>