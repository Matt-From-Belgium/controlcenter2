<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/validator.php";
require_once $_SERVER['DOCUMENT_ROOT']."/contact/berichtclass.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";

class berichtvalidator extends validator
{
	public function __construct()
	{
		Validator::__construct("berichtvalidator");
	}
	
	private function ValidateAfzender($value)
	{
		if(empty($value))
		{
			$returnmessage['field'] = "naam";
			$returnmessage['message'] = "U bent verplicht om een naam op te geven";
			
			return $returnmessage;
		}
	}
	
	private function ValidateMailadres($value)
	{
		if(empty($value))
		{
			$returnmessage['field'] = "mail";
			$returnmessage['message'] = "U bent verplicht om een geldig mailadres op te geven";
			
			return $returnmessage;
		}
	}
	
	private function ValidateBericht($value)
	{
		if(empty($value))
		{
			$returnmessage['field'] = "bericht";
			$returnmessage['message'] = "U kan geen leeg bericht versturen";
			
			return $returnmessage;
		}
	}
	
	public function ValidateObject($object)
	{
		if($object instanceof bericht)
		{
			//Het aangeleverde object is in ieder geval van het juiste type => validatie starten
			$errormessages[]= $this->ValidateAfzender($object->getAfzender());
			$errormessages[]= $this->ValidateMailadres($object->getMailadres());
			$errormessages[]= $this->ValidateBericht($object->getBericht());
			
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
			throw new CC2Exception("The contact system caused an error","You tried to execute function ValidateObject without a valid bericht as argument");
		}
	}
	
	public function ValidateField($field,$value,$identifier,$mode="xml")
	{
		switch($field)
		{
			case "naam":
				$errormessage = $this->ValidateAfzender($value);
			break;
			case "mail":
				$errormessage = $this->ValidateMailadres($value);
			break;
			case "bericht":
				$errormessage = $this->ValidateBericht($value);
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