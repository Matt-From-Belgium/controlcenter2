<?php
class Nieuwsbrief
{
	###Privates
	private $id;
	private $maand;
	private $jaar;
	private $timestamp;
	
	private $titel;
        private $verstuurd;
	
	###Consctructor
	public function __CONSTRUCT($id,$maand,$jaar,$titel,$verstuurd=FALSE)
	{
		###Eventueel bestandsinfo buiten het object houden en enkel bestandspad opnemen?
		$this->setMaand($maand);
		$this->setJaar($jaar);
		$this->setAbonnementen($abonnementenarray);
		$this->setId($id);
                $this->setTitel($titel);
                $this->setVerstuurd($verstuurd);
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

	###Public Methods
	public function getMaand()
	{
		return $this->maand;
	}
	
	public function getJaar()
	{
		return $this->jaar;
	}

	
	public function getTimestamp()
	{
		return $this->timestamp;
	}
	
	public function getID()
	{
		return $this->id;
	}
        
        public function setTitel($titel)
        {
            $this->titel=$titel;
        }
        
        public function getTitel()
        {
            return $this->titel;
        }
        
        public function setVerstuurd($verstuurd)
        {
            if(is_bool($verstuurd))
            {
                $this->verstuurd=$verstuurd;    
            }
            else
            {
             throw new CC2Exception("Error in nieuwsbriefclass","setVerstuurd accepteert enkel boolean als argument");
            }
            
        }
        
        public function getVerstuurd()
        {
            return $this->verstuurd;
        }
}


?>