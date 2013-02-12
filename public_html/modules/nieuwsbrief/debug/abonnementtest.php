<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/abonnement.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/abonnee.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/data/datafuncties.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/logic/abonneevalidator.php';
	
$abonnee = new abonnee(-1,"Matthias","Bauw","matthias.bauw@gmail.com","ddd");
	
$abonnee = data_createAbonnee($abonnee);

$abonneeAgent = new abonneeValidator();
print_r($abonneeAgent->validateObject($abonnee));

$abonnement = new abonnement(-1,'testbrief');

$abonnement = data_createAbonnement($abonnement);

$lijst = data_getAbonnementenlijst();

#print_r($lijst);


/*$subscriptionlist[]=$lijst[0];
$subscriptionlist[]=$lijst[1];
$subscriptionlist[]=$lijst[2];
*/

#print_r($subscriptionlist);

$abonnee->editSubscriptions($lijst);

try
{
data_editAbonnee($abonnee);
}
catch(CC2Exception $ex)
{
	echo $ex->getExtendedMessage();
}
#print_r($abonnee);
?>