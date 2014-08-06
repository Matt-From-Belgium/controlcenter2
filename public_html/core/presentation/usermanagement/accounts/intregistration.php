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
			$html->setVariable("password",$_POST['password']);
			$html->setVariable("password2",$_POST['password2']);
			$html->setVariable("mail",$_POST['mail']);
			$html->setVariable("firstname",$_POST['firstname']);
			$html->setVariable("lastname",$_POST['lastname']);
			
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
