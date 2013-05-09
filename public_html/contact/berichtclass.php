<?php
class bericht
{
	//Declaratie van privates
	private $afzender;
	private $mailadres;
	private $bericht;
	
	//Constructor
	public function __CONSTRUCT($afzender,$mailadres,$bericht)
	{
		##De parameters worden in de velden van de klasse opgeslagen
		$this->afzender = $afzender;
		$this->mailadres = $mailadres;
		$this->bericht = $bericht;
	}
	
	public function getAfzender()
	{
		return $this->afzender;
	}
	
	public function getMailadres()
	{
		return $this->mailadres;
	}
	
	public function getBericht()
	{
		return $this->bericht;
	}
}
?>