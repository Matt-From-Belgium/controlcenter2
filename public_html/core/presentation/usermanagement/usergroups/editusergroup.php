<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
	
        checkPermission("usermanagement", "manage usergroups");
        
	$html = new htmlpage("backend");
try
{	
	if(isset($_POST['submit']))
	{
		$errormessages = editUsergroup($_POST);
		print_r($errormessages);
		
	}
	
	if((!isset($_POST['submit'])) or (!empty($errormessages)))
	{
		###er moet een attribuut id meegegeven worden met de url om te kunnen bepalen welke usergroup er moet
		###aangepast worden
		$groupid = $_GET['id'];
	
		if(isset($groupid))
		{
	
		###gebruikersgroep ophalen
		$usergroup = getUsergroup($groupid);
		$usergroupname = $usergroup->getName();
		$grouppermissions = $usergroup->getPermissions();
	
		###De lijst met mogelijke toegangsrechten wordt ingeladen.
		$tasklist = getPermissions();
	
		###de huidige instellingen moeten worden weergegeven => nagaan welke vakjes aangevinkt moeten worden.
		###De tasklist wordt doorlopen en voor iedere taak moet er gekeken worden of het id voorkomt in de array 
		###$grouppermissions
		foreach($tasklist as $modulekey => $module)
		{
			###De taken moeten worden doorlopen
			foreach($module['tasks'] as $taskkey => $task)
			{
				###Nakijken of het taakid voorkomt in $grouppermissions
				###Eerst kijken of er wel permissions in de array grouppermissions voorkomen, het zou immers
				###kunnen gaan om een gebruikersgroep zonder permissions.
				if(count($grouppermissions)>0)
				{
					if(in_array($task['id'],$grouppermissions))
					{
						###De checkedflag wordt op "checked" gezet om het vakje aan te vinken
						$tasklist[$modulekey]["tasks"][$taskkey]['checkedflag'] = "checked";
					}
				}
			}
		}
	
		$html->LoadAddin('/core/presentation/usermanagement/usergroups/addins/usergroupform.tpa');
		$html->setVariable("name",$usergroupname);
		$html->setVariable("errorlist",$errormessages);
		$html->setVariable("groupid",$groupid);
		$html->setVariable("tasklist",$tasklist);

		$html->PrintHTML();
		}
		else
		{
			echo "You must specify an id";
		}
	}
	else
	{
	$html = new htmlpage("backend");
	$html->LoadAddin("/core/presentation/general/addins/message.tpa");
	$html->setVariable("messagetitle",LANG_USERGROUP_EDITED_TITLE);
	$html->setVariable("message",LANG_USERGROUP_EDITED);
	$html->printHTML();
	}
}
catch(CC2Exception $ex)
{
	echo $ex->getExtendedMessage();
}
?>