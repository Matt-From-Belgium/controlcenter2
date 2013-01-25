<?php
##De abstracte klasse validator wordt gebruikt voor de validatie van gegevens
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/languages/languages.php";
getLanguageFiles();

abstract class Validator
{
	private $validatorname;
	
	public function __construct($validatorname)
	{
		#Het enige wat bij de constructie gebeurt is het definiëren van de variabele $this->validatorname
		$this->validatorname = $validatorname;
	}
	
	
	public function GenerateXMLResponse($fieldname,$message)
	{
		##Deze functie genereert een XMLfile die eventueel teruggegeven kan worden voor AJAX-functionaliteit
		$domdocument = new DomDocument();

		$maintag = $domdocument->CreateElement("response");
		$validatorattribute = $domdocument->createattribute("validator");
		$validatorvalue = $domdocument->createTextNode($this->validatorname);
		
		$validatorattribute->appendChild($validatorvalue);
		$maintag->appendChild($validatorattribute);
		
		$fieldnametag = $domdocument->CreateElement("fieldname");
		$fieldnametag->appendChild($domdocument->CreateTextNode($fieldname));
		
		$messagetag = $domdocument->CreateElement("message");
		$messagetag->appendChild($domdocument->CreateTextNode($message));
		
		$maintag->appendChild($fieldnametag);
		$maintag->appendChild($messagetag);
		
		$domdocument->appendChild($maintag);
		
		return $domdocument->SaveXML();
	}
	
	public abstract function ValidateObject($object);
	public abstract function ValidateField($field,$value,$identifier);
}
?>