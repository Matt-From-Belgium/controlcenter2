<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/nieuwsbrief.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/nieuwsbrieflogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$abonnementenlijst = getAbonnementenLijst();

if(isset($_POST['submit']))
{
	$maand = intval($_POST['maand']);
	$jaar = intval($_POST['jaar']);
	$geselecteerdeabonnementen = $_POST['abonnement'];
	
	if(count($geselecteerdeabonnementen)>0)
	{
		foreach($geselecteerdeabonnementen as $key=>$value)
		{
			$abonnementobjecten[] = $abonnementenlijst[$value];
		}
	}
	
	$pad ='/modules/nieuwsbrief/file/nieuwsbrieven/1.pdf';

	$nieuwsbrief = new nieuwsbrief(-1,$maand,$jaar,$abonnementobjecten,$pad);
        
	$errors = addNieuwsbrief($_FILES,$nieuwsbrief);
}

$abonnementen = array();

if(is_array($abonnementenlijst))
{
    foreach($abonnementenlijst as $key=>$abonnement)
    {
            $nieuwabonnement['id'] = $abonnement->getID();
            $nieuwabonnement['naam'] = $abonnement->getNaam();
            $abonnementen[] = $nieuwabonnement;
    }
}

$html = new htmlpage('frontend');
$html->LoadAddin('/modules/nieuwsbrief/addins/addnieuwsbrief.tpa');
$html->setVariable('abonnementen',$abonnementen);
$html->setVariable('errorlist',$errors);
$html->printHTML();
?>