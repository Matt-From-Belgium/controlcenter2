<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/nieuwsbrieflogic.php';
    require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
    require_once $_SERVER['DOCUMENT_ROOT'].'/core/email/email.php';
    
    if(isset($_GET['key']))
    {
        ###Eerst halen we de abonnee terug op
	$abonnee = getAbonneeByKey($_GET['key']);
	
	if($abonnee)
	{
            ###We hebben de abonnee. Vraag is nu of er al bevestigd werd
            if($_POST['confirm'])
            {
                ###Er is bevestiging, abonnee mag verwijderd worden
                deleteAbonnee($abonnee);
                
                ###Bevestigingsmail naar de gebruiker
                $cancelmail = new Email();
                $cancelmail->setSubject("Annulatie jestaatnietalleen.be");
                $cancelmail->setMessageAddin("/modules/nieuwsbrief/addins/cancelmail.tpa");
                $cancelmail->setTo($abonnee->getMailadres())                ;
                $cancelmail->Send();
                
                ###Bevestigingspagina weergeven
                $html = new htmlpage('frontend');
                $html->LoadAddin('/modules/nieuwsbrief/addins/cancelcomplete.tpa');
                $html->PrintHTML();  
            }
            else
            {
                ###Nog geen bevestiging, bevestigingspagina weergeven
                $html = new htmlpage('frontend');
                $html->LoadAddin('/modules/nieuwsbrief/addins/cancel.tpa');
                $html->PrintHTML();
            }
        }
        else
        {
            echo "ongeldige sleutel";
        }
    }
?>
