<?php
class CC2Exception extends Exception
{
	private $extendedmessage;
	#shortmessage en extendedmessage worden verplicht
	#errorcode blijft optioneel
	public function __construct($shortmessage,$extendedmessage,$errorcode=0)
	{
		parent::__construct($shortmessage,$errorcode);
		
		#extendedmessage moet worden opgeslagen in een nieuwe variabele
		$this->extendedmessage = $extendedmessage;
	}
	
	public function getExtendedmessage()
	{
	return $this->extendedmessage;
	}
}
?>
