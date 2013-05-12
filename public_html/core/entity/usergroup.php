<?php
class usergroup
{
	###Definitie privates
	private $id;
	private $name;
	private $permissionlist;
	
	###Constructor
	public function __CONSTRUCT($id=-1)
	{
		##Het id moet meegegeven worden bij constructie want dit kan achteraf niet meer gewijzigd worden.
			$this->id = $id;
	}
	
	##Public Methods
	public function getId()
	{
		return $this->id;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($newname)
	{
		$this->name = strtolower($newname);
	}
	
	public function setPermissions($permissionarray)
	{
		###Deze functie accepteert een array met idnummers van tasks
		if(is_array($permissionarray))
		{
			#De array wordt toegekend aan het veld $this->permissionlist.
			$this->permissionlist = $permissionarray;
		}
		else
		{
			throw new Exception("You tried to execute Usergroup::setPermissions with a parameter that is not an array");
		}
	}
	
	public function getPermissions()
	{
		###Deze array retourneert de permissionarray
		return $this->permissionlist;
	}

}

?>
