<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/dataconnection/componentselector.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/usergroup.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/parameters.php";

function dataaccess_AddUser($userobject,$password)
{
	###Deze functie voegt een gebruiker toe aan de database en geeft het userid van de gebruiker terug
	###Er wordt enkel een instantie van de klasse user als argument aanvaard.
	if($userobject instanceof User)
	{
  
		#Eerst moet de query gedefinieerd worden
		$query = "INSERT INTO users (username,facebookid,password,salt,passwordchangerequired,userconfirmation,adminconfirmation,realname,realfirstname,mailadress) VALUES ('@username','@facebookid','@password','@salt','@passwordchangerequired','@userconfirmation','@adminconfirmation','@realname','@realfirstname','@mailadress')";

                ###We genereren een userspecifiek salt
                $usersalt = dataaccess_generateUserSalt();
          
		$password=encryptPWD($password,$usersalt);
		
		$db = new dataconnection;
		$db->setQuery($query);
		$db->setAttribute("username",$userobject->getUsername());
                $db->setAttribute("facebookid",$userobject->getFacebookID());
		$db->setAttribute("password",$password);
                $db->setAttribute('salt', $usersalt);
		$db->setAttribute("passwordchangerequired",$userobject->getPasswordchangeRequired());
		$db->setAttribute("userconfirmation",$userobject->getUserConfirmationStatus());
		$db->setAttribute("adminconfirmation",$userobject->getAdminConfirmationStatus());
		$db->setAttribute("realname",$userobject->getRealname());
		$db->setAttribute("realfirstname",$userobject->getRealFirstname());
		$db->setAttribute("mailadress",$userobject->getMailadress());

		
		$db->ExecuteQuery();
		
		###De nieuwe gebruiker heeft een id gekregen, dit moet worden bijgehouden
		$newuserid = $db->getLastId();
		
		###Nu moet opgeslagen worden tot welke gebruikersgroepen deze gebruiker behoort
		
		if(is_array($userobject->getUsergroups()))
		{
			foreach($userobject->getUsergroups() as $key=>$groupid)
			{
				###Voor iedere groep waarvan de gebruiker lid is moet er een record worden aangemaakt in de tabel
				###usergroupmembers
				$query = "INSERT INTO usergroupmembers (user,usergroup) VALUES (@userid,@groupid)";
				$db->setQuery($query);
				$db->setAttribute("userid",$newuserid);
				$db->setAttribute("groupid",$groupid);
			
				$db->executeQuery();
			}
		}
		
		return $newuserid;
	}
	else
	{
		throw new Exception("dataaccess_AddUser only accepts a userobject as argument");
	}
}

function dataaccess_generateUserSalt()
{
    $usersalt=hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
    return $usersalt;
}

function dataaccess_getUserSalt($userid)
{
    ###Hiermee halen we de userspecifieke salt op
    #deze wordt gebruikt voor de encryptie van wachtwoorden
    #als id -1 is dan moet een nieuwe has gemaakt worden
    
    
        $db = new DataConnection();
        $query = 'select users.salt from users where users.id=@id';

        $userid = intval($userid);
        $db->setQuery($query);
        $db->setAttribute('id', $userid);

        $db->ExecuteQuery();

        if($db->GetNumRows()>0)
        {
            return $db->GetScalar();
        }
        else
        {
            return false;
        }
    
    
}

function dataaccess_UserExists($user,$id)
{
	#Als $user een string is dan wordt deze string als gebruikersnaam gezien
	#wanneer een object user wordt aangeleverd dan wordt de method ->getUsername uitgevoerd.
	if($user instanceof User)
	{
		$user = $user->getUsername();
	}
	elseif(!is_string($user))
	{	
		#geen string en geen instance van user => exception
		throw new Exception('you must supply a string  for the function dataaccess_userExists()');
	}

	$user = strtolower($user);
	
	$query = "SELECT users.id FROM users WHERE users.username='@username' and users.id <> @id";
	
	$db = new dataconnection;
	$db->setQuery($query);
	$db->setAttribute("username",$user);
	$db->setAttribute("id",$id);
	$db->executeQuery();
	
	if($db->getNumRows()<=0)
	{
		#gebruiker bestaat niet.
		return "false";
	}
	else
	{
		#gebruiker bestaat
		return "true";
	}
}

