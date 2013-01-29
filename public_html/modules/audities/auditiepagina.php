<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/presentation/general/commonfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditielogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditiekandidaat.php';

$kandidaat = checkKey($_GET['key']);

if(getAuditieMasterSwitchStatus())
{
    if(getAuditieInschrijvingStatus() || inschrijvingBevestigd($kandidaat))
    {
        if(!isset($_GET['key']))
        {
                ShowMessage('Geen toegang','om deze pagina te openen heb je een persoonlijke auditiesleutel nodig','/','Vraag je auditiepakket aan');
        }
        else
        {
                ###ER is een sleutel opgegeven maar is die wel correcT?


                if($kandidaat instanceOf auditieKandidaat)
                {
                        $html = new HTMLPage('frontend');
                        $html->LoadAddin('/modules/audities/auditiepagina.tpa');
                        $html->setVariable('naam',$kandidaat->getNaam());
                        $html->setVariable('voornaam',$kandidaat->getVoornaam());
                        $html->setVariable('key',$kandidaat->getKey());

                        ###Afhankelijk van de inschrijvingsstatus moet een andere melding weergegeven worden
                        if(inschrijvingBevestigd($kandidaat))
                        {
                                $html->setVariable('welingeschreven',"ok");
                        }
                        else
                        {
                                $html->setVariable('nognietingeschreven',"ok");
                        }

                        $html->PrintHTML();
                }
                else
                {
                        showMessage('Foutieve sleutel','Sorry, het systeem kon uw sleutel niet herkennen. Neem contact op met webmaster@projectkoor.be voor meer info');
                }
        }
    }
    else
    {
        showMessage("Auditiepagina gedesactiveerd", "De deadline voor de inschrijvingen is verstreken. Mischien een volgende keer?");
    }
}
else
{
    showMessage("Audities verlopen","Het auditiesysteem is momenteel niet actief");

}
?>