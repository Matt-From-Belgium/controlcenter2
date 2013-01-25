<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/usergroup.php";
session_start();

function AddUserINT($inputarray)
{
	##Deze functie handelt de interne aanmaak van een gebruikersaccount af
	require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/user.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/uservalidator.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
	
	#De inputarray zal normaal de waarde krijgen van $_GET
	#er moet een userobject gebouwd worden met deze gegevens en dat moet dan door de validator gehaald worden
	$newuser = new user($inputarray['username']);
	
	if($inputarray['passwordchangerequired'])
	{
		$newuser->setPasswordchangeRequired(1);
	}
	else
	{
		$newuser->setPasswordchangeRequired(0);
	}
	
	$newuser->setMailadress($inputarray['mail']);
	$newuser->setRealName($inputarray['lastname']);
	$newuser->setRealFirstName($inputarray['firstname']);
	$newuser->setWebsite($inputarray['website']);
	$newuser->setCountry($inputarray['country']);
	
	###Het gaat om een intern geregistreerde gebruiker => userconfirmation en adminconfirmation komen op 1
	$newuser->setUserConfirmationStatus('1');
	$newuser->setAdminConfirmationStatus('1');
	
	###Als er gebruikersgroepen zijn aangevinkt moeten deze ook opgeslagen worden
	if(is_array($inputarray['usergroups']))
	{
	$newuser->setUsergroups($inputarray['usergroups']);
	}
	
	$validator = new UserValidator();
	$errormessages = $validator->ValidateObject($newuser);
	
	##In het userobject is er geen ruimte voorzien voor het wachtwoord, maar ook dit moet gevalideerd worden
	$passworderror = $validator->ValidateField("password",$inputarray['password'],-1,"array");
	
	if(!empty($passworderror))
	{
		$errormessages[]=$passworderror;
	}
	
	if(strtolower($inputarray['password']) !== strtolower($inputarray['password2']))
	{
		$newmessage['fieldname'] = "password2";
		$newmessage['message'] = LANG_ERROR_PASSWORDMATCH;	
		$errormessages [] = $newmessage;
	}
	
	if(empty($errormessages))
	{
		###Geen fouten => gebruiker toevoegen
		dataaccess_Adduser($newuser,$inputarray['password']);
	}

	return $errormessages;
}

