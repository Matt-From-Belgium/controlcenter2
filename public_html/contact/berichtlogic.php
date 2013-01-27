<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/contact/berichtclass.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/contact/berichtvalidator.php";
	
	function ValidateBericht($postarray)
	{
		$validator = new berichtvalidator();
		$ingevoerdbericht = new bericht($postarray['naam'],$postarray['mail'],$postarray['bericht']);
		
		$messages = $validator->ValidateObject($ingevoerdbericht);
		
		if(!is_array($messages))
		{
			//Geen fouten => mail versturen
			$afzender = $ingevoerdbericht->getAfzender();
			$onderwerp = "Bericht ontvangen via www.detoverlantaarn.be";
			$mailadres = $ingevoerdbericht->getMailadres();
			
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
 			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			// Additional headers
			$headers .= "From: $afzender <$mailadres)>" . "\r\n";
			
			$bericht  = "<html><body><b>Deze mail werd u gestuurd via de website van De Toverlantaarn, u kan antwoorden op deze mail via de normale weg</b><p>";
			$bericht .= $ingevoerdbericht->getBericht();
			$bericht .= "</body></html>";
			
			switch($postarray['bestemming'])
			{
				case "secretariaat":
					$bestemming = "secretariaat@detoverlantaarn.be";
				break;
				case "regisseur":
					$bestemming = "tom.durie@detoverlantaarn.be";
				break;
				case "webmaster":
					$bestemming = "webmaster@detoverlantaarn.be";
				break;
			}

			mail($bestemming,$onderwerp,$bericht,$headers);
		}
		return $messages;
	}
?>