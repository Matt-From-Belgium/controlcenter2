<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";

class Email
{
	###Definitie van privates
	private $from;
	private $to;
	private $subject;
	private $message;
	private $templatealias;
	private $addin;
	private $variables;
	
	function __CONSTRUCT($templatealias = "mail")
	{
		$this->from = getServerMailadress();
		$this->templatealias = $templatealias;
	}
	
	function setTo($to)
	{	
		$this->to = $to;
	}
	
	function setFrom($from)
	{
		$this->from = $from;
	}
	
	function setSubject($subject)
	{
		$this->subject = $subject;
	}
	
	function setMessageText($message)
	{
		$this->message = $message;
	}
	
	function setVariable($variable,$value)
	{
		$this->variables[$variable] = $value;
	}
	
	function setMessageAddin($addinpath)
	{
		###Het pad naar de addin wordt gebruikt en opgeslagen in de private
		#klassevariabele. Bij $this->Send() wordt dan gebruik gemaakt van de addinfunctie van de klasse htmlpage
		$this->addin = $addinpath;
	}
	
	function Send()
	{
		$html = new htmlpage($this->templatealias,TRUE);
		
		###Er wordt nagegaan of er een addin werd gedefinieerd, als dat niet het geval is wordt de tekst van $this->message
		#ingevoegd in de mail.
		
		if(isset($this->addin))
		{
			$html->LoadAddin($this->addin);
		}
		else
		{
		$html->insertCode($this->message);
		}
		
		if(is_array($this->variables))
		{
			foreach($this->variables as $variable=>$value)
			{
				$html->setVariable($variable,$value);
			}
		}
		
		$htmlcode = $html->getHTML();
	
		$headers  = "MIME-Version: 1.0 \r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1 \r\n";
		$headers .= "From: ".$this->from;
		mail($this->to,$this->subject,$htmlcode,$headers);
	}
}
?>