<?php
$directoryvanditscript = dirname(__FILE__);
$directoryvanditscript = explode('/modules',$directoryvanditscript);
$_SERVER['DOCUMENT_ROOT']=$directoryvanditscript[0];

###Cron: gaat na welke nieuwsbrieven nog moeten verstuurd worden en genereert en verstuurt de mails

require_once $_SERVER['DOCUMENT_ROOT'].'/core/email/email.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/data/datafuncties.php';

###Stap 1: De nieuwsbrieven ophalen die nog niet verstuurd werden
$nieuwsbrieven = data_getNieuwsbrieven();


###Stap2: filteren welke nieuwsbrieven nog niet verstuurd werden
$teversturen = array();

echo "Versturen van nieuwsbrieven gestart<br>";

foreach($nieuwsbrieven as $nieuwsbrief)
{
    if($nieuwsbrief->getVerstuurd()==FALSE)
    {
        ###Deze nieuwsbrief moet nog verstuurd worden
        $teversturen[] = $nieuwsbrief;
    }
}

###Stap3: nu moeten we voor iedere nieuwsbrief achterhalen wie deze moet ontvangen en de nieuwsbrief versturen
foreach($teversturen as $nieuwsbrief)
{
    ###Voor iedere nieuwsbrief moeten we nagaan wie deze moet ontvangen
    /*SELECT * from nieuwsbrieven LEFT JOIN nieuwsbriefabonnementen ON nieuwsbrieven.id = nieuwsbriefabonnementen.nieuwsbrief LEFT JOIN abonnementenlink ON nieuwsbriefabonnementen.abonnement = abonnementenlink.abonnement LEFT JOIN abonnees ON abonnees.id=abonnementenlink.abonnee WHERE nieuwsbrieven.id=1*/    
    if($abonnees = data_getNieuwsbriefAbonnees($nieuwsbrief))
    {
            echo "Nieuwsbrief ".$nieuwsbrief->getTitel()." wordt verstuurd aan ". count($abonnees) . " abonnees";
            ###Nu moeten we de nieuwsbrief versturen naar iedere abonnee
            foreach($abonnees as $abonnee)
            {
                $mail = new Email();
                $mail->setSubject("jestaatnietalleen.be: Nieuwsbrief");
                $mail->setTo($abonnee->getMailadres());
                $mail->setMessageAddin('/modules/nieuwsbrief/addins/nieuwsbriefmail.tpa');
                $nieuwsbriefurl = 'http://www.jestaatnietalleen.be/modules/nieuwsbrief/nieuwsbrieven/'.$nieuwsbrief->getID().'.pdf';
                $mail->setVariable('nieuwsbriefurl', $nieuwsbriefurl);
                $mail->setVariable('key',$abonnee->getKey());
                $mail->Send();
            }
    }
       
    ###Nieuwsbrief is verstuurd, status aanpassen in de databank zodat deze niet nog eens verzonden wordt
    data_setNieuwsBriefStatusSent($nieuwsbrief);
}
?>
