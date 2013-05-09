<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/validator.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";

class reservatievalidator extends validator
{
	public function __CONSTRUCT()
	{
		Validator::__construct("reservatievalidator");
	}
	
	public function validateEmail($value)
	{
		if(empty($value))
		{
			$returnmessage['field']="email";
			$returnmessage['message']="U moet een mailadres opgeven";
			return $returnmessage;
		}
		else
		{
			if(!$this->ValidEmail($value))
			{
			$returnmessage['field']="email";
			$returnmessage['message']="Het E-mailadres dat u opgaf is niet correct";
			return $returnmessage;				
			}
		}
	}
	
	public function validateNaam($value)
	{
		if(empty($value))
		{
			$returnmessage['field']="naam";
			$returnmessage['message']="U moet een familienaam opgeven";
			return $returnmessage;
		}
	}
	
	public function validateVoornaam($value)
	{
		if(empty($value))
		{
			$returnmessage['field']="voornaam";
			$returnmessage['message']="U moet een voornaam opgeven";
			return $returnmessage;
		}
	}
	
	public function validateAantal($value)
	{
		if(empty($value))
		{
			$returnmessage['field']="aantal";
			$returnmessage['message']="U moet een aantal tickets opgeven";
			return $returnmessage;
		}
		else
		{
			###Aantal moet numeriek zijn
			if(!is_numeric($value))
			{
				$returnmessage['field']="aantal";
				$returnmessage['message']="Vermeld het aantal tickets in cijfers";
				return $returnmessage;
			}
			else
			{
				###Als aantal numeriek is mag het niet groter zijn dan 19 want dan is er mogelijk groepstarief
				if($value >= 15)
				{
					$returnmessage['field']="aantal";
					$returnmessage['message'] = "Vanaf 15 tickets komt u mogelijk in aanmerking voor het groepstarief. Om die reden moeten reservaties vanaf 15 kaarten telefonisch gebeuren via het nummer 051/501616. U kan ook mailen naar secretariaat@detoverlantaarn.be";
					return $returnmessage;
				}
			}
		}
	}
	
	public function validateVoorstelling($value)
	{
		if($value=="")
		{
			$returnmessage['field']="voorstelling";
			$returnmessage['message']="U moet een voorstelling kiezen";
			return $returnmessage;
		}
	}
	
	public function validateReferral($value)
	{
		if($value == 0)
		{
			$returnmessage['field']="referral";
			$returnmessage['message']="Gelieve aan te duiden hoe u van deze voorstelling gehoord heeft";
			return $returnmessage;
		}
	}
	
	public function validateOpmerkingen($value)
	{
	}
	
	public function validateStraat($value)
	{
		if(empty($value))
		{
			$returnmessage['field']="straat";
			$returnmessage['message']="Gelieve uw straat in te vullen";
			return $returnmessage;
		}
	}
	
	public function validateHuisNr($value)
	{
		if(empty($value))
		{
			$returnmessage['field']="nummer";
			$returnmessage['message']="U bent verplicht uw huisnummer op te geven";
			return $returnmessage;
		}
	}
	
	public function validateGemeente($value)
	{
		if(empty($value))
		{
			$returnmessage['field']="gemeente";
			$returnmessage['message']="Voer uw postcode in en selecteer uw gemeente";
			return $returnmessage;
		}
	}
	
