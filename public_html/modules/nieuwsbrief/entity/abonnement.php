<?php
	###Abonnement
	
	class abonnement
	{
		###Private variabelen
		private $id;
		private $naam;
		
		###Public variables
		
		###Public methods
		function __CONSTRUCT($id,$naam)
		{
			##$id moet een integer zijn
			if(is_int($id))
			{
				$this->id=$id;
			}
			else
			{
				throw new Exception("id must be an integer");
			}
			
			$this->naam = $naam;
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		public function getNaam()
		{
			return $this->naam;
		}
	}
?>