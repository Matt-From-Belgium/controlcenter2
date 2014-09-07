<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html = new htmlpage('frontend');
$html->LoadAddin('/addins/snaaskerke.tpa');

$html->addCustomMeta('og:type', $fbNameSpace.':concert');
$html->addCustomMeta('fb:app_id', $fbAppId);
$html->addCustomMeta('og:url', 'http://www.projectkoorchantage.be/beatles.php');
$html->addCustomMeta('og:title','Projectkoor CHANTage brengt Beatles-concert');
$html->addCustomMeta('og:image', 'http://www.projectkoorchantage.be/images/beatles/beatlesFB2.png');
$html->addCustomMeta('og:description',"In 1964 beleefden 'The Beatles' hun grote doorbraak. Ze werden de grondleggers van de hedendaagse popmuziek. En toch... zo heeft u hen nog nooit gehoord. Projectkoor CHANTage brengt u een mix van hun meest gekende nummers.");

$html->PrintHTML();