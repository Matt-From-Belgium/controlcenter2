<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
class parameter
{
#Definitie van privates
Private $id;
Private $name;
Private $value;
Private $environmental;
Private $overridable;

#Constructor
function __construct($id=-1,$environmental=false)
{
	#Enkel numerieke id's toegelaten.
	if (is_numeric($id))
	{
		$this->id = $id;
	}
	else
	{
            throw new Exception("The id that was given is not numeric");
	}
        
        if(is_bool($environmental))
        {
            $this->environmental=$environmental;
        }
        else
        {
            throw new Exception("The environmental argument must be a boolean");
        }
}

public function getId()
{
	return $this->id;
}

public function getName()
{
	return $this->name;
}

public function setName($value)
{
	$this->name = $value;
}

public function getValue()
{
	return $this->value;
}

public function setValue($value)
{
	$this->value = $value;
}

public function getOverridable()
{
	return $this->overridable;
}

public function setOverridable($value)
{
	#de waarde die wordt aangeleverd is true of false maar MySQL werkt met 0 of 1 => er moet een omzetting gebeuren
	#Enkel booleans toegestaan. Eventueel kan de aangeleverde waarde ook 0 of 1 zijn.
	if(is_bool($value))
	{
		if(!$value)
		{
		$boolstring = 0;
		}
		else
		{
		$boolstring = 1;
		}
		$this->overridable = $boolstring;
	}
	elseif($value==0 or $value==1)
	{
	#als de aangeleverde waarde 0 of 1 is dan moet er geen conversie gebeuren.
		$this->overridable = $value;
	}
	else
	{
		throw new Exception("the overridable argument must be a boolean");
	}
}

public function getEnvironmental()
{
    ###Deze parameter geeft aan of de parameter rechtstreeks in een template mag weergegeven worden
    ###door gebruik van een custom tag
    return $this->environmental;
}
}
?>