function AddUserEXT($inputarray)
{
	##Deze functie handelt de externe aanmaak van een gebruikersaccount af
	require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/user.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/uservalidator.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
	
	#De inputarray zal normaal de waarde krijgen van $_GET
	#er moet een userobject gebouwd worden met deze gegevens en dat moet dan door de validator gehaald worden
	$newuser = new user($inputarray['username']);
	
	$newuser->setMailadress($inputarray['mail']);
	$newuser->setRealName($inputarray['lastname']);
	$newuser->setRealFirstName($inputarray['firstname']);
	$newuser->setWebsite($inputarray['website']);
	$newuser->setCountry($inputarray['country']);
	
	###Het gaat om een extern gecreëerde gebruiker => nakijken of er activatie nodig is
	if(getUserActivationParameter())
	{
		###er is wel gebruikersactivatie nodig => confirmationstatus is 0
		$newuser->setUserConfirmationStatus(0);
	}
	else
	{
		###er is geen gebruikersactivatie nodig => confirmationstatus wordt 1
		$newuser->setUserConfirmationStatus(1);
	}
	
	if(getAdminActivationParameter())
	{
		###er is administrator toestemming nodig voor een account-> adminconfimationstatus is 0
		$newuser->setAdminConfirmationStatus(0);
	}
	else
	{
		###er is geen admin toestemming nodig voor een account => adminconfirmationstatus is 1
		$newuser->setAdminConfirmationStatus(1);
	}
	
	$validator = new UserValidator();
	$errormessages = $validator->ValidateObject($newuser);
	
	##In het userobject is er geen ruimte voorzien voor het wachtwoord, maar ook dit moet gevalideerd worden
	$passworderror = $validator->ValidateField("password",$inputarray['password'],-1,"array");
	
	if(!empty($passworderror))
	{
		$errormessages[]=$passworderror;
	}
	
	if(strtolower($inputarray['password']) !== strtolower($inputarray['password2']))
	{
		$newmessage['fieldname'] = "password2";
		$newmessage['message'] = LANG_ERROR_PASSWORDMATCH;	
		$errormessages [] = $newmessage;
	}
	
	###Het gaat om externe registratie => nakijken bij welke gebruikersgroep de account moet ingedeeld worden
	$defaultusergroupid = dataaccess_getDefaultUsergroup();
	
	###het id van de defaultusergroup moet nu in het userobject verwerkt worden zodat het in de database geregistreerd kan worden
	$usergrouparray[] = $defaultusergroupid;
	$newuser->setUsergroups($usergrouparray);	
	
	if(empty($errormessages))
	{
		###Geen fouten => gebruiker toevoegen
		$newuserid=dataaccess_Adduser($newuser,$inputarray['password']);
		
		###Afhankelijk van de activatieprocedure die is ingesteld moet er een mail gestuurd worden naar de gebruiker.
		if(getUserActivationParameter())
		{
			###Bevestiging van het mailadres door de gebruiker is nodig => mail naar de gebruiker versturen.
			require_once $_SERVER['DOCUMENT_ROOT']."/core/email/email.php";
			$activatiemail = new Email();
			$activatiemail->setTo($newuser->getMailadress());
			
			$sitename = getSiteName();
			
			$activatiemail->setSubject(LANG_USER_SELF_ACTIVATION_WELCOMETO . "$sitename");
			$activatiemail->setMessageAddin("/core/presentation/usermanagement/accounts/addins/useractivationmail.tpa");
			$activatiemail->setVariable("sitename","$sitename");
			
			###De activatielink moet verwijzen naar het userid dat werd teruggegeven door dataaccess_adduser
			#en naar het activatiescript op de server waarop Controlcenter2 draait.
			$activatielink = "http://".$_SERVER['HTTP_HOST']."/core/presentation/usermanagement/accounts/activate.php?id=".$newuserid;
			
			$activatiemail->setVariable("activationlink",$activatielink);
			$activatiemail->Send();
		}
	}

	return $errormessages;
}

function AddUsergroup($inputarray)
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/usergroupvalidator.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/usergroup.php";
	#Deze functie voert de nodige controles uit op een nieuwe usergroup en voegt deze toe 
	#als de gegevens correct zijn.
	$validator = new UsergroupValidator;
	
	$newusergroup = new usergroup;
	$newusergroup->setName($inputarray["usergroupname"]);
	
	#De lijst met aangevinkte tasks bevindt zich in $inputarray['tasks']
	#Deze lijst wourdt doorgegeven aan het usergroup object. Het is echter mogelijk dat er geen vakjes werden
	#aangekruist in dat geval gebeurt er hier niks
	if(count($inputarray['tasks'])>0)
	{
	$newusergroup->setPermissions($inputarray['tasks']);
	}
	
	$errormessages = $validator->validateObject($newusergroup);
	
	if(empty($errormessages))
	{
		dataaccess_AddUsergroup($newusergroup);
		return false;
	}
	else
	{
		return $errormessages;
	}
}

function editUsergroup($inputarray)
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/usergroupvalidator.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/usergroup.php";
	
	###Er wordt een instance van usergroup gemaakt die door de validator gehaald wordt om te controleren of
	###de waarden correct zijn.
	$validator = new UsergroupValidator;
	$newusergroup = new usergroup($id=$inputarray['groupid']);
	$newusergroup->setName($inputarray["usergroupname"]);
	
	#De lijst met aangevinkte tasks bevindt zich in $inputarray['tasks']
	#Deze lijst wourdt doorgegeven aan het usergroup object. Het is echter mogelijk dat er geen vakjes werden
	#aangekruist in dat geval gebeurt er hier niks
	if(count($inputarray['tasks'])>0)
	{
	$newusergroup->setPermissions($inputarray['tasks']);
	}
	
	$errormessages = $validator->validateObject($newusergroup);
	
	if(empty($errormessages))
	{
		dataaccess_EditUsergroup($newusergroup);
		return false;
	}
	else
	{
		return $errormessages;
	}
}

