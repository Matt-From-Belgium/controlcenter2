<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/validator.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/usergroup.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/usermanagement/userfunctions.php";

class UsergroupValidator extends validator
{
	public function __CONSTRUCT()
	{
		$validatorname = "UsergroupValidator";
		Validator::__construct($validatorname);
	}
	
	private function validateGroupName($name,$id)
	{
		if(!empty($name))
		{
			if(dataaccess_UsergroupExists($name,$id))
			{
				$returnmessage['fieldname'] = "usergroupname";
				$returnmessage['message'] = "that usergroup exists";
				return $returnmessage;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$returnmessage['fieldname'] = "usergroupname";
			$returnmessage['message']= "You must specify a name for the usergroup";
			return $returnmessage;
		}
	}
	
	public function validateField($field,$value,$identifier,$mode="xml")
	{
		switch($field)
		{
			case "$usergroupname":
				$errormessage = $this->validateGroupName($value,$identifier);
			break;
			
			if($mode=="xml")
			{
				Validator::GenerateXMLResponse("usergroupname",$errormessage['message']);
			}
			else
			{
				return $errormessage;
			}
		}	
	}
	
	public function validateObject($object)
	{
		if($object instanceof usergroup)
		{
			$errormessages[] = $this->validateGroupName($object->getName(),$object->getId());
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
			throw new CC2Exception("The user management system caused an error","You tried to execute validateobject without a valid instance of usergroup");
		}
	}
}
?>