<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";
        
        checkPermission("usermanagement", "manage usergroups");
	
	if(isset($_POST['submit']))
	{
		$errormessages = AddUsergroup($_POST);
	}
	
	if((!isset($_POST['submit'])) or (!empty($errormessages)))
	{
	$html = new htmlpage("backend");
	
	###De lijst met mogelijke toegangsrechten wordt ingeladen.
	$tasklist = getPermissions();
	
	###De checkedvlag moet op waarde "checked" gezet worden zodat vakjes aangevinkt blijven bij foutmelding
	if(is_array($_POST['tasks']))
	{	
		foreach($tasklist as $id => $moduledetail)
		{
			$activemodule = $tasklist[$id]['tasks'];
		
			foreach($activemodule as $taskid => $taskdetail)
			{
				if(in_array($taskdetail['id'],$_POST['tasks']))
				{
					$tasklist[$id]['tasks'][$taskid]['checkedflag'] = "checked";
				}			
			}
		}
	}
	
	
	$html->setVariable("tasklist",$tasklist);
	$html->setVariable("usergroupname",$_POST['usergroupname']);
	
	$html->LoadAddin("/core/presentation/usermanagement/usergroups/addins/usergroupform.tpa");
	$html->setVariable("errorlist",$errormessages);
	$html->setVariable("pagetitle",LANG_ADD_USERGROUP);
	
	$html->printHTML();
	}
	else
	{
	$html = new htmlpage("backend");
	$html->LoadAddin("/core/presentation/general/addins/message.tpa");
	$html->setVariable("messagetitle",LANG_USERGROUP_ADDED_TITLE);
	$html->setVariable("message",LANG_USERGROUP_ADDED);
	$html->printHTML();
	}
?>
