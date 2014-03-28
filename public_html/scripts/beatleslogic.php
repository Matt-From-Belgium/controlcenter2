<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/beatlesdata.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/beatlesentity.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/beatlesfile.php';


function searchBeatlesSong($searchstring)
{
    $foundInDB= data_searchBeatlesSong($searchstring);
    
    if(count($foundInDB)>0)
    {
        //We hebben resultaten in onze eigen databank => deze teruggeven
        return $foundInDB;
    }
    else
    {
        //We gaan zoeken in de iTunes catalogus
        $foundOniTunes=file_searchITunes($searchstring);
        return $foundOniTunes;
    }
}

function beatlesVote($id)
{
    data_voteForSong($id);
}

function addSongFromItunes($itunesid)
{
    $newSong = file_lookupSong($itunesid);
    $addedSong = addBeatlesSong($newSong);
    
    return $addedSong;
}

function getTop5()
{
    return data_getTop5();
}

/*DEBUG*/

/*$result = searchBeatlesSong("can't");
print_r($result);*/