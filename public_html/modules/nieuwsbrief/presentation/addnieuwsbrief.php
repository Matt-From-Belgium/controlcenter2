<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/nieuwsbrief.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/nieuwsbrieflogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/usermanagement/userfunctions.php";

checkPermission('nieuwsbrief','nieuwsbrieven versturen');

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
	
	$titel = $_POST['titel'];

	$nieuwsbrief = new nieuwsbrief(-1,$maand,$jaar,$titel);
        
	$errors = addNieuwsbrief($_FILES,$nieuwsbrief,$abonnementobjecten);
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