<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";

###Is dit de eerste keer dat de pagina wordt weergegeven?
if(isset($_POST['submit']))
{
	require_once $_SERVER['DOCUMENT_ROOT']."/reservaties/reservatielogic.php";
	$errormessages = ValidateReservatie($_POST);
	
	$vflagstring = "v".$_POST['voorstelling']."selectedflag";
	
	$rflagstring = "r".$_POST['referral']."selectedflag";
	
}

if((!isset($_POST['submit'])) or (is_array($errormessages)))
{
	$html = new htmlpage("frontend");
	$html->LoadAddin('/addins/springawakeningtickets.tpa');
	$html->setVariable("errorlist",$errormessages);
	$html->setVariable("naam",$_POST['naam']);
	$html->setVariable("email",$_POST['email']);
	$html->setVariable("voornaam",$_POST['voornaam']);
	$html->setVariable("aantal",$_POST['aantal']);	
	$html->setVariable("opmerkingen",$_POST['opmerkingen']);	
	
	$html->setVariable($vflagstring,"checked");
	$html->setVariable($rflagstring,"selected");
	
	$html->printHTML();
}
else
{
	###De input was correct, er moet enkel een bevestigingspagina worden weergegeven.
	$html = new htmlpage("frontend");
	$html->LoadAddin("/addins/message.tpa");
	$html->setVariable("messagetitle","Reservatie ontvangen");
	$html->setVariable("message","Wij hebben uw reservatie ontvangen. U ontvangt in de komende minuten een bevestigingsmail met betalingsgegevens.");
	$html->PrintHTML();
}
?>