function dataaccess_MailadressExists($mailadress,$id)
{
	###Deze functie moet nagaan of er nog geen andere gebruiker is met hetzelfde mailadres.
	$db = new dataconnection;
	
	$query = "SELECT users.id FROM users WHERE users.mailadress = '@mailadress' AND users.id <> @id";
	$db->setQuery($query);
	$db->setAttribute("mailadress",$mailadress);
	$db->setAttribute("id",$id);
	
	$db->executeQuery();
	
	if($db->getNumRows() <= 0)
	{
		#mailadres bestaat nog niet
		return false;
	}
	else
	{
		return true;
	}
}

function dataaccess_AddUsergroup($usergroupobject)
{
	###Deze functie cre�ert een nieuwe usergroup aan de hand van aangeleverd object (instantie van klasse usergroup)
	if($usergroupobject instanceof usergroup)
	{
		if(!dataaccess_UsergroupExists($usergroupobject->getName(),$usergroupobject->getId()))
		{
			$groupname = strtolower($usergroupobject->getName());
			$query = "INSERT INTO usergroups (name) VALUES ('@groupname')";
	
			$db = new dataconnection;
			$db->setQuery($query);
			$db->setAttribute("groupname",$usergroupobject->getName());
	
			$db->executeQuery();
		
			###De gebruikersgroep is nu aangemaakt, nu hebben we het id nodig van de nieuwe record zodat we de tasks eraan
			###kunnen koppelen.
			$newid = $db->getLastId();
		
			###Nu kunnen we de tasklijst doorlopen en voor ieder item een record aanmaken in usergrouppermissions
			$tasklist = $usergroupobject->getPermissions();
			
			foreach($tasklist as $key=>$value)
			{
				###Voor iedere task wordt een rij toegevoegd aan usergrouppermissions
				$insertpermissionquery = "INSERT INTO usergrouppermissions (usergroup,moduletask) VALUES (@groupid,@taskid)";
				
				$db->setQuery($insertpermissionquery);
				$db->setAttribute("groupid",$newid);
				$db->setAttribute("taskid",$value);
				
				$db->executeQuery();
			}
		}
		else
		{
			$groupname = $usergroupobject->getName();
			throw new Exception("You tried to add a usergroup $groupname, but 	that one already exists...");
		}
	}
	else
	{
	throw new Exception("You tried to execute dataaccess_addusergroup with a parameter that is not an instance of usergroup");
	}
}

function dataaccess_EditUsergroup($usergroupobject)
{
	###Deze functie cre�ert een nieuwe usergroup aan de hand van aangeleverd object (instantie van klasse usergroup)
	if($usergroupobject instanceof usergroup)
	{
		###Eerst wordt de nieuwe groepsnaam naar de database geschreven
		$db = new dataconnection();
	
		$groupid = $usergroupobject->getId();
		$groupname = $usergroupobject->getName();
		$permissions = $usergroupobject->getPermissions();
		
		$editusergroupquery = "UPDATE usergroups SET usergroups.name = '@groupname' WHERE usergroups.id=@id";
		
		$db->setQuery($editusergroupquery);
		$db->setAttribute("groupname",$usergroupobject->getName());
		$db->setAttribute("id",$usergroupobject->getId());
		
		$db->executeQuery();
		
		###Dan worden de permissions bijgewerkt
		###Eerst worden de oude permissionrecords verwijderd
		$verwijderquery = "DELETE from usergrouppermissions WHERE usergrouppermissions.usergroup=@id";
		$db->setQuery($verwijderquery);
		$db->setAttribute("id",$usergroupobject->getId());
		$db->executeQuery();
		
		###Nu kunnen we nieuwe permissionrecords aanmaken
		$tasklist = $usergroupobject->getPermissions();
		if(count($tasklist)>0)
		{
			foreach($tasklist as $key=>$value)
			{
				###Voor iedere task wordt een rij toegevoegd aan usergrouppermissions
				$insertpermissionquery = "INSERT INTO usergrouppermissions (usergroup,moduletask) VALUES (@groupid,@taskid)";
				
				$db->setQuery($insertpermissionquery);
				$db->setAttribute("groupid",$usergroupobject->getId());
				$db->setAttribute("taskid",$value);
			
				$db->executeQuery();
			}
		}
	}
	else
	{
	throw new Exception("You tried to execute dataaccess_addusergroup with a parameter that is not an instance of usergroup");
	}
}

