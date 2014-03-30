<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html = new htmlpage('frontend');
$html->LoadAddin('/addins/beatles.tpa');
$html->loadScript('/modules/fotoalbum/presentation/showalbum.js');
$html->loadScript('/scripts/beatles.js');
$html->loadScript('/core/presentation/usermanagement/accounts/fbRegister.js');

$html->addCustomMeta('og:type', article);
$html->addCustomMeta('og:url', 'http://www.projectkoorchantage.be/beatles.php');
$html->addCustomMeta('og:title','Projectkoor CHANTage brengt Beatles-concert');
$html->addCustomMeta('og:image', 'http://www.projectkoorchantage.be/images/beatles/beatlesFB.png');
$html->addCustomMeta('og:description',"In 1964 beleefden 'The Beatles' hun grote doorbraak. Ze werden de grondleggers van de hedendaagse popmuziek. En toch... zo heeft u hen nog nooit gehoord. Projectkoor CHANTage brengt u een mix van hun meest gekende nummers.");

$html->PrintHTML();
?>
