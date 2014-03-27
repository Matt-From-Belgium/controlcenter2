<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/beatlesdata.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/beatlesentity.php';


function searchBeatlesSong($searchstring)
{
    return data_searchBeatlesSong($searchstring);
}

/*DEBUG*/
/*$result = searchBeatlesSong('let');
print_r($result);*/