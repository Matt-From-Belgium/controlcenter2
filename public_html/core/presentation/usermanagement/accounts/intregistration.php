<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";

###Om deze pagina te kunnen gebruiken moet de gebruiker het toegangsniveau usermanagement::add users
checkPermission('usermanagement','add users');


		##Is dit de eerst keer dat het formulier wordt weergegeven of werden er reeds gegevens verstuurd
		#naar de server?

		if(isset($_POST['submit']))
		{
			#ValidateUser() controleert de gebruiker en schrijft deze weg naar de database als 
			#de gegevens correct zijn. 
			#Wanneer errors een array is betekent het dat er fouten zijn opgetreden
			#Wanneer errors de waarde true heeft dan is de gebruiker toegevoegd.
			$errors = AddUserINT($_POST);		
		}
		
		#Het formulier moet enkel worden weergegeven wanneer er nog geen input werd gedetecteerd of
		#wanneer er input met fouten is.
		if((!isset($_POST['submit'])) or ((is_array($errors))))
		{
			$html = new htmlpage("backend");
		
			$html->setVariable("errorlist",$errors);

		
			#De waarden die eventueel bij een eerste foutieve ingave werden ingevoerd moeten hier opnieuw
			#doorgegeven worden zodat de gebruiker het formulier kan aanvullen.
			$html->setVariable("userid",-1);
			
			if(isset($_POST['passwordchangerequired']))
			{
				$html->setVariable("passwordchangerequired","CHECKED");
			}
			else
			{
				$html->setVariable("passwordchangerequired","");
			}
			
                   if(isset($_POST['submit']))
                   {
			$html->setVariable("username",$_POST['username']);
                        
                        if($_POST['password1'] == 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e')
                        {
                            echo "ok";
                            ###Er moet  opnieuw gehashed worden want er zal nieuwe ingave gebeuren
                            $html->setVariable("phash",'1');
                            $html->setVariable("password1","");
                            $html->setVariable("password2","");
                        }
                        else
                        {
                            ###Als de waarde van het wachtwoord niet overeenkomt met de hash van null dan moet een waarde teruggegeven worden
                            $html->setVariable("password1",$_POST['password1']);
                            $html->setVariable("password2",$_POST['password2']);
                            #wachtwoord had een waarde dus deze wordt teruggegeven, ze moet niet opnieuw gehasht worden
                            #Als de gebruiker de velden toch wil aanpassen zal het javascript hashing opnieuw activeren
                            #als een hacker dat proces zou tegengaan maakt dat niet uit dan zal de account mogelijk onbruikbaar blijken
                            $html->setVariable("phash",'0');
                        }
                        
                        
			$html->setVariable("mail",$_POST['mail']);
			$html->setVariable("firstname",$_POST['firstname']);
			$html->setVariable("lastname",$_POST['lastname']);
                        
                        
			
                   }
                   else
                   {
                       #DE variabele phash is een indicator voor het javascript dat het wachtwoord niet
                        #nog eens gehashed moet worden. Hier gaat het om eerste weergave => hashen is nodig
                        $html->setVariable("phash",'1');
                   }
                   
			$html->LoadAddin("/core/presentation/usermanagement/accounts/addins/intregform.tpa");
                        $html->loadScript('/core/logic/usermanagement/hashpwd.js');
                        $html->loadScript('/core/logic/usermanagement/hash.js');
	
			#De Addin intregform bevat een loop met als parameter de array countrylist
			#deze moet dus gedefinieerd worden.
			
	
			
			
			###De gebruikersgroepen moeten opgehaald worden
			$usergrouplist = getUsergroups();
			
			###Als het gaat om een herpost moeten de vakjes aangekruist blijven
			if(is_array($_POST['usergroups']))
			{
				foreach($usergrouplist as $key=>$usergroup)
				{
					if(in_array($usergroup['id'],$_POST['usergroups']))
					{
						$usergrouplist[$key]['checkedflag']="checked";
					}
				}
			}
			
			$html->setVariable("usergrouplist",$usergrouplist);	
		
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

?>
