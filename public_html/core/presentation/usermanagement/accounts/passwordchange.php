<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/presentation/general/commonfunctions.php';

###Is er een gebruiker ingelogd?
if(isset($_SESSION['currentuser']))
{
	###Zijn er gegevens om te verwerken? maw, heeft $_POST['submit'] een waarde?
	if(isset($_POST['submit']))
	{
		$errormessages=changePassword($_POST['oldpassword'],$_POST['newpassword1'],$_POST['newpassword2']);	
		
		if(!is_array($errormessages))
		{
			###De functie is uitgevoerd en er zijn geen errors teruggekeerd 
			
			if(isset($_GET['d']))
			{
			###doorgaan naar de bevestigingspagina met link naar $_GET['d']
			#header("location: $_GET[d]");
			showMessage(LANG_PASSWORD_CHANGED_HEADER,LANG_PASSWORD_CHANGED,$_GET['d'],LANG_CONTINUE);
			}
			else
			{
				###De gebruiker heeft zijn wachtwoord vrijwillig gewijzigd => showmessage
				showMessage(LANG_PASSWORD_CHANGED_HEADER,LANG_PASSWORD_CHANGED,'/',LANG_GOTOINDEX);
			}
		}
	}


	###Wanneer de pagina voor het eerst wordt geladen (als $_POST['submit'] geen waarde heeft) of wanneer er fouten zijn opgetreden
	###(dus als $errormessages één of meerdere foutmeldingen heeft teruggekregen van changePassword() moet het formulier worden weergegeven.
	if((!isset($_POST['submit'])) or (is_array($errormessages)))
	{
		$html = new htmlpage('backend');
		$html->LoadAddin('/core/presentation/usermanagement/accounts/addins/changepassword.tpa');
                $html->loadScript('/core/logic/usermanagement/hashpwd.final.js');
                $html->loadScript('/core/logic/usermanagement/hash.final.js');
                
		###Als de waarde $_GET['d'] gedefinieerd is dan komen we uit de inlogprocedure => bericht aan de gebruiker dat hij dit
		###scherm krijgt omdat de administrator dat zo bepaald heeft.
		if(isset($_GET['d']))
	    {
			$html->setVariable("message",LANG_PASSWORD_FORCEDCHANGE);
	    }
	
		###Foutmeldingen inladen
		if(isset($errormessages))
		{
			$html->setVariable("errorlist",$errormessages);
		}
	
		$html->PrintHTML();
	}
}
else
{
	echo "you must login before you can change your password";
}
?>