<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/beatleslogic.php';

$html = new htmlpage('frontend');
$html->LoadAddin('/addins/beatles.tpa');
$html->loadScript('/modules/fotoalbum/presentation/showalbum.js');
$html->loadScript('/scripts/beatles.js');
$html->loadScript('/core/presentation/usermanagement/accounts/fbRegister.js');

if(!isset($_GET['id']))
{
$html->addCustomMeta('og:type', article);
$html->addCustomMeta('og:url', 'http://www.projectkoorchantage.be/beatles.php');
$html->addCustomMeta('og:title','Projectkoor CHANTage brengt Beatles-concert');
$html->addCustomMeta('og:image', 'http://www.projectkoorchantage.be/images/beatles/beatlesFB.png');
$html->addCustomMeta('og:description',"In 1964 beleefden 'The Beatles' hun grote doorbraak. Ze werden de grondleggers van de hedendaagse popmuziek. En toch... zo heeft u hen nog nooit gehoord. Projectkoor CHANTage brengt u een mix van hun meest gekende nummers.");
}
else
{
    ###ER is een id gezet, dus dit is een link van iemand die via FB zijn keuze heeft gedeeld
    $selectedsong = searchBeatlesSongById($_GET['id']);
    
    $html->addCustomMeta('og:type', song);
    $html->addCustomMeta('og:url', "http://www.projectkoorchantage.be/beatles.php?id=".$selectedsong->getID());
    $html->addCustomMeta('og:title',"Mijn favoriete Beatles-hit: ".$selectedsong->getTitle());
    $html->addCustomMeta('og:image', 'http://www.projectkoorchantage.be/images/beatles/beatlesFB.png');
    $html->addCustomMeta('og:description',"Wat is jouw favoriete Beatles-hit? Stem hier!");

}
$html->PrintHTML();
?>
