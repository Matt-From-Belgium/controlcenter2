<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/abonnee.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/nieuwsbrieflogic.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

	if(isset($_POST['submit']))
	{
	$voornaam = $_POST['voornaam'];
	$familienaam = $_POST['familienaam'];
	$mailadres = $_POST['mailadres'];
	
	$abonnee = new abonnee(-1,$voornaam,$familienaam,$mailadres,"");
	
	$result = abonneeToevoegen($abonnee);
	
	if($result instanceOf abonnee)
	{
		$html = new htmlpage('frontend');
		$html->LoadAddin('/modules/nieuwsbrief/addins/subscribecomplete.tpa');
		$html->setVariable("errors",$errors);
		$html->printHTML();
	}
	else
	{
		###$result wordt een array met foutmeldingen
		$errors = $result;
	}
	
	}
	
	if((!isset($_POST['submit'])) or is_array($errors))
	{
            
        $promotext = getNewsPromoText();
            
	$html = new htmlpage('frontend');
	$html->LoadAddin('/modules/nieuwsbrief/addins/subscribeform.tpa');
	$html->setVariable("errors",$errors);
	$html->setVariable("voornaam",$_POST['voornaam']);
	$html->setVariable("familienaam",$_POST['familienaam']);
	$html->setVariable("mailadres",$_POST['mailadres']);
        $html->setVariable('promotext', $promotext);
	$html->printHTML();
	}
?>