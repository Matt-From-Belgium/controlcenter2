<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/nieuwsbrieflogic.php';


if(isset($_GET['id']))
{
    $abonnement = getAbonnementbyKey($_GET['id']);
    
    $abonnees = getAbonnees($abonnement);
    $aantal = count($abonnees);
    
    ###abonnees omzetten naar een array die aan het templatesysteemgekoppeld kan worden
    $abonneelijst = array();
    
    foreach($abonnees as $abonnee)
    {
        $newitem['naam'] = $abonnee->getFamilienaam();
        $newitem['voornaam'] = $abonnee->getVoornaam();
        $newitem['mailadres'] = $abonnee->getMailadres();
        
        $abonneelijst[] = $newitem;
    }
    
    
    $html = new htmlpage('backend');
    $html->LoadAddin('/modules/nieuwsbrief/addins/showabonnees.tpa');
    $html->setVariable('abonnementnaam', $abonnement->getNaam());
    $html->setVariable('aantalabonnees',$aantal);
    $html->setVariable('abonneelijst',$abonneelijst);
    
    $html->PrintHTML();    
}

?>
