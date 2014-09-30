<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditielogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditiekandidaat.php';
require_once $_SERVER['DOCUMENT_ROOT']."/core/presentation/general/commonfunctions.php";

###Fix voor notice op de index
$result = null;

if(getAuditiepakketStatus())
{
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
                
                ###Specifieke META voor audities
                $html->addCustomMeta('og:url', 'http://www.projectkoorchantage.be/modules/audities/index.php');
                $html->addCustomMeta('og:title','Projectkoor CHANTage organiseert audities');
                $html->addCustomMeta('og:image', 'http://www.projectkoorchantage.be/images/auditiesfb.png');
                $html->addCustomMeta('og:description',"Kan je goed zingen en heb je zin in een uitdaging? Je hebt er altijd al van gedroomd om in groep 4 tot 8-stemmig te zingen ? Ben je tussen 18 en 55 jaar oud? Dan zit je goed want CHANTage zoekt nieuwe zangers/zangeressen voor haar volgende project.");

                $html->PrintHTML();
    }
}
else
{    
    #showMessage("Audities", "Helaas! op dit moment zijn wij niet op zoek naar kandidaten. Wil je je toch spontaan aanbieden? Stuur dan een mail naar ".dataaccess_getParameter('AUDITIES_ADMIN_MAIL')->getValue());
    $html = new htmlpage('frontend');
    $html->LoadAddin('/modules/audities/geenaudities.tpa');
    $html->setVariable(mailadres, dataaccess_getParameter('AUDITIES_ADMIN_MAIL')->getValue());
    $html->printHTML();
}

?>