	public function ValidateObject($object)
	{
		if($object instanceof reservatie)
		{
			//Het aangeleverde object is in ieder geval van het juiste type => validatie starten
			#$errormessages[]= $this->ValidateAfzender($object->getAfzender());
			$errormessages[] = $this->ValidateNaam($object->getNaam());
			$errormessages[] = $this->ValidateVoornaam($object->getVoornaam());
			$errormessages[] = $this->ValidateEmail($object->getMailadres());
			$errormessages[] = $this->ValidateAantal($object->getAantalTickets());
			$errormessages[] = $this->ValidateVoorstelling($object->getVoorstelling());
			$errormessages[] = $this->ValidateReferral($object->getReferral());
			$errormessages[] = $this->ValidateOpmerkingen($object->getOpmerkingen());
			$errormessages[] = $this->ValidateStraat($object->getStraat());
			$errormessages[] = $this->ValidateHuisNr($object->getHuisNr());
			$errormessages[] = $this->ValidateGemeente($object->getGemeente());
			
			##Nu hebben we een array $errormessages met foutmeldingen maar ook de geslaagde validaties
			##hebben een entry in deze array (deze is leeg maar ze is er...)=>De lege items moeten gewist worden.
			foreach($errormessages as $key=>$value)
			{
				if(!empty($value))
				{
					$correctederrorlist[] = $errormessages[$key];
				}
			}
			
			$errormessages = $correctederrorlist;
			
			return $errormessages;
		}
		else
		{
			throw new CC2Exception("The contact system caused an error","You tried to execute function ValidateObject without a valid reservatie as argument");
		}
	}
	
	public function ValidateField($field,$value,$identifier,$mode="xml")
	{
		switch($field)
		{
			case "naam":
				$errormessage = $this->ValidateNaam($value);
			break;
			case "voornaam":
				$errormessage = $this->ValidateVoornaam($value);
			break;
			case "aantal":
				$errormessage = $this->ValidateBericht($aantal);
			break;
			case "voorstelling":
				$errormessage = $this->ValidateVoorstelling($aantal);
			break;
			case "referral":
				$errormessage = $this->ValidateReferral($value);
			break;
			case "opmerkingen":
				$errormessage = $this->ValidateOpmerkingen($value);
			break;
		}
				
			##Soms kan het handig zijn van $errormessage gewoon terug te krijgen ipv de XML.
			##Standaard is $mode = XML en wordt XML teruggeven, als deze waarde echter veranderd wordt 
			##zal de functie de $errormessage teruggeven.
			if($mode=="xml")
			{
				return Validator::GenerateXMLResponse($field,$errormessage['message']);
			}
			else
			{
				return $errormessage;
			}
		
	}
	
	private function validEmail($email)
	{
   	$isValid = true;
   	$atIndex = strrpos($email, "@");
   	if (is_bool($atIndex) && !$atIndex)
   	{
   	   $isValid = false;
   	}
   	else
   	{
      	$domain = substr($email, $atIndex+1);
      	$local = substr($email, 0, $atIndex);
      	$localLen = strlen($local);
      	$domainLen = strlen($domain);
      	if ($localLen < 1 || $localLen > 64)
      	{
       	  // local part length exceeded
       	  $isValid = false;
      	}
      	else if ($domainLen < 1 || $domainLen > 255)
      	{
       	  // domain part length exceeded
       	  $isValid = false;
      	}
      	else if ($local[0] == '.' || $local[$localLen-1] == '.')
      	{
       	  // local part starts or ends with '.'
       	  $isValid = false;
      	}
      	else if (preg_match('/\\.\\./', $local))
      	{
       	  // local part has two consecutive dots
       	  $isValid = false;
      	}
      	else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      	{
       	  // character not valid in domain part
       	  $isValid = false;
      	}
      	else if (preg_match('/\\.\\./', $domain))
      	{
       	  // domain part has two consecutive dots
       	  $isValid = false;
      	}
      	else if
		(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
   	   {
   	      // character not valid in local part unless 
   	      // local part is quoted
   	      if (!preg_match('/^"(\\\\"|[^"])+"$/',
   	          str_replace("\\\\","",$local)))
   	      {
   	         $isValid = false;
   	      }
   	   }
   	   if ($isValid && !(checkdnsrr($domain,"MX") || 
		checkdnsrr($domain,"A")))
   	   {
   	      // domain not found in DNS
   	      $isValid = false;
   	   }
   	}
   	return $isValid;
	}

}
?>