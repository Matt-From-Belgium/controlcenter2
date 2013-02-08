<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/abonnement.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/nieuwsbrieflogic.php';

if(isset($_POST['key']))
{
	###De gebruiker heeft zijn keuze doorgestuurd
	###We moeten het abonnnement nu configureren
	
	###Eerst halen we de abonnee terug op
	$abonnee = getAbonneeByKey($_POST['key']);
	
	if($abonnee)
	{
		###abonnee gevonden
		if(count($_POST['abonnementen'])>0)
		{
			###Er zijn abonnementen geselecteerd

			###We halen de abonnementenlijst op
			$abonnementenlijst = getAbonnementenLijst();
			
			###We gaan voor ieder geselecteerd item het abonnementenobject opslaan in de array
			$geselecteerdeabonnementen = array();
			
			foreach($_POST['abonnementen'] as $key=>$value)
			{
				$geselecteerdeabonnementen[] = $abonnementenlijst[$value];
			}
			
			###Nu voegen we de array met objecten toe aan het abonnee object
			$abonnee->editSubscriptions($geselecteerdeabonnementen);
			
			###Het gewijzigde abonnee object mag nu opgeslagen worden
			editAbonnee($abonnee);
			
			###De abonnee mag nu geactiveerd worden
			confirmAbonnee($abonnee);
			
			###Nu mag de bevestigingspagina getoond worden
			$html = new htmlpage('frontend');
			$html->LoadAddin('/modules/nieuwsbrief/addins/confirm2.tpa');
			$html->printHTML();
		}
		else
		{
			###Fout gebruiker moet terug naar de selectiepagina gebracht worden
			$errormessage['message']="Je moet minstens 1 abonnement selecteren";
			$errors[] = $errormessage;
		}
	}
	else
	{
		###abonnee niet gevonden => blanco pagina
	}
}

if(isset($_GET['key']) || is_array($errors))
{
	###Gebruiker heeft op de link geklikt of bovenstaande code is uitgevoerd met fouten
	###We kunnen dus het abonnement configureren
	
	###Eerst moeten we nagaan welk mailadres overeenkomst met de opgegeven sleutel

	if(isset($_GET['key']))
	{
	###$abonnee krijgt waarde false als er niks terugkomt, anders is er een object teruggegeven
	$abonnee = getAbonneeByKey($_GET['key']);
	}

	if(is_array($errors))
	{
		$abonnee = getAbonneeByKey($_POST['key']);
	}
	
	if($abonnee)
	{
		###Nu moet de abonnee kiezen op welke abonnementen hij/zij wil inschrijven
		$abonnementen = getAbonnementenLijst();
		
		###We krijgen een array met objecten terug maar deze moeten in een array omgezet worden dat
		###bruikbaaris voor het templatesysteem
		$abonnementenarray = array();
		
                
		foreach($abonnementen as $key=>$abonnement)
		{
			$nieuwitem['id'] = $abonnement->getId();
			$nieuwitem['naam']= $abonnement->getNaam();
			
			$abonnementenarray[] = $nieuwitem;
		}
		
		$html = new htmlpage('frontend');
		$html->LoadAddin('/modules/nieuwsbrief/addins/confirm.tpa');
		$html->setVariable("abonnementen",$abonnementenarray);
		$html->setVariable("errors",$errors);
		$html->setVariable("key",$abonnee->getKey());
		$html->printHTML();
	}
	else
	{
		echo "key unknown";
	}
}

?>