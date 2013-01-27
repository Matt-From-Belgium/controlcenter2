<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/presentation/general/commonfunctions.php';


checkPermission('ticketserver','ticketadministratie');
if(isset($_POST['voorstelling']))
{
	###Er is data doorgestuurd => voorstellingen aanpassen
	$db = new dataconnection();
	
	$voorstellingen = $_POST['voorstelling'];
	
	foreach($voorstellingen as $key=>$value)
	{
		$query = "UPDATE voorstellingen SET voorstellingen.volzet='@waarde' WHERE voorstellingen.id=@id";
		$db->setQuery($query);
		
		$db->setAttribute('waarde',$value);
		$db->setAttribute('id',$key);
		$db->executeQuery();
	}
	
	showMessage('Voorstellinge aangepast','Uw wijzigingen werden opgeslagen','backend.php','Terug','backend');
}

?>