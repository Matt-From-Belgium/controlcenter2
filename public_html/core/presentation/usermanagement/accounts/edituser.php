<?php
###De gebruiker moet permission usermanagement::edit users hebben.
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';
checkPermission('usermanagement','edit users');

	###Om te weten om welke gebruiker het gaat moeten we eerst het id hebben van de te wijzigen gebruiker.
	if(isset($_GET['id']))
	{
		###Dit script past de gegevens van een bestaande gebruiker aan.
		###Kijken of dit de eerste keer is dat het formulier geladen wordt, maw is er input?
		if(isset($_POST['submit']))
		{
		require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";
		$errormessages = editUser($_POST);
		}
		
		if((!isset($_POST['submit'])) or (is_array($errormessages)))
		{
			require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
			require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";
			require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/countryfunctions.php";

			##De gebruikersgegevens moeten worden opgehaald. De functie getUser geeft een gebruikersobject terug
			##of false als er geen gebruiker werd gevonden.
			$userid = $_GET['id'];
	
			if($usertoedit = getUser($userid))
			{	

				$html = new htmlpage("backend");

				###om ervoor te zorgen dat de validator een id heeft wordt hier het id van de gebruiker opgegeven.
				$html->setVariable("userid",$_GET['id']);
			
				###De waarden van de gebruiker moeten worden weergegeven
				###MAAR: als dit niet de eerste keer is dat de gegevens worden doorgestuur moeten de waarden van
				###$_POST worden getoond.
				if(isset($errormessages))
				{
					if($_POST['passwordchangerequired'])
					{
						$html->setVariable("passwordchangerequired","CHECKED");
					}
					
					$html->setVariable("username",$usertoedit->getUsername());
					$html->setVariable("mail",$_POST['mail']);
					$html->setVariable("firstname",$_POST['firstname']);
					$html->setVariable("lastname",$_POST['lastname']);
					$html->setVariable("website",$_POST['website']);

					$html->setVariable("password",$_POST['password']);
					$html->setVariable("password2",$_POST['password2']);
					$html->setVariable("errorlist",$errormessages);
				}
				else
				{
					$html->setVariable("username",$usertoedit->getUsername());
					
					if($usertoedit->getPasswordchangeRequired()==1)
					{
						$html->setVariable("passwordchangerequired","CHECKED");
					}
					
					$html->setVariable("mail",$usertoedit->getMailAdress());
					$html->setVariable("firstname",$usertoedit->getRealFirstName());
					$html->setVariable("lastname",$usertoedit->getRealName());
					$html->setVariable("website",$usertoedit->getWebsite());
				}
		
				$clist = getCountries();
		
						foreach($clist as $key=>$value)
						{
							$newitem ="";
							$newitem['countrycode']=$value;
						
							##Als het land het land is dat geselecteerd werd dan moet dit opnieuw als geselecteerd
							##worden weergegeven, dit kan door de selectionflag loopvariable te gebruiken
							if($value == $usertoedit->getCountry())
							{
								$newitem['selectionflag'] = "selected";
							}
					
							$countrylist[]=$newitem;
						}
		
				$html->setVariable("countrylist",$countrylist);
				
			###De gebruikersgroepen moeten opgehaald worden
			$usergrouplist = getUsergroups();
			
			
			if(is_array($_POST['usergroups']))
			{
				###Als het gaat om een herpost moeten de vakjes aangekruist blijven
				foreach($usergrouplist as $key=>$usergroup)
				{
						if(in_array($usergroup['id'],$_POST['usergroups']))
						{
						$usergrouplist[$key]['checkedflag']="checked";
						}
				}
			}

			else
			{
				###Als het niet gaat om een herpost dan moeten de vakjes aangekruist worden zoals ze in de database
				###opgeslagen zitten
				if(is_array($usertoedit->getUsergroups()))
				{
					foreach($usergrouplist as $key=>$usergroup)
					{
							if(in_array($usergroup['id'],$usertoedit->getUsergroups()))
							{
								$usergrouplist[$key]['checkedflag']="checked";
							}
					}
				}
			}
			
				$html->setVariable("usergrouplist",$usergrouplist);	

					$html->LoadAddin("/core/presentation/usermanagement/accounts/addins/edituser.tpa");
					$html->printHTML();
				
			}
			else
			{
				echo "that userid does not exist";
			}
		}
		else
		{
			require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
			$html = new htmlpage("backend");
			$html->LoadAddin("/core/presentation/general/addins/message.tpa");
			$html->setVariable("messagetitle",LANG_USER_EDITED_TITLE);
			$html->setVariable("message",LANG_USER_EDITED);
			$html->printHTML();
		}
	}
	else
	{
		echo "you must specify a userid";
	}
?>