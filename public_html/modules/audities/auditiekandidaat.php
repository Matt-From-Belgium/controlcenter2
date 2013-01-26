<?php

class auditieKandidaat
{
	###Privates
	private $id=-1;
	private $key;
	private $voornaam;
	private $naam;
	private $mailadres;
	private $stemgroepen=array();
	private $stemgroep;
	
	public function __CONSTRUCT($voornaam,$naam,$mailadres,$stemgroep,$secretkey="")
	{
		$this->stemgroepen[1] = "Sopraan";
		$this->stemgroepen[2] = "Mezzo";
		$this->stemgroepen[3] = "Alt";
		$this->stemgroepen[4] = "Tenor";
		$this->stemgroepen[5] = "Bariton";
		$this->stemgroepen[6] = "Bas";
		
		$this->voornaam= $voornaam;
		$this->naam=$naam;
		$this->mailadres = strtolower($mailadres);
		$this->stemgroep = $stemgroep;
		
		###Als secretkey niet werd opgegeven moet er een nieuwe sleutel aangemaakt worden
		if(empty($secretkey))
		{
			$tohash = $mailadres.time();
			$this->key = md5($tohash);
		}
		else
		{
			$this->key = $secretkey;
		}
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id=$id;
	}
	
	public function getVoornaam()
	{
		return $this->voornaam;
	}
	
	public function getNaam()
	{
		return $this->naam;
	}
	
	public function getKey()
	{
		return $this->key;
	}
	
	public function getMailadres()
	{
		return $this->mailadres;
	}
	
	public function getStemgroep()
	{
		return $this->stemgroepen[$this->stemgroep];
	}
	
	public function getStemgroepInt()
	{
		return $this->stemgroep;
	}
}

class defAuditieKandidaat extends auditieKandidaat
{
	### privates
	private $adres;
	private $gsm;
	private $geboortedatum;
	private $hoogstenoot;
	private $laagstenoot;
	private $partiturenlezen;
	private $ervaring;
	private $zangles;
	private $instrument;
	private $ervaringinstrument;
	private $motivatie;
	
	###public functions
	public function __CONSTRUCT(auditieKandidaat $kandidaat)
	{
		
		auditieKandidaat::__CONSTRUCT($kandidaat->getVoornaam(),$kandidaat->getNaam(),$kandidaat->getMailadres(),$kandidaat->getStemgroepInt(),$kandidaat->getKey());

	}
	
	public function setAdres($adres)
	{
		$this->adres = $adres;
	}
	
	public function getAdres()
	{
		return $this->adres;
	}
	
	public function setGSM($nummer)
	{
		$this->gsm = $nummer;
	}
	
	public function getGSM()
	{
		return $this->gsm;
	}
	
	public function setGeboortedatum($jaar,$maand,$dag)
	{
		$this->geboortedatum=$jaar.'/'.$maand.'/'.$dag;
	}
	
	public function getGeboortedatum()
	{
		return $this->geboortedatum;
	}
	
	public function setHoogsteNoot($hoogstenoot)
	{
		$this->hoogstenoot=$hoogstenoot;
	}
	
	public function getHoogsteNoot()
	{
		return $this->hoogstenoot;
	}
	
	public function setLaagsteNoot($laatstenoot)
	{
		$this->laagstenoot=$laatstenoot;
	}
	
	public function getLaagsteNoot()
	{
		return $this->laagstenoot;
	}
	
	public function setPartiturenLezen($bool)
	{
		$this->partiturenlezen=$bool;
	}
	
	public function getPartiturenLezen()
	{
		return $this->partiturenlezen;
	}
	
	public function setErvaring($ervaring)
	{
		$this->ervaring=$ervaring;
	}
	
	public function getErvaring()
	{
		return $this->ervaring;
	}
	
	public function setZangles($bool)
	{
		$this->zangles=$bool;
	}
	
	public function getZangles()
	{
		return $this->zangles;
	}
	
	public function setInstrument($instrument)
	{
		$this->instrument = $instrument;
	}
	
	public function getInstrument()
	{
		return $this->instrument;
	}
	
	public function setErvaringInstrument($ervaringinstrument)
	{
		$this->ervaringinstrument = $ervaringinstrument;
	}
	
	public function getErvaringInstrument()
	{
		return $this->ervaringinstrument;
	}
	
	public function setMotivatie($motivatie)
	{
		$this->motivatie = $motivatie;
	}
	
	public function getMotivatie()
	{
		return $this->motivatie;
	}
}
/*
###DEBUG
$nieuweKandidaat = new auditieKandidaat("Matthias","Bauw","matthias.bauw@gmail.com",4);
echo $nieuweKandidaat->getStemgroep();
print_r($nieuweKandidaat);
*/

/*
###DEBUG extended class
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/audities/auditielogic.php';

$kandidaat = checkKey("1eb318791d401fddfc53686a75075b08");

$definitievekandidaat = new defAuditieKandidaat($kandidaat);
print_r($definitievekandidaat);
*/
?>