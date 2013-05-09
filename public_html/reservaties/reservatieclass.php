<?php
class reservatie
{
	###Definitie van privates
	private $naam;
	private $voornaam;
	private $mailadres;
	private $aantaltickets;
	private $voorstelling;
	private $reservatietijdstip;
	private $referral;
	private $opmerkingen;
	
	public function __CONSTRUCT()
	{
		date_default_timezone_set('Europe/Madrid');
		$tijd = new DateTime();
		$tijdstring = $tijd->format('d/m/Y G:i');
		$this->reservatietijdstip=$tijdstring;
	}
	
	public function setNaam($naam)
	{
		$this->naam=$naam;
	}
	
	public function getNaam()
	{
		return $this->naam;
	}
	
	public function getEmail()
	{
		return $this->mailadres;
	}
	
	public function setVoornaam($voornaam)
	{
		$this->voornaam = $voornaam;
	}
	
	public function getVoornaam()
	{
		return $this->voornaam;
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
	
	public function getReservatieTijdstip()
	{
		return $this->reservatietijdstip;
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
	
}
?>