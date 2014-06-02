<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatesystem.php";
require_once $_SERVER['DOCUMENT_ROOT']."/modules/ticketserver/reservatielogic.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/core/presentation/general/commonfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/social/facebook/php/facebook.php';

if(getTicketSaleStarted())
{
    ###Er mogen tickets verkocht worden

    try
    {
    ###Is dit de eerste keer dat de pagina wordt weergegeven?
            if(isset($_POST['submitcheck']))
            {
                    $errormessages = ValidateReservatie($_POST);


                    $vflagstring = "v".$_POST['voorstelling']."selectedflag";

                    $rflagstring = "r".$_POST['referral']."selectedflag";

            }

            if((!isset($_POST['submitcheck'])) or (is_array($errormessages)))
            {
                    $html = new htmlpage("frontend");
                    $html->LoadAddin('/modules/ticketserver/bestel.tpa');
                    $html->loadScript('/core/presentation/ajax/ajaxtransaction.js');
                    $html->loadScript('/modules/ticketserver/javascript/tickets.js');
                    $html->loadScript('/modules/ticketserver/postcodes/postcodes.js');
                    $html->loadScript('/core/presentation/usermanagement/accounts/fbRegister.final.js');
                    $html->loadScript('/core/logic/common.js');
                    $html->loadCSS('/modules/ticketserver/tickets.css');
                    $html->loadCSS('/modules/ticketserver/postcodes/postcodeselector.css');
                    $html->setVariable("eventname",  getEventName());
                    $html->setVariable("ticketprijs", getEventPrice());
                    $html->setVariable('telefoonnr',  getTelephoneNR());
                    $html->setVariable("errorlist",$errormessages);
                    $html->setVariable("naam",$_POST['naam']);
                    $html->setVariable("email",$_POST['email']);
                    $html->setVariable("voornaam",$_POST['voornaam']);
                    $html->setVariable("aantal",$_POST['aantal']);	
                    $html->setVariable("opmerkingen",$_POST['opmerkingen']);
                    $html->setVariable("straat",$_POST['straat']);
                    $html->setVariable("huisnummer",$_POST['nummer']);
                    $html->setVariable("gemeente",$_POST['gemeente']);
                    $html->setVariable("postcode",$_POST['postcode']);
                    $html->setVariable("gemeentenaam",$_POST['gemeentenaamveld']);

                    ###Voorstellingen worden ingeladen
                    $voorstellingen = getVoorstellingen();

                    ###Omdat uitverkochte voorstellingen apart moeten gemarkeerd worden zijn er voor het array nog een aantal extra velden
                    ###die moeten aangepast worden
                    foreach($voorstellingen as $key=>$voorstelling)
                    {
                            ###Nagaan of de voorstelling actief is
                            if($voorstelling['voorstellingsnummer']==$_POST['voorstelling'])
                            {
                                    $voorstellingen[$key]['selectedflag']="checked";
                            }
                            if( $voorstelling['volzet']=='J')
                            {
                                    ###voorstelling is volzet
                                    $voorstellingen[$key]['disabledflag1']="DISABLED";
                                    $voorstellingen[$key]['disabledflag2']="<font color='#FF0000'>(UITVERKOCHT)</font>";
                                    $voorstellingen[$key]['disabledflag3']="class='uitverkocht'";
                            }
                    }


                    $html->setVariable('voorstellingen',$voorstellingen);

                    $html->setVariable($rflagstring,"selected");

                    $html->printHTML();
            }
            else
            {
                    try
                    {
                        ###Alles ok, nakijken of we bericht op facebook moeten plaatsen
                        if($_POST['shareOnFB'])
                        {
                            $config = array(
                                'appId'=>  getFacebookAppID(),
                                'secret'=> getFacebookSappId(),
                                'allowSignedRequest'=> false
                            );

                            $facebook = new Facebook($config);
                            $userid = $facebook->getUser();
                            $concerturl = getFBConcertUrl();

                            if($userid)
                            {
                                $response = $facebook->api('/me/'.getFacebookNameSpace().':reserve_for','POST',
                                   array(
                                       'concert'=> $concerturl,
                                       'fb:explicitly_shared'=>'true'
                                   )     
                                        );
                            }
                        }
                    }
                    catch(Exception $ex)
                    {
                        ###Dit staat er gewoon om zelfs in geval van problemen de bevestiging te tonen.
                        CC_Send_Error_report($ex);
                    }
                    
                    #Wijziging: bevestigingspagina wordt apart php pagina om tracking mogelijk te maken
                    #showMessage('Reservatie ontvangen','Wij hebben uw reservatie ontvangen. U ontvangt in de komende minuten een bevestigingsmail met betalingsgegevens.');
                    header('location:/modules/ticketserver/confirmation.php');
                    

                    ###De input was correct, er moet enkel een bevestigingspagina worden weergegeven.
                    #$html = new htmlpage("frontend");
                    #$html->LoadAddin("/addins/message.tpa");
                    #$html->setVariable("messagetitle","Reservatie ontvangen");
                    #$html->setVariable("message","Wij hebben uw reservatie ontvangen. U ontvangt in de komende minuten een bevestigingsmail met betalingsgegevens.");
                    #$html->PrintHTML();
            }
    }
    catch(CC2Exception $ex)
    {
            showMessage('Reservatie mislukt','er heeft zich een fout voorgedaan tijdens het reservatieproces. U kan uw reservatie telefonisch uitvoeren door te bellen naar onze ticketlijn op 051/501616 of u kan mailen naar <a href="mailto:info@3musketiers.be">info@3musketiers.be</a>');

            require_once $_SERVER['DOCUMENT_ROOT']."/core/email/email.php";
            $mailnaarwebmaster = new Email("mail");
            $mailnaarwebmaster->setTo('webmaster@detoverlantaarn.be');
            $mailnaarwebmaster->setSubject('OPGELET: Fout in het ticketsysteem!');


            foreach($_POST as $key=>$value)
            {
                    $errorstring .= "<tr><td><b>$key:</b></td><td>$value</td></tr>";
            }

            $errorstring = "<table>".$errorstring."</table>";

            $errorstring = "Er heeft zich een fout voorgedaan in het ticketsysteem, de gebruiker voerde volgende gegevens in:<p>".$errorstring;

            $errorstring .= "<p>Exceptionbericht:".$ex->getExtendedMessage();

            $mailnaarwebmaster->setMessageText($errorstring);
            $mailnaarwebmaster->Send();
    }
}
else
{
    showMessage('Ticketverkoop is nog niet gestart', 'Nog even geduld, de ticketverkoop is nog niet actief.');
}
            ?>