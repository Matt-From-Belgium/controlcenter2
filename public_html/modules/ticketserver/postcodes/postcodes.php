<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/exception.php';

###Eerst wordt nagekeken of er een $_POST['postcode'] is
if(isset($_POST['postcode']))
{
	$db= new dataconnection();
	$query = "SELECT id,postcode,gemeente from postcodes WHERE postcodes.postcode LIKE '@invoer%'";
	$db->setQuery($query);
	
	$db->setAttribute("invoer",$_POST['postcode']);

	$db->executeQuery();

	$result = $db->getResultArray();
	
	header('Content-type: text/xml');
	$response = new DomDocument('1.0');
	
	$root = $response->createElement('postcodes');

	
	foreach($result as $key=>$value)
	{
		list($id,$postcode,$gemeente)=$value;
		
		$result = $response->createElement('result');
		
		$idnode = $response->createElement('id');
		$idresult = $response->createTextNode($id);
		$idnode->appendChild($idresult);
		
		$postcodenode = $response->createElement('postcode');
		$postcoderesult = $response->createTextNode($postcode);
		$postcodenode->appendChild($postcoderesult);
		
		$gemeentenode = $response->createElement('gemeente');
		$gemeenteresult = $response->createTextNode($gemeente);
		$gemeentenode->appendChild($gemeenteresult);
		
		$result->appendChild($idnode);
		$result->appendChild($postcodenode);
		$result->appendChild($gemeentenode);
	
		$root->appendChild($result);
	}
	
		$response->appendChild($root);
	
	echo $response->saveXML();
}
?>