function getUsergroup($id)
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";
	###Deze functie haalt de gegevens op van de usergroup met id $id.
	return dataaccess_getUserGroup($id);
}

function getUsergroups()
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";
	return dataaccess_getUsergroups();
}

function getUser($id)
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";
	return dataaccess_getUser($id);
}

function editUser($inputarray)
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/user.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/uservalidator.php";
	##Deze functie moet nagaan of de gebruikersgegevens die werden ingevoerd correct zijn
	##Als dat het geval is mogen ze naar de database geschreven worden.
	##De oorspronkelijke gebruiker wordt opgehaald en de wijzigbare velden worden gewijzigd.

	$editeduser = getUser($inputarray['userid']);

	if($inputarray['passwordchangerequired'])
	{
		$editeduser->setPasswordchangeRequired(1);
	}
	else
	{
		$editeduser->setPasswordchangeRequired(0);
	}

	$editeduser->setMailAdress($inputarray['mail']);
	$editeduser->setRealName($inputarray['lastname']);
	$editeduser->setRealFirstName($inputarray['firstname']);
	$editeduser->setWebsite($inputarray['website']);
	$editeduser->setCountry($inputarray['country']);

	###Bij wijziging moet er normaal gezien niet opnieuw geactiveerd worden.
	$editeduser->setUserConfirmationStatus(1);
	$editeduser->setAdminConfirmationStatus(1);
	
	###Als er gebruikersgroepen zijn aangevinkt moeten deze ook opgeslagen worden
	if(is_array($inputarray['usergroups']))
	{
	$editeduser->setUsergroups($inputarray['usergroups']);
	}
	else
	{
		###user accepteert enkel een array als argument voor setUsergroups, als er dus geen toegang is moet er een lege array
		###aangeleverd worden.
		$editeduser->setUsergroups(array());
	}
	
	$uservalidator = new uservalidator();
	$errormessages = $uservalidator->validateObject($editeduser);
	
	##Nu nog de passwordcontrole, password en password2 moeten gelijk zijn aan elkaar.
	if($inputarray['password'] !== $inputarray['password2'])
	{
		$errormessage['field'] = "password2";
		$errormessage['message'] = LANG_ERROR_PASSWORDMATCH;	
		
		$errormessages[] = $errormessage;
	}
	
	if(empty($errormessages))
	{
		##Wijzigingen ok => gebruiker mag gewijzigd worden.
		dataaccess_EditUser($editeduser,$_POST['password']);
	}

	return $errormessages;
}

function checkUserPassword($username,$password)
{
	###Deze functie controleert of de combinatie $username/$password klopt en geeft true of false terug.
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";
	return dataaccess_checkUserPassword($username,$password);
}

function UserSelfActivation($user)
{
	if($user instanceof User)
	{
		###Het aangeleverde object is een instantie van user=>activeren via DataAccess layer
		dataaccess_UserSelfActivation($user);
		return true;
	}
	else
	{
		throw new CC2Exception("There was an error in the activation system","UserSelfActivation() only accepts an instance of user as argument");
	}
}

