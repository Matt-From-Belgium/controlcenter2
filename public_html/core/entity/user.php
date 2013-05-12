<?php
class User
{
#Deze klasse wordt gebruikt voor het werken met gebruikers.

###DEFINITIE VAN PRIVATES
	private $id="";
	private $username="";
	private $realname="";
	private $realfirstname="";
	private $mailadress="";
	private $website="";
	private $country="";
	private $usergroups="";
	private $userconfirmation="0";
	private $adminconfirmation="0";
	private $passwordchangerequired="0";

###CONSTRUCTOR FUNCTIONS
	function __construct($username,$id=-1)
	{
		$this->id = $id;
		$this->username = $username;
	}
	
###Publieke methoden
	public function getId()
	{
		return $this->id;
	}

	public function getUsername()
	{
		return $this->username;
	}
	
	public function setRealname($value)
	{
		$this->realname = $value;
	}
	
	public function getRealname()
	{	
		return $this->realname;
	}
	
	public function setRealFirstname($value)
	{
		$this->realfirstname=$value;
	}
	
	public function getRealFirstname()
	{
		return $this->realfirstname;
	}
	
	public function setMailadress($value)
	{
		$this->mailadress = strtolower($value);
	}
	
	public function getMailadress()
	{
		return $this->mailadress;
	}
	
	public function setWebsite($value)
	{
		$this->website = strtolower($value);
	}
	
	public function getWebsite()
	{
		return $this->website;
	}
	
	public function setCountry($value)
	{
		$this->country = $value;
	}
	
	public function getCountry()
	{
		return $this->country;
	}
	
	public function setUserConfirmationStatus($value)
	{
		if($value == 0 or $value == 1)
		{
			$this->userconfirmation = $value;
		}
		else
		{
			throw new Exception('You tried to use the SetUserConfirmationStatus with a parameter that is 0 or 1');			
		}
	}
	
	public function setAdminConfirmationStatus($value)
	{
		if($value == 0 or $value == 1)
		{
			$this->adminconfirmation = $value;
		}
		else
		{
			throw new Exception('You tried to use the SetAdminConfirmationStatus with a parameter that is not 0 or 1');			
		}
	}
	
	public function getUserConfirmationStatus()
	{
		return $this->userconfirmation;
	}
	
	public function getAdminConfirmationStatus()
	{
		return $this->adminconfirmation;
	}
	
	public function setUsergroups($grouparray)
	{
		if(is_array($grouparray))
		{
			###Er werd een array aangeleverd => ok
			$this->usergroups = $grouparray;
		}
		else
		{
			###geen array => exception
			throw new Exception("You tried to execute setUsergroups() without an array");
		}
	}
	
	public function getUsergroups()
	{
		return $this->usergroups;
	}
	
	public function getPasswordchangeRequired()
	{
		return $this->passwordchangerequired;
	}
	
	public function setPasswordchangeRequired($value)
	{
		if($value == true || $value == false)
		{
			if($value)
			{
				$this->passwordchangerequired=1;
			}
			else
			{
				$this->passwordchangerequired=0;
			}
		}
		else
		{
			throw new Exception("setPasswordchangeRequired only accepts 'true' or 'false' as argument");
		}
	}
}
?>