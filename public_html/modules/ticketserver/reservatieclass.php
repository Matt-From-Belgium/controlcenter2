<?php
class reservatie
{
	###Definitie van privates
	private $id;
	private $naam;
	private $datum;
	private $voornaam;
	private $mailadres;
	private $aantaltickets;
	private $voorstelling;
	private $status;
	private $referral;
	private $opmerkingen;
	private $straat;
	private $huisnr;
	private $gemeente;
	
	public function __CONSTRUCT($id=-1)
	{
		###Voor een nieuwe reservatie wordt $id op -1 gezet. Voor andere reservaties kan het id meegegeven worden bij constructie
		$this->id=$id;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setNaam($naam)
	{
		$this->naam=$naam;
	}
	
	public function getNaam()
	{
		return $this->naam;
	}
	
	public function setVoornaam($voornaam)
	{
		$this->voornaam = $voornaam;
	}
	
	public function getVoornaam()
	{
		return $this->voornaam;
	}
	
	public function getDatum()
	{
		return $this->datum;
	}
	
	public function setDatum($value)
	{
		$this->datum = $value;
	}
	
	public function setAantalTickets($aantal)
	{
		$this->aantaltickets = $aantal;
	}
	
	public function getAantalTickets()
	{
		return $this->aantaltickets;
	}
	
	public function setVoorstelling($voorstelling)
	{
		$this->voorstelling=$voorstelling;
	}
	
	public function getVoorstelling()
	{
		return $this->voorstelling;
	}
	
	public function setReferral($value)
	{
		$this->referral= $value;
	}
	
	public function getReferral()
	{
		return $this->referral;
	}
	
	public function setOpmerkingen($value)
	{
		$this->opmerkingen = $value;
	}
	
	public function getOpmerkingen()
	{
		return $this->opmerkingen;
	}
	
	public function setMailadres($value)
	{
		$this->mailadres = $value;
	}
	
	public function getMailadres()
	{
		return $this->mailadres;
	}
	
	public function getVoorstellingTekst()
	{
		switch($this->voorstelling)
		{
			case 1:
				return "9 april 2010 om 20u";
			break;
			
			case 2:
				return "10 april 2010 om 20u";
			break;
			
			case 3:
				return "16 april 2010 om 20u";
			break;
			
			case 4:
				return "17 april 2010 om 20u";
			break;
		}
	}
	
	public function setStatus($value)
	{
		$this->status=$value;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function getStraat()
	{
		return $this->straat;
	}
	
	public function setStraat($value)
	{
		$this->straat=$value;
	}
	
	public function getHuisNr()
	{
		return $this->huisnummer;
		
	}
	
	public function setHuisNr($value)
	{
		$this->huisnummer = $value;
	}
	
	public function getGemeente()
	{
		return $this->gemeente;
	}
	
	public function setGemeente($value)
	{
		$this->gemeente = $value;
	}
}
?>