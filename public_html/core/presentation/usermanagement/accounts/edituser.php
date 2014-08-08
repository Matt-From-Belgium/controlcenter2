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
					

					/*$html->setVariable("password1",$_POST['password1']);
					$html->setVariable("password2",$_POST['password2']);*/
                                        
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
                                                    //echo "ok";
                                                    ###Als de waarde van het wachtwoord niet overeenkomt met de hash van null dan moet een waarde teruggegeven worden
                                                    $html->setVariable("password1",$_POST['password1']);
                                                    $html->setVariable("password2",$_POST['password2']);
                                                    $html->setVariable("phash",'0');
                                                    #wachtwoord had een waarde dus deze wordt teruggegeven, ze moet niet opnieuw gehasht worden
                                                    #Als de gebruiker de velden toch wil aanpassen zal het javascript hashing opnieuw activeren
                                                    #als een hacker dat proces zou tegengaan maakt dat niet uit dan zal de account mogelijk onbruikbaar blijken
                                                    
                                                }
                                                else
                                                {
                                                    
                                                    ###Het gaat hier om eerste presentatie
                                                    #Aangezien we een bestaande gebruiker editen mogen de velden blanco blijven
                                                    $html->setVariable("phash",'0');
                                                }
                                            }
                                        
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
					
				}
		
				
				
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
                                        $html->loadScript('/core/logic/usermanagement/hashpwd.js');
                                        $html->loadScript('/core/logic/usermanagement/hash.js');
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