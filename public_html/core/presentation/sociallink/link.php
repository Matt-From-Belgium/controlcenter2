<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

$html = new htmlpage('backend');
$html->LoadAddin('/core/presentation/sociallink/addins/link.tpa');
$html->loadCSS('/core/presentation/sociallink/css/sociallink.css');
$html->loadScript('/core/presentation/sociallink/javascript/FBlink.js');
$html->enableAjax();
$html->setFacebookIntegration(true);
$html->PrintHTML();