function dataaccess_getUsergroup($id)
{
	###Deze functie haalt de gegevens op van de gebruikersgroep met nummer $id
	
	###Eerst halen we de basisgegevens van de gebruikersgroep op
	$usergroupquery = "SELECT usergroups.name FROM usergroups WHERE usergroups.id=@id";
	
	$db = new dataconnection();
	$db->setQuery($usergroupquery);
	$db->setAttribute("id",$id);
	$db->executeQuery();
	


	
	if($db->getNumRows() == 1)
	{
		###Er zijn resultaten gevonden
		###Er wordt een usergroup object aangemaakt waarmee de gegevens naar de andere lagen zullen worden doorgegeven
		$usergroup = new usergroup($id);
		
		$result = $db->getResultArray();
		list($groupname) = $result[0];
		$usergroup->setName($groupname);	
		
		
		###Nu we de groepsgegevens hebben moet er nagekeken worden welke permissions er hieraan zijn verbonden
		$permissionsquery = "SELECT usergrouppermissions.moduletask FROM usergrouppermissions WHERE usergrouppermissions.usergroup=@id";
		$db = new dataconnection();
		$db->setQuery($permissionsquery);
		$db->setAttribute("id",$id);
		
		$db->executeQuery();
		$result = $db->getResultArray();
		
		if($db->getNumRows())
		{
			###De usergroup in kwestie heeft permissions => ophalen
			foreach($result as $key=>$value)
			{
				
				list($moduletask) = $result[$key];
				$taskarray[] = $moduletask;
			}
			
			###Op dit punt is de array $taskarray gevuld met de tasknummers waar de gebruikersgroep toegang toe heeft
			###=>deze moet in het usergroupobject worden opgeslagen
			$usergroup->setPermissions($taskarray);
		}
		
		return $usergroup;
	}
	else
	{
		return false;
	}
}

function dataaccess_getUsergroups()
{
	###Deze functie haalt een array op met alle gebruikersgroepen
	$query = "SELECT usergroups.id,usergroups.name FROM usergroups";
	$db = new dataconnection();
	$db->setQuery($query);
	$db->executeQuery();
	
	$resultarray = $db->getResultArray();
	
	if(is_array($resultarray))
	{
		foreach($resultarray as $rownumber=>$rowvalue)
		{
			list($id,$groupname) = $rowvalue;
			$newgroup['id'] = $id;
			$newgroup['groupname'] = $groupname;
		
			$grouplist[] = $newgroup;
		}
	}
	return $grouplist;
}

function dataaccess_UsergroupExists($groupname,$id)
{
	$groupname = strtolower($groupname);
	$query = "SELECT usergroups.id FROM usergroups WHERE usergroups.name='@groupname' AND usergroups.id <> @id";
	
	$db = new dataconnection;
	$db->setQuery($query);
	$db->setAttribute("groupname",$groupname);
	$db->setAttribute("id",$id);
	
	$db->executeQuery();
	
	$numrows = $db->getNumRows();
	
	if($numrows>0)
	{
	return true;
	}
	else
	{
	return false;
	}
}

