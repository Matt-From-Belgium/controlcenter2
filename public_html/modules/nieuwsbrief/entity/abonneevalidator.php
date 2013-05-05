<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/abonnee.php';

class abonneeValidator
{
	
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
	
	public function validateObject(abonnee $abonnee)
	{
		###object valideren
			$errorlist = array();
			
			$errorlist[] = $this->validateVoornaam($abonnee->getVoornaam());
			$errorlist[] = $this->validateFamilienaam($abonnee->getFamilienaam());
			$errorlist[] = $this->validateMailadres($abonnee->getMailadres());
			
			###Lege items uit de array gooien
			$errorlist = array_filter($errorlist);
			
			return $errorlist;
	}
	
	public function validateVoornaam($voornaam)
	{
		if(empty($voornaam))
		{
			$newerror['field']="voornaam";
			$newerror['message']="Je bent verplicht je voornaam op te geven";
			
			return $newerror;
		}
	}
	
	public function validateFamilienaam($familienaam)
	{
		if(empty($familienaam))
		{
			$newerror['field']="familienaam";
			$newerror['message']="Je bent verplicht je familienaam op te geven";
			
			return $newerror;
		}
	}
	
	public function validateMailadres($mailadres)
	{
		if(empty($mailadres))
		{
			$newerror['field']="mail";
			$newerror['message']="Je bent verplicht een mailadres op te geven";
			
			return $newerror;
		}
		else
		{
			###Er is iets ingevuld, maar is het juist?
			if(!$this->validEmail($mailadres))
			{
			$newerror['field']="mail";
			$newerror['message']="Dit mailadres is niet correct";		
			
			return $newerror;		
			}
			else
			{
				###Het mailadres is ingevuld en het is geldig, maar het zou al in de databank kunnen voorkomen
				if(data_dubbelMailadres($mailadres))
				{
					$newerror['field']="mail";
					$newerror['message']="Dit mailadres bestaat reeds in onze databank";						
					
					return $newerror;
				}
			}
		}
	}

}