<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatielogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/presentation/general/commonfunctions.php';

checkPermission('ticketserver','ticketadministratie');

###Wordt de pagina voor het eerst geladen of zijn er uit te voeren commando's?
if(isset($_POST['result']))
{
	switch(strtolower($_POST['result']))
	{
		case 'markeren als betaald':
			reservatieBetaald($_POST['reservatienummer']);
			showMessage('Reservatie afgehandeld','Deze reservatie werd gemarkeerd als betaald. Er werd een mail gestuurd naar de klant waarin de ontvangst van de betaling wordt bevestigd.','/modules/ticketserver/backend.php','Terug','backend');
			break;
		case "reservatie annuleren":
			reservatieAnnuleren($_POST['reservatienummer']);
			showMessage('Reservatie manueel geannuleerd','De reservatie werd geannuleerd. Er werd een mail gestuurd naar de klant om dit te melden. Als de storting toch nog zou binnenkomen kan deze reservatie nog opnieuw geactiveerd worden.','/modules/ticketserver/backend.php','Terug','backend');			
			break;
		case "reservatie heractiveren":
			reservatieHeractiveren($_POST['reservatienummer']);
			showMessage('Reservatie geactiveerd','Deze reservatie werd opnieuw geactiveerd. Hou er rekening mee dat de status van de reservatie nu \'wacht op betaling\' is . Als u de betaling heeft ontvangen moet u dit dus nog aangeven.','/modules/ticketserver/backend.php','Terug','backend');
		break;
	}
}
else
{
	###Geen commando's => reservatiegegevens weergeven
	###Eerst nakijken of er een reservatienummer werd opgegeven
	if(isset($_GET['reservatienummer']))
	{
		###reservatie ophalen, de functie geeft false terug als de reservatie niet bestaat
		$reservatie = openReservatie($_GET['reservatienummer']);
		if(!empty($reservatie))
		{
			###Er werd een reservatie gevonden met het betreffende nummer
			$html = new htmlpage('backend');
			$html->loadAddin('/modules/ticketserver/openreservatie.tpa');
			$html->setVariable('reservatienummer',$reservatie->getId());
			$html->setVariable('reservatiedatum',$reservatie->getDatum());
			$html->setVariable('naam',$reservatie->getNaam());
			$html->setVariable('voornaam',$reservatie->getVoornaam());
			$html->setVariable('voorstelling',getVoorstellingstekst($reservatie->getVoorstelling()));
			$html->setVariable('aantaltickets',$reservatie->getAantalTickets());
		
			###Op basis van de status moeten we bepalen welke knoppen er worden weergegeven	

			switch(strtolower($reservatie->getStatus()))
			{
				case 'wacht op betaling':
						###De status is wacht op betalen => de nodige knoppen zijn:
						###->Markeren als betaald
						###->Reservatie Annuleren
						$betaaldbutton['tekst']="Markeren als betaald";
						$buttons[]=$betaaldbutton;
						
						$annuleringsbutton['tekst']="Reservatie annuleren";
						$buttons[]=$annuleringsbutton;

						$html->setVariable('buttons',$buttons);
						$html->setVariable('statuscolor',"ff9933");
					break;
					
				case 'wacht op betaling (herinnering verstuurd)':
						###De status is wacht op betalen => de nodige knoppen zijn:
						###->Markeren als betaald
						###->Reservatie Annuleren
						$betaaldbutton['tekst']="Markeren als betaald";
						$buttons[]=$betaaldbutton;
						
						$annuleringsbutton['tekst']="Reservatie annuleren";
						$buttons[]=$annuleringsbutton;

						$html->setVariable('buttons',$buttons);	
						$html->setVariable('statuscolor',"ff9933");						
					break;
				case 'definitief':
					###Geen knoppen nodig, reservatie is afgehandeld
						$html->setVariable('statuscolor',"2b9546");
					break;
				case 'automatisch geannuleerd':
						###De status is geannuleerd
						###->Reservatie opnieuw activeren
						$heractiveren['tekst'] = "Reservatie heractiveren";
						$buttons[]=$heractiveren;
						$html->setVariable('buttons',$buttons);
						$html->setVariable('statuscolor',"990000");
					break;
				case 'manueel geannuleerd':
						###De status is geannuleerd
						###->Reservatie opnieuw activeren
						$heractiveren['tekst'] = "Reservatie heractiveren";
						$buttons[]=$heractiveren;
						$html->setVariable('buttons',$buttons);
						$html->setVariable('statuscolor',"990000");
					break;
					
			}
	
			###Totaal te betalen uitrekenen
			$totaal = 8*$reservatie->getAantalTickets();
			$html->setVariable('totaal',$totaal.' EUR');
			
			$html->setVariable('status',$reservatie->getStatus());
			$html->printHTML();

		}
		else
		{
			###Reservatienummer bestaat niet
			showMessage('Reservatienummer niet gevonden','het ingevoerde reservatienummer is ongeldig','/modules/ticketserver/backend.php','Terug','backend');
		}
	}
	else
	{
		echo 'u moet een reservatienummer opgeven';
	}
}
?>
