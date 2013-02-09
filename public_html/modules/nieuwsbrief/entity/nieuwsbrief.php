<?php
class Nieuwsbrief
{
	###Privates
	private $id;
	private $maand;
	private $jaar;
	private $timestamp;
	private $abonnementen = array();
	private $bestandspad;
	
	###Consctructor
	public function __CONSTRUCT($id,$maand,$jaar,$abonnementenarray)
	{
		###Eventueel bestandsinfo buiten het object houden en enkel bestandspad opnemen?
		$this->setMaand($maand);
		$this->setJaar($jaar);
		$this->setAbonnementen($abonnementenarray);
		$this->setId($id);
	}
	
	###Private Methods
	private function setMaand($maand)
	{
		if(is_int($maand))
		{
			$this->maand=$maand;
		}
		else
		{
			throw new Exception('Maand moet integer zijn');
		}
	}
	
	private function setJaar($jaar)
	{
		if(is_int($jaar))
		{
			$this->jaar = $jaar;
		}
		else
		{
			throw new Exception('Jaar moet integer zijn');
		}
		
	}
	
	private function setAbonnementen($abonnementen)
	{
		$this->abonnementen = $abonnementen;
	}
	
	private function setId($id)
	{
		if(is_int($id))
		{
			$this->id = $id;
		}
		else
		{
			throw new Exception('Id moet integer zijn');
		}		
	}
	###Public Methods
	public function getMaand()
	{
		return $this->maand;
	}
	
	public function getJaar()
	{
		return $this->jaar;
	}
	
	public function getAbonnementen()
	{
		return $this->abonnementen;
	}
	
	public function getTimestamp()
	{
		return $this->timestamp;
	}
	
	public function getID()
	{
		return $this->id;
	}
}


?>