function dataaccess_getUser($userid)
{
	###Deze functie haalt de gebruikersgegevens op van de gebruiker met id $userid

		$query = "SELECT users.id,users.username,users.facebookid,users.passwordchangerequired,users.userconfirmation,users.adminconfirmation,users.realname,users.realfirstname,users.mailadress FROM users WHERE users.id=@userid";
	
		$db = new dataconnection;
		$db->setQuery($query);
		$db->setAttribute("userid",$userid);
		$db->Executequery($query);
		
		if($db->getNumrows() == 1)
		{
			$result = $db->getResultArray();
		
			list($id,$username,$facebookid,$passwordchange,$userconfirmation,$adminconfirmation,$realname,$realfirstname,$mailadress,$website,$country) = $result[0];
			$returneduser  = new user($username,$id);
                        $returneduser->setFacebookID($facebookid);
			$returneduser->setPasswordchangeRequired($passwordchange);
			$returneduser->setRealName($realname);
			$returneduser->setRealFirstName($realfirstname);
			$returneduser->setMailAdress($mailadress);
			$returneduser->setUserConfirmationStatus($userconfirmation);
			$returneduser->setAdminConfirmationStatus($adminconfirmation);
			
			###Nu moeten de gebruikersgroepen opgehaald worden waar de gebruiker lid van is
			$query = "SELECT usergroupmembers.usergroup FROM usergroupmembers WHERE usergroupmembers.user=@userid";
			$db->setQuery($query);
			$db->setAttribute("userid",$userid);
			$db->ExecuteQuery();

			$result = $db->getResultArray();

			if(is_array($result))
			{

				foreach($result as $rownumber=>$rowvalue)
				{
					list($groupid) = $rowvalue;
					$usergroups[] = $groupid;
				}
				$returneduser->setUsergroups($usergroups);
			}
	
			return $returneduser;
		}
		else
		{
			throw new Exception("User does not exist");
		}


}

function dataaccess_EditUser($userobject,$password)
{
	###Alle controles zijn uitgevoerd, de wijzigingen kunnen naar de database geschreven worden.
	
	###eerst wordt gekekekn of $password een waarde bevat. Als dat niet het geval is dan wordt er niks aan
	###het wachtwoord gewijzigd. Als er wel een waarde is wordt het wachtwoord gewijzigd.


    
	if(!empty($password))
	{
                $usersalt = dataaccess_getUserSalt($userobject->getID());
		$password=encryptPWD($password,$usersalt);
		
		##Het nieuwe wachtwoord wordt naar de database geschreven.
		$db = new dataconnection;
		
		$query = "UPDATE users SET users.password='@password' WHERE users.id='@userid'";
		$db->setQuery($query);
		$db->setAttribute("userid",$userobject->getId());
		$db->setAttribute("password",$password);
		$db->ExecuteQuery();
	}
	
	
		###De overige wijzigingen worden naar de database geschreven.
		$query = "UPDATE users SET users.passwordchangerequired='@passwordchangerequired', users.facebookid='@facebookid' ,users.userconfirmation='@userconfirmation',users.adminconfirmation='@adminconfirmation',users.realname='@realname',users.realfirstname='@realfirstname',users.mailadress='@mailadress' WHERE users.id=@userid";
		
		$db = new dataconnection;
		$db->setQuery($query);
		$db->setAttribute("passwordchangerequired",$userobject->getPasswordchangeRequired());
                $db->setAttribute('facebookid',$userobject->getFacebookID());
		$db->setAttribute("userconfirmation",$userobject->getUserConfirmationStatus());
		$db->setAttribute("adminconfirmation",$userobject->getAdminConfirmationStatus());
		$db->setAttribute("realname",$userobject->getRealName());
		$db->setAttribute("realfirstname",$userobject->getRealFirstName());
		$db->setAttribute("mailadress",$userobject->getMailAdress());
		$db->setAttribute("userid",$userobject->getId());
		

		$db->ExecuteQuery();
		
		###Nu moeten de gebruikersgroepen aangepast worden
		
		###Eerst worden de oude records verwijderd
		$query = "DELETE FROM usergroupmembers WHERE user = @userid";
		$db->setQuery($query);
		$db->setAttribute("userid",$userobject->getId());
		$db->executeQuery();
		
		if(is_array($userobject->getUsergroups()))
		{
			foreach($userobject->getUsergroups() as $key=>$groupid)
			{
				###Voor iedere groep waarvan de gebruiker lid is moet er een record worden aangemaakt in de tabel
				###usergroupmembers
				$query = "INSERT INTO usergroupmembers (user,usergroup) VALUES (@userid,@groupid)";
				$db->setQuery($query);
				$db->setAttribute("userid",$userobject->getId());
				$db->setAttribute("groupid",$groupid);
			
				$db->executeQuery();
			}
		}
}