function Login($username,$password,$d)
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";
	###Deze functie gaat na of de aangeleverde parameters kloppen
	###Als dat het geval is wordt het userobject opgehaald en wordt er van dit object een sessievariabele gecreëerd.
	
	###Eerst laten we de DataAccess layer controleren of de gegevens kloppen
	$id=dataaccess_checkUserPassword($username,$password);
	if(!empty($id))
	{
		###De aangeleverde gegevens zijn correct
		###We halen de gebruikersgegevens op in een userobject
		$user = getUser($id);
		
		###1e CONTROLE: heeft de gebruiker zijn account geactiveerd
		###Als activatie niet nodig is dan krijgt de gebruiker in de databank zowieso de waarde userconfirmation=1 mee
		if($user->getUserConfirmationStatus()==1)
		{
			###Gebruiker heeft zichzelf geactiveerd of activatie is niet nodig. In ieder geval is er geen beletsel voor
			###het verderzetten van de loginprocedure.
			
			###2e CONTROLE: Moet de useraccount nog geactiveerd worden door een administrator?
			if($user->getAdminConfirmationStatus()==1)
			{
				###De gebruikersgegevens waren correct, en de account is volledig actief => de gebruiker mag ingelogd worden
				$_SESSION['currentuser'] = $user;
				
				###Nu moet er gecontroleerd worden of de gebruiker zijn wachtwoord moet wijzigen. Als dat het geval is dan moet
				###de gebruiker worden doorverwezen naar de pagina voor het wijzigen van zijn wachtwoord.
				if($user->getPasswordChangeRequired()==1)
				{
					header("location: /core/presentation/usermanagement/accounts/passwordchange.php?d=$d");
					exit();
				}
			}
			else
			{
				###De gebruiker heeft zijn account wel geactiveerd maar de admin moet nog zijn/haar toestemming geven
				require_once $_SERVER['DOCUMENT_ROOT']."/core/presentation/general/commonfunctions.php";
				showMessage("Admin moet nog activeren","Uw account is nog niet bruikbaar. Een administrator zal uw gegevens nakijken en uw account activeren. Wij streven ernaar om alle accounts binnen de 24 uur te activeren");
				exit();
			}
		}
		else
		{
			###Gebruiker moet zijn account nog activeren => melding weergeven
			require_once $_SERVER['DOCUMENT_ROOT']."/core/presentation/general/commonfunctions.php";
			showMessage("Activatie nodig","Account nog niet geactiveerd");
			exit();
		}
		return $user;
	}
	else
	{
		###de parameters zijn niet correct => return false
		return false;
	}
}

