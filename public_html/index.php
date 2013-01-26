<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditielogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditiekandidaat.php';

if(isset($_POST['send']))
{
	###Er is input, maar klopt die ook?
	$auditiekandidaat = new auditieKandidaat($_POST['voornaam'],$_POST['naam'],$_POST['mail'],$_POST['stemgroep']);
	$result = addKandidaat($auditiekandidaat);
	
	if($result instanceOf auditieKandidaat)
	{
		###Alles is correct verlopen => bevestigingspagina
		$html= new HTMLPage('frontend');
		$html->LoadAddin('/modules/audities/bevestiging.tpa');
		$html->PrintHTML();
	}
}

if((!isset($_POST['send'])) || (is_array($result)))
{
	$html = new HTMLPage('frontend');

	###Als er foutmeldingen zijn moeten deze getoond worden
	if(count($result)>0)
	{
		$html->setVariable("errors"	,$result);
	}
	

	$html->LoadAddin('/modules/audities/inschrijvingsformulier.tpa');
	$html->PrintHTML();
}
?>