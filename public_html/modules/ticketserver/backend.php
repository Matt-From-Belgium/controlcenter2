<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/ticketserver/reservatielogic.php';

###Voor het bekijken van deze pagina is de toegang ticketserver::ticketadministratie nodig
checkPermission('ticketserver','ticketadministratie');

$html = new htmlpage('backend');
$html->loadAddin('/modules/ticketserver/backend.tpa');

###Voorstellingsgegevens ophalen
$voorstellingen = getVoorstellingen();

foreach($voorstellingen as $key=>$value)
{
	if($value['volzet']=='N')
	{

		$voorstellingen[$key]['beschikbaarflag']='CHECKED';
	}
	else
	{
		$voorstellingen[$key]['volzetflag']='CHECKED';
	}
}

$html->setVariable('voorstellingen',$voorstellingen);

$html->printHTML();
?>