function checkPermission($module,$permission)
{
	###Deze functie controleert of een gebruiker toegang heeft tot een bepaalde moduletask
	
	###Eerst wordt gekeken of er wel een gebruiker is ingelogd
	if(isset($_SESSION['currentuser']))
	{	
		###De gebruiker is ingelogd. Nu moeten we dus controleren of de gebruikersnaam wel recht heeft om deze pagina te bekijken.
		###Eerst halen we de lijst van gebruikergroepen op waar de gebruiker bij behoort.
		###BUGFIX: de toegangsrechten van de gebruiker worden bij inloggen in de sessievariabele opgeslagen. Dat betekent dat
		###Wanneer er tijdens de sessie iets verandert dat dat dan tot problemen leidt  => bij gebruik van checkpermission laden
		###we de gegevens rechtstreeks uit de database.
		$user = getUser($_SESSION['currentuser']->getId());
		$usergroups = $user->getUsergroups();
		
		
		$permissions = array();
		###Daarna gaan we van alle gebruikersgroepen na welke toegang deze heeft, alle toegangscombinaties worden in een array opgeslagen
		###Eerst kijken we of $usergroups wel een array is, als dat niet het geval is heeft de gebruiker geen enkele toegang => geen zin om verder te gaan
		###In dat geval blijft $permissions leeg.
		if(is_array($usergroups))
		{
			foreach($usergroups as $key=>$usergroupid)
			{
				###De permissions van iedere usergroup worden in array $permissions geplaatst.
				###De klasse gebruiker geeft enkel het id van de usergroup terug, om een usergroupobject te kunnen krijgen moeten we dit
				###ophalen.
			
				$usergroup = getUsergroup($usergroupid);
			
				if(is_array($usergroup->getPermissions()))
				{
					###De toegangsrechten van de groep worden opgehaald.
					$grouppermissions = $usergroup->getPermissions();
					
					###Er wordt nagekeken welk van deze rechten nog niet in $permissions zitten
					$newpermissions = array_diff($grouppermissions,$permissions);	

					###De items die nog niet in $permissions voorkwamen worden nu toegevoegd.
					$permissions = array_merge($permissions,$newpermissions);
				}
			}
		}

		###Nu hebben we een array met alle toegangsrechten van de huidige gebruiker. Het enige wat nu nog rest dat is nakijken
		###of de gebruiker het toegangsrecht heeft dat werd aangevoerd bij het aanroepen van de functie
		
		###Eerst moeten we dus nagaan wat het id is van de aangeleverde permission.
		require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";
		$permissionid=dataaccess_searchPermission($module,$permission);

		###Nu kijken we of de permissionid voorkomt in het toegangsarray
		$haspermission=in_array($permissionid,$permissions);

		
		if(!$haspermission)
		{
			###De gebruiker heeft niet genoeg rechten om deze pagina te openen => uitvoering stoppen en foutmelding geven.
			require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
			$url=getNoAccessURL();
			
			###Als de eigenaar van de server de waarde CORE_NOACCESS_URL heeft ingesteld wordt die pagina weergegeven.
			###Wanneer dit niet het geval is wordt er gebruk gemaakt van showMessage.
			if(empty($url))
			{
				require_once $_SERVER['DOCUMENT_ROOT']."/core/presentation/general/commonfunctions.php";
				showMessage(LANG_ERROR_NOACCESS_HEADER,LANG_ERROR_NOACCESS,'/',LANG_GOTOINDEX);
				exit;
			}
			else
			{
				###De gebruiker wordt naar de pagina doorverwezen en de uitvoering wordt gestopt.
				header("location: $url");
				exit;
			}
		}

	}
	else
	{
		###Geen gebruiker ingelogd => naar inlogpagina. De query d wordt meegegeven om ervoor te zorgen dat de gebruiker na het inloggen
		###terug naar dezelfde pagina kan worden geleid.
		$d = $_SERVER['SCRIPT_NAME'];
		
		###Als de url een querystring bevat moet deze meegenomen worden
		if(!empty($_SERVER['QUERY_STRING']))
		{
			$d=$d.'?'.$_SERVER['QUERY_STRING'];
		}
		
		header("location: /core/presentation/usermanagement/accounts/login.php?d=$d");
		exit;
	}
}

function getPermissions()
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";
	
	return dataaccess_getPermissions();
}

function changePassword($oldpassword,$newpassword1,$newpassword2)
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataaccess/usermanagement/userfunctions.php';
	
	if(($oldpassword == $newpassword1) and ($newpassword1 == $newpassword2) and ((!empty($oldpassword)) ||(!empty($newpassword1)) ||(!empty($newpassword2))))
	{
		$errormessage['field'] = "password1";
		$errormessage['message']= LANG_ERROR_NEWPASSWORD_REQUIRED;
		$errormessages[] = $errormessage;
	}
	
	if($newpassword1 !== $newpassword2)
	{
		$errormessage['field']= "password1";
		$errormessage['message'] = LANG_ERROR_PASSWORDMATCH;
		
		$errormessages[]=$errormessage;
	}
	
	if((empty($newpassword1)) || (empty($newpassword2)))
	{
		$errormessage['field']= "password1";
		$errormessage['message'] = LANG_ERROR_NEWPASS_EMPTY;
		
		$errormessages[] = $errormessage;
	}

	###De velden zijn ingevuld, nu moet gecontroleerd worden of het oude wachtwoord wel klopt
	if(checkUserPassword($_SESSION['currentuser']->getUsername(),$oldpassword))
	{
		###Het oude wachtwoord klopt, we kunnen het wachtwoord wijzigen
		dataaccess_changePassword($_SESSION['currentuser']->getId(),$newpassword1);
	}
	else
	{
		###Het oude wachtwoord klopt niet => error
		$errormessage['field']="oldpassword";
		$errormessage['message']=LANG_ERROR_PASSWORD_INCORRECT;
		
		$errormessages[] = $errormessage;
	}

	###De array met foutmeldingen wordt teruggegeven naar de presentation-layer
	return $errormessages;
}
?>