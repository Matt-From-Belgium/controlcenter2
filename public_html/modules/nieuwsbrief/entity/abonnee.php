<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/abonnement.php';

	class abonnee
	{
		###Private properties
		private $id;
		private $voornaam;
		private $familienaam;
		private $mailadres;
		private $secretkey;
		private $confirmed;
		private $subscriptionlist=array();
		
		###Constructor
		function __CONSTRUCT($id,$voornaam,$familienaam,$mailadres,$secretkey,$confirmed=false)
		{
			if(is_int($id))
			{
				$this->id = $id;
			}
			else
			{
				throw new Exception('$id must be an integer');
			}
			
			$this->secretkey=$key;
			
			$this->setVoornaam($voornaam);
			$this->setFamilienaam($familienaam);
			$this->setMailadres($mailadres);
			$this->secretkey = $secretkey;
			$this->confirmed = $confirmed;
		}
		
		###Public methods
		public function getID()
		{
			return $this->id;
		}
		
		public function setVoornaam($voornaam)
		{
			$this->voornaam=$voornaam;
		}
		
		public function getVoornaam()
		{
			return $this->voornaam;
		}
		
		public function setFamilienaam($familienaam)
		{
			$this->familienaam=$familienaam;
		}
		
		public function getFamilienaam()
		{
			return $this->familienaam;
		}
		
		public function setMailadres($mail)
		{
			$this->mailadres = $mail;
		}
		
		public function getMailadres()
		{
			return $this->mailadres;
		}
		
		public function checkKey($key)
		{
			###Deze functie geeft true of false terug 
			if($key==$this->secretkey)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public function getKey()
		{
			return $this->secretkey;
		}
		
		public function editSubscriptions(array $abonnementen)
		{
			$this->subscriptionlist=$abonnementen;
		}
		
		public function getSubscriptions()
		{
			return $this->subscriptionlist;
		}
	}

