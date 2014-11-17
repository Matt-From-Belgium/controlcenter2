<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/validator.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";

class UserValidator extends Validator
{
	public function __construct()
	{
		#De klasse validator vereist een parameter bij zijn constructie
		Validator::__construct("uservalidator");
	}
	
	private function ValidateUsername($value,$id)
	{
		if(empty($value))
		{
			$returnmessage['fieldname'] = "username";
			$returnmessage['message'] = LANG_ERROR_USERNAME_EMPTY;
			return $returnmessage;
		}
		else
		{
			$userexists = dataaccess_Userexists($value,$id);
			if($userexists=="true")
			{
				$returnmessage['fieldname'] = "username";
				$returnmessage['message'] = LANG_ERROR_USERNAME_EXISTS;
				return $returnmessage;
			}
			else
			{
			return false;
			}
		}
	}
	
	private function ValidateMail($value,$id)
	{
		$value = strtolower($value);
		if(empty($value))
		{
			$returnmessage['fieldname'] = "mail";
			$returnmessage['message'] = LANG_ERROR_MAIL_EMPTY;
			return $returnmessage;
		}
		else
		{
			##Mailadress is niet leeg => nakijken of er al een gebruiker is met dit mailadres.
			if(dataaccess_MailadressExists($value,$id))
			{
				$returnmessage['fieldname'] = "mail";
				$returnmessage['message'] = LANG_ERROR_MAILADRESS_EXISTS;
				return $returnmessage;
			}
			else
			{
				return false;
			}
		}
	}
	
	private function ValidatePassword($value,$id)
	{
		###Het is niet verplicht om een wachtwoord op te geven bij het wijzigen van een gebruiker
		if($id==-1)
		{
                        #De waarde waarop getest wordt is de hash van null
			if($value=='cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e')
			{
				$returnmessage['fieldname'] = "passwordinvalid";
				$returnmessage['message'] = LANG_ERROR_PASSWORD_EMPTY;
				return $returnmessage;
			}
		}
	}
	
	private function ValidateFirstname($value)
	{
		if(empty($value))
		{
			$returnmessage['fieldname'] = "firstname";
			$returnmessage['message'] = LANG_ERROR_FIRSTNAME_EMPTY;
			return $returnmessage;
		}
	}
	
	private function ValidateLastname($value)
	{
		if(empty($value))
		{
			$returnmessage['fieldname'] = "lastname";
			$returnmessage['message'] = LANG_ERROR_LASTNAME_EMPTY;
			return $returnmessage;
		}
	}
        
        public function ValidateFacebookID($fbid,$id)
        {
             if(!dataaccess_FacebookIdUnique($fbid,$id))
             {
                 $returnmessage['fieldname'] = "facebookid";
		 $returnmessage['message'] = LANG_ERROR_FACEBOOK_DUPLICATE_ID;
		 return $returnmessage;
             }

        }
	

	public function ValidateObject($object)
	{
	##Deze functie wordt gebruikt bij de traditionele validatie. Een object met alle waarden die werden ingevoerd
	##wordt opgegeven, de velden worden één voor één gevalideerd. De meldingen worden opgeslagen in een array $errors
	##en teruggegeven.
		
	##Eerst en vooral wordt gecontroleerd of het aangeleverde object wel degelijk een instantie is van user
		if($object instanceof user)
		{
			$id = $object->getId();
                        
			##Validatie wordt gestart
                        $facebookId = $object->getFacebookID();
                        if(empty($facebookId))
                        {
                            ###Het gaat niet om een Facebook registratie
                            $errormessages[] = $this->ValidateUsername($object->getUsername(),$id);
                            $errormessages[] = $this->ValidateMail($object->getMailadress(),$id);
                            $errormessages[] = $this->ValidateFirstname($object->GetRealFirstname());
                            $errormessages[] = $this->ValidateLastname($object->getRealname());
                        }
                        else
                        {
                            ###Het gaat wel om facebook login
                            #Dus moet er minder gecontroleerd worden, of op een andere manier
                            
                            #We controleren username en mail omdat die manueel gewijzigd mogen worden
                            $errormessages[] = $this->ValidateUsername($object->getUsername(),$id);
                            $errormessages[] = $this->ValidateMail($object->getMailadress(),$id);
                        
                            #We controleren ook of er nog geen gebruiker is met dit facebook-profiel
                            $errormessages[] = $this->ValidateFacebookID($object->getFacebookID(),$id);
                            
                            
                        }
                        
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
			throw new Exception("You tried to execute function ValidateObject without a valid userobject as argument");
		}
	}
	
	public function ValidateField($field,$value,$id,$mode="xml")
	{
	##Dit is de functie die gebruikt wordt bij validatie via AJAX. Er wordt een veld en een waarde doorgegeven
	##Deze wordt met behulp van de interne functies gevalideerd en een XML-antwoord wordt teruggegeven.
		switch($field)
		{
			case "username":
				$errormessage = $this->ValidateUsername($value,$id);
			break;
			case "mail":
				$errormessage = $this->ValidateMail($value,$id);
			break;
			case "password":
				$errormessage = $this->ValidatePassword($value,$id);
			break;
			case "firstname":
				$errormessage = $this->ValidateFirstname($value);
			break;
			case "lastname":
				$errormessage = $this->ValidateLastname($value);
			break;
			case "website":
				$errormessage = $this->ValidateWebsite($value);
			break;
			case "country":
				$errormessage = $this->ValidateCountry($value);
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