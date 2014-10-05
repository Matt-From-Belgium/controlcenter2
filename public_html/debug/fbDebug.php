<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$html = new htmlpage('frontend');
$html->LoadAddin('/debug/fbDebug.tpa');
$html->loadScript('/core/presentation/usermanagement/accounts/fbRegister.js');
$html->PrintHTML();