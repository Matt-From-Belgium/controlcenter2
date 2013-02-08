<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/nieuwsbrieflogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$abonnementenlijst = getAbonnementenLijst();

if(isset($_GET['id']))
{
    ###Als id gedefnieerd is in de querystring moet de inhoud van dat abonnement getoond worden
    
}
else
{
    foreach($abonnementenlijst as $value)
    {
        $newitem['naam']=$value->getNaam();
        $newitem['id']=$value->getId();
        $nieuwsbrieven[] = $newitem;
    }

    $html = new htmlpage("frontend");
    $html->LoadAddin('/modules/nieuwsbrief/addins/shownieuwsbrieven.tpa');
    $html->setVariable('nieuwsbrieven',$nieuwsbrieven);
    $html->PrintHTML();
}
?>
