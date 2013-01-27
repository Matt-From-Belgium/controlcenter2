<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
require_once $_SERVER['DOCUMENT_ROOT']."/contact/berichtlogic.php";

try
{

//Eerst nakijken of dit de eerste keer is dat de pagina geladen wordt. We zien dit aan $_POST['submit']
if(isset($_POST['submit']))
{
	//Er zijn gegevens gepost, nakijken of de gegevens correct zijn en doorsturen
	$errormessages=ValidateBericht($_POST);
}

//Op dit punt doen we opnieuw een controle: als er geen $_POST['submit'] is of er is een array $errors dan moet 
//het formulier opnieuw weergegeven worden
if((!isset($_POST['submit'])) or (is_array($errormessages)))
{
$html = new htmlpage("frontend");
$html->LoadAddin('/addins/contact.tpa');

	if(is_array($errormessages))
	{
		$html->setVariable("errorlist",$errormessages);
	}
$html->printHTML();
}
else
{
	###De input was correct, er moet enkel een bevestigingspagina worden weergegeven.
	$html = new htmlpage("frontend");
	$html->LoadAddin("/addins/message.tpa");
	$html->setVariable("messagetitle","Bericht verzonden");
	$html->setVariable("message","Uw bericht werd verzonden, wij proberen u zo spoedig mogelijk te antwoorden");
	$html->PrintHTML();
}

}
catch(CC2Exception $err)
{
	echo $err->getExtendedMessage();
}
?>