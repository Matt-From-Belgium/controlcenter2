<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditiekandidaat.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditiedata.php';


class kandidaatValidator
{
	###privates
	private $errorlist=array();
	
	public function validateObject(auditieKandidaat $kandidaat)
	{
		$errorlist[] = $this->validateVoornaam($kandidaat->getVoornaam());
		$errorlist[] = $this->validateNaam($kandidaat->getNaam());
		$errorlist[] = $this->validateMailadres($kandidaat->getMailadres());
		$errorlist[] = $this->validateStemgroep($kandidaat->getStemgroepInt());
		
		$errorlist = array_filter($errorlist);
		
		return $errorlist;
	}
	
	public function validateVoornaam($voornaam)
	{
		$errormessage = array();
		if(empty($voornaam))
		{
			$errormessage['field'] = "voornaam";
			$errormessage['message'] = "Geen voornaam ingevuld";
		}
		
		return $errormessage;
		
	}
	
	public function validateNaam($naam)
	{
		$errormessage = array();
		if(empty($naam))
		{
			$errormessage['field'] = "naam";
			$errormessage['message'] = "Geen familienaam ingevuld";
		}
		
		return $errormessage;
		
	}
	
	public function validateMailadres($mail)
	{
		$errormessage = array();
		if(empty($mail))
		{
			$errormessage['field'] = "mail";
			$errormessage['message'] = "Geen mailadres ingevuld";
		}
		else
		{
			###Er is een mailadres ingevuld maar klopt het wel?
			if(!$this->validEmail($mail))
			{
				$errormessage['field'] = "mail";
				$errormessage['message'] = "Dit is geen geldig mailadres";				
			}
			else
			{
				###mailadres heeft de juiste vorm, maar bestaat het reeds in onze databank?
				if(dataMailReedsGekend($mail))
				{
					$errormessage['field'] = "mail";
					$errormessage['message'] = "Dit mailadres is reeds gekend in de databank";					
				}
			}
		}
		
		return $errormessage;
		
	}
	
	public function validateStemgroep($stemgroep)
	{
		$errormessage = array();
		if(empty($stemgroep))
		{
			$errormessage['field'] = "stemgroep";
			$errormessage['message'] = "Geen stemgroep opgegeven";
		}
		
		return $errormessage;
		
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

class defKandidaatValidator
{
	public function validateObject(defAuditieKandidaat $kandidaat)
	{
		$errorlist[] = $this->validateAdres($kandidaat->getAdres());
		$errorlist[] = $this->validateGeboortedatum($kandidaat->getGeboortedatum());
		$errorlist[] = $this->validateGSM($kandidaat->getGSM());
		$errorlist[] = $this->validateMotivatie($kandidaat->getMotivatie());
		$errorlist[] = $this->validatePartiturenLezen($kandidaat->getPartiturenLezen());
		$errorlist[] = $this->validateZangles($kandidaat->getZangles());
		
		$errorlist = array_filter($errorlist);
		
		return $errorlist;
	}
	
	public function validateAdres($adres)
	{
		if(empty($adres))
		{
			$errormessage['field'] = "adres";
			$errormessage['message'] = "Geen adres opgegeven";			
		}
		
		return $errormessage;
	}
	
	public function validateGSM($nummer)
	{
		if(empty($nummer))
		{
			$errormessage['field'] = "nummer";
			$errormessage['message'] = "Geen telefoonnummer opgegeven";
		}
		
		return $errormessage;
	}
	
	public function validateGeboortedatum($datum)
	{
		$datumarray = explode('/',$datum);
		foreach($datumarray as $key=>$value)
		{
			if(empty($value))
			{
				$errormessage['field'] = "geboortedatum";
				$errormessage['message'] = "gelieve uw volledige geboortedatum in te vullen";
			}
		}
		
		return $errormessage;
	}
	
	public function validatePartiturenLezen($bool)
	{
		if(empty($bool))
		{
			$errormessage['field'] = "partituren lezen";
			$errormessage['message'] = "Geef aan of je partituren kan lezen";
		}
		
		return $errormessage;
	}
	
	public function validateZangles($bool)
	{
		if(empty($bool))
		{
			$errormessage['field'] = "zangles";
			$errormessage['message'] = "Geef aan of je zanglessen hebt gevolgd";
		}
		
		return $errormessage;
	}
	
	public function validateMotivatie($motivatie)
	{
		if(empty($motivatie))
		{
			$errormessage['field'] = "motivatie";
			$errormessage['message'] = "Vul de rubriek motivatie in";
		}
		
		return $errormessage;
	}
}

/*
###DEBUG
$nieuwekandidaat = new auditieKandidaat("","","","");

$kandidaatvalidator = new kandidaatValidator();
#print_r($kandidaatvalidator->validateStemgroep($nieuwekandidaat->getVoornaam()));
print_r($kandidaatvalidator->validateObject($nieuwekandidaat));
*/

/*
###DEBUG
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditiedata.php';
$auditiekandidaat = data_getKandidaatByKey("2a5781dfd8068670921800c8e0b55b5c");

$definitievekandidaat = new defAuditieKandidaat($auditiekandidaat);


$definitievekandidaat->setAdres('Lange Veldstraat 10 \n 8600 DIksmuide');
$definitievekandidaat->setGSM('0472377267');
$definitievekandidaat->setGeboortedatum(1984,10,18);
$definitievekandidaat->setHoogstenoot('geen idee');
$definitievekandidaat->setLaagstenoot('geen idee');
$definitievekandidaat->setpartiturenLezen('Y');
$definitievekandidaat->setervaring('musicals en dergelijke');
$definitievekandidaat->setzangles('Y');
$definitievekandidaat->setinstrument('Ja, gitaar');
$definitievekandidaat->setErvaringInstrument('weinig');
$definitievekandidaat->setMotivatie('tja ik wil wel maar kan ik?');

print_r($definitievekandidaat);

$validator = new defKandidaatValidator();
print_r($validator->validateObject($definitievekandidaat));
*/
?>