<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/presentation/general/commonfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditielogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditiekandidaat.php';

if(!isset($_GET['key']))
{
	ShowMessage('Geen toegang','om deze pagina te openen heb je een persoonlijke auditiesleutel nodig','/','Vraag je auditiepakket aan');
}
else
{
	###ER is een sleutel opgegeven maar is die wel correcT?
	$kandidaat = checkKey($_GET['key']);
	
	if($kandidaat instanceOf auditieKandidaat)
	{
			$basepath = "/home/chantage/protectedfiles";
			$fileparts = explode("/",$_GET['f']);
			$filesize = filesize($basepath."/$_GET[f]");
			$recommendedfilename = $fileparts[count($fileparts)-1];
			
			header("Content-disposition: attachment;filename=\"$recommendedfilename\"");
			
			switch($_GET['t'])
			{
				case "pdf":
					$contenttype = "application/pdf";
				break;
				case "mp3":
					$contenttype = "audio/mpeg";
				break;
			}
			
			header("Content-type:$contenttype;");
			header("Content-Length: $filesize;");
			readfile("$basepath/$_GET[f]");
	}
	else
	{
		showMessage('Foutieve sleutel','Sorry, het systeem kon uw sleutel niet herkennen. Neem contact op met webmaster@projectkoor.be voor meer info');
	}
}
?>