function dataaccess_getDefaultUsergroup()
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/parameters.php";
	
	###De parameter wordt opgehaald, er wordt een instance van parameter teruggegeven.
	$defaultusergroup = dataaccess_getParameter("CORE_USER_EXT_USERGROUP");

	return $defaultusergroup->getValue();
}

function dataaccess_checkUserFacebookId($id)
{
    if(!empty($id))
    {
        $query = "SELECT id,facebookid FROM users WHERE facebookid='@id'";
        
        $db = new dataconnection();
        $db->setQuery($query);
        $db->setAttribute('id', $id);
        $db->ExecuteQuery();
        
        if($db->GetNumRows()==1)
        {
            ###Alleen maar als er één resultaat is mag er verder gegaan worden
            $result = $db->GetResultArray();
            return $result[0]['id'];
        }
        else 
        {
            if($db->GetNumRows()>1)
            {
                throw new Exception('Multiple user accounts with same Facebook id?');
            }
            return false;
        }
    }
    else
    {
        throw new Exception('$id must be set');
    }
}

function dataaccess_checkUserPassword($username,$password)
{
	###Eerst nakijken of beide waarden werden opgegeven
	if(isset($username) or isset($password))
	{
                ###Om het ingevoerde wachtwoord te kunnen versleutelen moeten we de salt kennen
                $requestSalt = new DataConnection();
                $query = "SELECT users.id FROM users WHERE users.username='@username' LIMIT 1";
                
                $requestSalt->setQuery($query);
                $requestSalt->setAttribute('username', $username);
                $requestSalt->ExecuteQuery();
                
                if($requestSalt->GetNumRows()>0)
                {
                    $userid = $requestSalt->GetScalar();
                }
            
                ###We halen de salt op
                $usersalt = dataaccess_getUserSalt($userid);
                
		###We versleutelen het ingevoerde wachtwoord net zoals we het wachtwoord versleutelen bij
                #creatie van gebruikers
                $password = encryptPWD($password,$usersalt);
                
		###Opvragen van de gebruikers met gebruikersnaam $username en wachtwoord $password
		$query = "SELECT users.id FROM users WHERE users.username='@username' AND users.password='@password'";
		$db = new dataconnection();
		$db->setQuery($query);
		$db->setAttribute("username",$username);
		$db->setAttribute("password",$password);
		$db->executeQuery();
		
		###Nagaan of er gebruikers gevonden zijn
		if($db->getNumRows()==1)
		{
			###Enkel wanneer het resultaat van bovenstaande query gelijk is aan 1 is er een geldige gebruiker. Er kunnen nooit
			###2 gebruikers dezelfde gebruikersnaam hebben dus kan het resultaat nooit groter zijn dan 1
			return $db->getScalar();
		}
		else
		{
			return false;
		}
	}
	else
	{
		throw new Exception('You need to give both a username and password in dataaccess_checkUserPassword()');
	}
}

