<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/presentation/general/commonfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditielogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditiekandidaat.php';




if(!isset($_GET['key']))
{
	ShowMessage('Geen toegang','om deze pagina te openen heb je een persoonlijke auditiesleutel nodig','/','Vraag je auditiepakket aan');
}
else
{
	###ER is een sleutel opgegeven maar is die wel correcT?
	$kandidaat = checkKey($_GET['key']);
	
	if($kandidaat instanceOf auditieKandidaat)
	{
		###WE hebben een kandidaat, wordt het formulier voor het eerst weergegeven of moeten we gegevens verwerken?
		
		if(isset($_POST['send']))
		{
			$defKandidaat = new defAuditieKandidaat($kandidaat);
			
			###Nu moeten we de bijkomende gegevens overdragen op het object
			$defKandidaat->setAdres($_POST['adres']);
			$defKandidaat->setGSM($_POST['zonetel'].$_POST['telrest']);
			$defKandidaat->setGeboorteDatum($_POST['geboortejaar'],$_POST['geboortemaand'],$_POST['geboortedag']);
			$defKandidaat->setHoogsteNoot($_POST['hoogstenoot']);
			$defKandidaat->setLaagsteNoot($_POST['laagstenoot']);
			$defKandidaat->setPartiturenLezen($_POST['partituren']);
			$defKandidaat->setErvaring($_POST['ervaring']);
			$defKandidaat->setZangles($_POST['zangles']);
			$defKandidaat->setInstrument($_POST['instrument']);
			$defKandidaat->setErvaringInstrument($_POST['ervaringinstrument']);
			$defKandidaat->setMotivatie($_POST['motivatie']);
			
			$errorlist = bevestigInschrijving($defKandidaat);
			
			if(!is_array($errorlist))
			{
				showMessage('Inschrijving bevestigd','Je bent definitief ingeschreven. H&eacute;&eacute;l v&eacute;&eacute;l succes! We nemen nog contact op met je om concreet af te spreken.');
			}
		}
		
		if(!isset($_POST['send']) || is_array($errorlist))
		{


			$html = new HTMLpage('frontend');
			$html->LoadAddin('/modules/audities/uitgebreideinschrijving.tpa');
			$html->setVariable('naam',$kandidaat->getNaam());
			$html->setVariable('voornaam',$kandidaat->getVoornaam());
			$html->setVariable('mailadres',$kandidaat->getMailadres());
			$html->setVariable('stemgroep',$kandidaat->getStemgroep());
			$html->setVariable('key',$kandidaat->getKey());
			
			if(is_array($errorlist))
			{
				$html->setVariable('errors',$errorlist);
				
				###Ingevulde waarden herhalen
				$html->setVariable('adres',$_POST['adres']);
				$html->setVariable('zonetel',$_POST['zonetel']);
				$html->setVariable('telrest',$_POST['telrest']);
				$html->setVariable('hoogstenoot',$_POST['hoogstenoot']);
				$html->setVariable('laagstenoot',$_POST['laagstenoot']);
				$html->setVariable('ervaring',$_POST['ervaring']);
				$html->setVariable('instrument',$_POST['instrument']);
				$html->setVariable('ervaringinstrument',$_POST['ervaringinstrument']);
				$html->setVariable('motivatie',$_POST['motivatie']);
				
				if($_POST['partituren']=='Y')
				{
					$html->setVariable('partituurja',"CHECKED");
				}
				else
				{
					$html->setVariable('partituurnee','CHECKED');
				}
				
				if($_POST['zangles']=='Y')
				{
					$html->setVariable('zanglesja',"CHECKED");
				}
				else
				{
					$html->setVariable('zanglesnee','CHECKED');
				}
				
			}
		
			###Maanden invullen op het formulier
			$maand['naam'] = 'januari';
			$maand['value'] = 1;
			$maanden[] = $maand;
		
			$maand['naam'] = 'februari';
			$maand['value'] = 2;
			$maanden[] = $maand;
		
			$maand['naam'] = 'maart';
			$maand['value'] = 3;
			$maanden[] = $maand;
		
			$maand['naam'] = 'april';
			$maand['value'] = 4;
			$maanden[] = $maand;
		
			$maand['naam'] = 'mei';
			$maand['value'] = 5;
			$maanden[] = $maand;
		
			$maand['naam'] = 'juni';
			$maand['value'] = 6;
			$maanden[] = $maand;
		
			$maand['naam'] = 'juli';
			$maand['value'] = 7;
			$maanden[] = $maand;
		
			$maand['naam'] = 'augustus';
			$maand['value'] = 8;
			$maanden[] = $maand;
		
			$maand['naam'] = 'september';
			$maand['value'] = 9;
			$maanden[] = $maand;
		
			$maand['naam'] = 'oktober';
			$maand['value'] = 10;
			$maanden[] = $maand;
		
			$maand['naam'] = 'november';
			$maand['value'] = 11;
			$maanden[] = $maand;
		
			$maand['naam'] = 'december';
			$maand['value'] = 12;
			$maanden[] = $maand;
		

		
			###Zorgen dat bij fouten de actieve maand dat blijft
			for($i=0;$i<count($maanden);$i++)
			{
				if($_POST['geboortemaand'] == $i)
				{
					$maanden[$i]['checkedflag']="SELECTED";
				}
				else
				{
					$maanden[$i]['checkedflag']="";
				}
			}
			
			$html->setVariable('maanden',$maanden);
		
			###Dagen van de maand 
			for($i=1;$i<=31;$i++)
			{
				$dag['nummer'] = $i;
				
				if($_POST['geboortedag']==$i)
				{
					$dag['checkedflag']="SELECTED";
				}
				else
				{
					$dag['checkedflag']="";
				}
				
				$dagen[] = $dag;
			}
			
			$html->setVariable('dagen',$dagen);
			
			for($i=1900;$i<=2000;$i++)
			{
				$jaar['nummer'] = $i;
				
				if($_POST['geboortejaar'] == $i)
				{
					$jaar['checkedflag'] = "SELECTED";
				}
				else
				{
					$jaar['checkedflag'] = "";
				}
				
				$jaren[] = $jaar;
			}
			
			$html->setVariable('jaren',$jaren);
			
			try
			{
			$html->PrintHTML();
			}
			catch(CC2Exception $ex)
			{
				echo $ex->getExtendedMessage();
			}
		}
	}
	else
	{
			showMessage('Foutieve sleutel','Sorry, het systeem kon uw sleutel niet herkennen. Neem contact op met webmaster@projectkoor.be voor meer info');
	}
}
?>