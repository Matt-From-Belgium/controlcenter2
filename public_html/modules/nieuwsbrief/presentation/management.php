<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/nieuwsbrieflogic.php';
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";

checkPermission('nieuwsbrief', "beheerscherm openen");

###Nieuwsbrieven ophalen
$nieuwsbrieven=getNieuwsbrieven();

$nieuwsbrieflijst = array();
foreach($nieuwsbrieven as $nieuwsbrief)
{
    $newitem['id'] = $nieuwsbrief->getID();
    $newitem['titel']= $nieuwsbrief->getTitel();
    
    $newitem['ontvangers']=berekenAbonnees($nieuwsbrief);
    
    if($nieuwsbrief->getVerstuurd())
    {
        $newitem['status']="Verstuurd";
    }
    else
    {
        $newitem['status']='Wachtrij';
        
        ###actiecode moet de HTML-code met acties krijgen
        $id = $nieuwsbrief->getID();
        $newitem['actiecode']="<a href='/modules/nieuwsbrief/presentation/cancelnieuwsbrief.php?id=$id'>Annuleer</a>";
    }
    
    $nieuwsbrieflijst[]=$newitem;
}

###Abonnementenlijst ophalen
$abonnementen = getAbonnementenLijst();

$abonnementenarray = array();

foreach($abonnementen as $abonnement)
{
    
   ###We halen het aantal abonnees op
   $newitem['aantalabonnees']=countAbonnees($abonnement)." abonnees";
    
   $newitem['naam'] = $abonnement->getNaam();
   $abonnementenarray[] = $newitem;
   
}

$html = new htmlpage('backend');
$html->LoadAddin('/modules/nieuwsbrief/addins/management.tpa');
$html->setVariable('nieuwsbrieflijst', $nieuwsbrieflijst);
$html->setVariable('abonnementenlijst', $abonnementenarray);
$html->PrintHTML();

?>