function dataaccess_UserSelfActivation($user)
{
	#Wanneer men de optie admin confirmation wijzigt zou het kunnen dat gebruikers de boodschap krijgen dat bevestiging niet nodig is
	#terwijl dat wel het geval is. adminconfirmation wordt immers op 0 gezet bij het maken van de account, dan wijzigt de admin de parameter
	#=> gebruiker krijgt de melding dat de account bruikbaar is terwijl de adminconfirmation nog altijd op 0 staat => parameter ophalen en 
	#adminconfirmation op 1 zetten als de optie CORE_USER_ADMIN_ACTIVATION 0 is

	$adminneeded = dataaccess_getParameter("CORE_USER_ADMIN_ACTIVATION")->getValue();
	
	###$adminneeded moet de omgekeerde waarde krijgen. waarde 0 betekent dat admin niet moet activeren => adminconfirmation moet 1 zijn.
	if($adminneeded == 1)
	{
		$adminneeded = 0;
	}
	else
	{
		$adminneeded = 1;
	}

	#id van de gebruiker ophalen
	$id = $user->getId();
	$query = "UPDATE users SET users.userconfirmation='1', users.adminconfirmation='@adminneeded' WHERE users.id=@id";
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute("id",$id);
	$db->setAttribute("adminneeded",$adminneeded);
	$db->executeQuery();
}

function dataaccess_searchPermission($module,$permission)
{
	###Deze functie zoekt in de databank het id op van de aangeleverde permission en geeft deze terug aan de logic layer
	###Er zijn 2 mogelijkheden: de permission is aan een module gekoppeld of het gaat om een losstaande permission.
	if(empty($module))
	{
		###het gaat om een core-permission
		$query = "SELECT permissions.id FROM permissions LEFT JOIN modules ON permissions.module = modules.id WHERE permissions.name='@permission'";	
		$db = new dataconnection();
		$db->setQuery($query);
		$db->setAttribute("permission",strtolower($permission));
}
	else
	{
		###Beide parameters hebben een waarde
		$query = "SELECT permissions.id FROM permissions LEFT JOIN modules ON permissions.module = modules.id WHERE modules.name='@module' AND permissions.name='@permission'";
		$db = new dataconnection();
		$db->setQuery($query);
		$db->setAttribute("module",strtolower($module));
		$db->setAttribute("permission",strtolower($permission));
	}


	$db->executeQuery();
	
	if($db->getNumRows()>0)
	{
		return $db->getScalar();
	}
	else
	{
		###er zijn geen permissions die aan de voorwaarden voldoen => Exception
		throw new Exception("There is no permission $module::$permission");
	}
}

function dataaccess_getPermissions()
{
	###We halen eerst de CORE-permissions op. Dat zijn permissions die niet aan een module gekoppeld zijn
	###Het gaat om zaken als inloggen wanneer de site niet operationeel is, de server tijdelijk ontoegankelijk maken, etc.
	$corequery = "SELECT permissions.id,permissions.name FROM permissions WHERE ISNULL(permissions.module)";
	$db = new dataconnection();
	$db->setQuery($corequery);
	$db->ExecuteQuery();
	
	$resultarray = $db->getResultArray();

	###Core is geen module maar om ervoor te zorgen dat we na het doorlopen van deze functie alle
	###gegevens kunnen teruggeven in ��n array wordt er hier wel gebruik gemaakt van $moduledetail die verderop
	###ook wordt gebruikt voor modules
	$moduledetail['id']=-1;
	$moduledetail['name']="CORE";

	foreach($resultarray as $rowid=>$rowvalue)
	{
		list($permissionid,$permissionname)=$rowvalue;
		$taskdetail['id'] = $permissionid;
		$taskdetail['name']= $permissionname;
		$taskdetail['checkedflag'] = "";
		
		$tasks[]=$taskdetail;
	}
	
	$moduledetail['tasks'] = $tasks;
	
	###De CORE-permissions worden toegevoegd aan de resultarray
	$result[]=$moduledetail;
	
	###We een lijst op van alle modules
	$modulequery = "SELECT modules.id,modules.name FROM modules";
	

	$db->setQuery($modulequery);
	$db->ExecuteQuery();
	
	$resultarray = $db->getResultArray();
	
	foreach($resultarray as $rowid=>$rowvalue)
	{
		list($moduleid,$modulename) = $rowvalue;
		
		###We maken een array die de gegevens van de module bevat
		$moduledetail['id'] = $moduleid;
		$moduledetail['name'] = $modulename;
		
		###Nu gaan we per module op zoek naar de taken die bij de module horen;
		$taskquery = "SELECT permissions.id,permissions.name FROM permissions WHERE permissions.module=@moduleid";
		$db->setQuery($taskquery);
		$db->setAttribute("moduleid",$moduleid);
		$db->ExecuteQuery(); 
		
		$tasklist = $db->getResultArray();

		###De variabele tasks wordt leeggemaakt
		$tasks = "";
		###De takenlijst moet ook doorlopen worden
		foreach($tasklist as $taskrowid => $taskrowvalue)
		{
			list($taskid,$taskname) = $taskrowvalue;
			
			###We maken een array die details van de taskbevat
			$taskdetail['id'] = $taskid;
			$taskdetail['name'] = $taskname;
			
			###Om ervoor te zorgen dat er bij het weergeven van foutmeldingen op de presentation layer
			###ook vakjes kunnen worden aangevinkt wordt er een checkedflag toegevoegd. Als deze de waarde checked krijgt
			###zal het vakje als aangekruist worden weergegeven.
			$taskdetail['checkedflag'] = "";
			
			###Het detail wordt toegevoegd aan de array tasks
			$tasks[] = $taskdetail;
		}
		
		###De details van de tasks worden toegevoegd aan het moduledetail
		$moduledetail['tasks'] = $tasks;
		
		###De details van de module worden aan de resultarray toegevoegd
		$result[] = $moduledetail;
	}
	
	return $result;
}

