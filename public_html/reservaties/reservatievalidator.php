<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/validator.php";
require_once $_SERVER['DOCUMENT_ROOT']."/contact/berichtclass.php";
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
				if($value > 19)
				{
					$returnmessage['field']="aantal";
					$returnmessage['message'] = "Vanaf 20 tickets komt u mogelijk in aanmerking voor het groepstarief. Om die reden moeten reservaties vanaf 20 kaarten telefonisch gebeuren via het nummer 051/501616. U kan ook mailen naar secretariaat@detoverlantaarn.be";
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
}
?>