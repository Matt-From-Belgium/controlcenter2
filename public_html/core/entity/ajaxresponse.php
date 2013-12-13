<?php

class ajaxResponse
{
	###private variabelen
	private $xmlroot;
	private $velden;
	private $data;
	private $status;
	private $responsetag;
	private $errors;
	
	function __CONSTRUCT($status)
	{
		$this->status = $status;
		$this->xmlroot = new DomDocument();
				
		###Er zijn momenteel 2 verschillende antwoordstatussen: ok en error
		###In geval van error moet er een foutmelding teruggegeven worden
		###In geval van ok moet er mogelijk (maar niet altijd) data teruggegeven worden
		
		###Alle antwoorden bevatten de <response></response> tag		
		$this->responsetag = $this->xmlroot->createElement("response");
		
		$resulttag = $this->xmlroot->createElement("result");
		$resulttag->appendChild($this->xmlroot->createTextNode($status));
		
		
		$this->responsetag->appendChild($resulttag);

	}
		
		public function getXML()
		{		
		###We moeten de resultaatXML nu gaan opbouwen
			if($this->status == "ok")
			{
				###Status is ok dus mogelijk moeten we data teruggeven als die er is
				if(count($this->data)>0)
				{
					###Er moet data teruggegeven worden. We beginnen met de creatie van de datareturn-tag
					$datareturn = $this->xmlroot->CreateElement("datareturn");
				
					###Nu beginnen we de items van $this->data te doorlopen en maken we lijnen aan
					foreach($this->data as $key=>$value)
					{
						$datarowtag = $this->xmlroot->CreateElement("datarow");
						
						foreach($this->velden as $veldkey=>$fieldname)
						{
							$fieldtag = $this->xmlroot->CreateElement($fieldname);
							$fieldtag->appendChild($this->xmlroot->CreateTextNode($this->data[$key][$fieldname]));
							$datarowtag->appendChild($fieldtag);
							
						}
						$datareturn->appendChild($datarowtag);
					}
					
					$this->responsetag->appendChild($datareturn);
				}
				
				$this->xmlroot->appendChild($this->responsetag);
			}
			elseif($this->status == "error")
			{
				if(count($this->errors)>0)
				{
					###Er zitten items in errors
					$errorlisttag = $this->xmlroot->createElement("errorlist");
					
					foreach($this->errors as $key=>$value)
					{
						$errortag=$this->xmlroot->createElement("error");
						
						$parametertag=$this->xmlroot->createElement("parameter");
						$parametertag->appendChild($this->xmlroot->createTextNode($this->errors[$key]['parameter']));
						
						$messagetag=$this->xmlroot->createElement("message");
						$messagetag->appendChild($this->xmlroot->createTextNode($this->errors[$key]['message']));
						
						$errortag->appendChild($parametertag);
						$errortag->appendChild($messagetag);
						
						$errorlisttag->appendChild($errortag);
					}
					
					$this->responsetag->appendChild($errorlisttag);
					$this->xmlroot->appendChild($this->responsetag);
				}
				else 
				{
					###Er zitten geen items in errors => fout
					throw new Exception("No errormessages set");
				}
			}
		
		header('Content-type: text/xml');
		echo $this->xmlroot->saveXML();		
		}
		
		public function addField($string)
		{
			###enkel wanneer status ok is
			if($this->status == "ok")
			{
				###Deze functie voegt een item toe aan het array velden.
                                ###OPGELET: VELDNAMEN ZIJN CASE SENSITIVE!
				$this->velden[] = $string;		
			}
			else 
			{
				throw new Exception("addField cannot be used when status is not ok");
			}
		}
		
		public function addData($array)
		{
		###Eerst kijken we naar de status. Als de status niet ok is kan er geen data zijn => exception
			if($this->status=="ok")
			{
			###De data wordt aangeleverd in een associatieve array 
			###TODO: later misschien dynamisch object programmeren waarmee data aangeleverd kan worden
				if(is_array($array))
				{
				
					###Nu gaan we controleren of alle keys van de array voorkomen in de array velden
					foreach($array as $key=>$value)
					{
						###We stellen de waarde van de variabele arrayok in op true
						###Wanneer we bij een iteratie een sleutel tegenkomen die niet geregistreerd is wordt deze false
						###Na de iteratie kunnen we dan aan de hand van de variabele vaststellen of alles goed is verlopen
						$arrayok = true;
					
						if(!in_array($key,$this->velden))
						{
							###Als de sleutel niet voorkomt in velden hebben we een probleem en moet er een exception komen
							throw new Exception("$key has not been registered as a field. use addField to register a new field");
							$arrayok=false;
						}
					}
				
					if($arrayok == true)
					{
                                                
						$this->data[] = $array;
					}
				}
				else 
				{
					throw new Exception("argument for addData() must be an array");								
				}
			}
			else 
			{
				throw new Exception("addData is not possible when status is not ok, use addError instead");	
			}
		}
		
		public function addErrorMessage($parameter,$message)
		{
			if($this->status=="error")
			{
				$newerror['parameter'] = $parameter;
				$newerror['message']=$message;
				
				$this->errors[] = $newerror;
			}
			else
			 {
			 	###Als de status niet error is kan deze niet gebruikt worden
			 	throw new Exception("addErrorMessage is not possible when status is not error, use addData instead");
			}
		}
}


?>