function dataaccess_changePassword($userid,$newpassword)
{	
        #we halen de gebruikerspecifieke salt op
        $usersalt = dataaccess_getUserSalt($userid);
    
	#De saltvariabele wordt toegevoegd aan het wachtwoord
	$newpassword = encryptPWD($newpassword,$usersalt);
       
	###Het nieuwe wachtwoord wordt naar de database geschreven
	$query = "UPDATE users SET users.password='@newpassword',users.passwordchangerequired=0 WHERE users.id='@userid'";
	
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute("newpassword",$newpassword);
	$db->setAttribute("userid",$userid);
	$db->executeQuery();
}

function dataaccess_FacebookIdUnique($fbid,$id)
{
    ###Deze functie gaat na of een facebook ID al voorkomt in de tabel users
    #Anders zouden er verschillende controlcenter accounts aan één profiel gekoppeld 
    #kunnen worden
    
    $query= "SELECT users.id FROM users WHERE users.facebookid='@fbid' AND users.id<>@id";
    
    $db = new DataConnection;
    $db->setQuery($query);
    $db->setAttribute('fbid', $fbid);
    $db->setAttribute('id', $id);
    $db->ExecuteQuery();
    
    if($db->GetNumRows()>0)
    {
        return false;
    }
    else
    {
        return true;
    }
}

function encryptPWD($password,$usersalt)
{
    ###Dit is geen databasefunctie maar omdat adduser en edituser dezelfde encryptie moeten gebruiken centraliseren we die hier
    
    		include $_SERVER['DOCUMENT_ROOT']."/core/pathtoconfig.php";
                
                ###Deze moet op include blijven staan! anders kan dit problemen geven als de salt in één call meerdere keren uitgevoerd wordt
                #De salt wordt dan mogelijk meerdere keren gehasht
                include $pathtoconfig."/salt.php";
		
		#De saltvariabele wordt toegevoegd aan het wachtwoord
                #Het wachtwoord dat bij de server binnenkomt is gehasht.
                #De salt wordt ook gehashed en de samenvoeging van beiden wordt nogmaals gehashed
                $salt = hash('sha512', $salt);
                
                #We hashen de usersalt nog eens
                $usersalt = hash('sha512',$usersalt);
                
		$password = $salt.$password.$usersalt;
                
		$password = hash('sha512',$password);
                
                return $password;
}
?>