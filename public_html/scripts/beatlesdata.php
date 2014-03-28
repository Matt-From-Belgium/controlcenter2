<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/beatlesentity.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';

function addBeatlesSong($beatlesSong)
{
    if($beatlesSong instanceof beatlesSong)
    {
        $db = new DataConnection();
        $query = "INSERT INTO beatlespoll (title,previewurl,votes) VALUES ('@title','@previewurl',0)";
        $db->setQuery($query);
        
        $db->setAttribute('title', $beatlesSong->getTitle());
        $db->setAttribute('previewurl', $beatlesSong->getPreviewURL());
        
        $db->ExecuteQuery();
        
        ##Nu we het nummer toegevoegd hebben geven we een nieuw beatlesSong object terug met het id ingevuld
        $newid = $db->getLastId();
        
        $addedSong = new beatlesSong($beatlesSong->getTitle(), $beatlesSong->getPreviewURL(), $newid);
        return $addedSong;
    }
    else
    {
        throw new Exception('$beatlessong must be an instance of beatlesSong');
    }
}

/*DEBUG*/
/*$test = new beatlesSong('Blackbird', 'http://a1433.phobos.apple.com/us/r1000/018/Music6/v4/d9/33/c1/d933c103-2b8f-6f92-a1f9-42735d090538/mzaf_332285493930270021.plus.aac.p.m4a');

$result = addBeatlesSong($test);

print_r($result);*/
 
 
function data_searchBeatlesSong($string)
{
    $db = new DataConnection();
    
    $query = "SELECT id,title,previewurl from beatlespoll WHERE title LIKE '%@string%'";
    
    $db->setQuery($query);
    $db->setAttribute('string', $string);
    $db->ExecuteQuery();
    
    $results = array();
    
    if($db->GetNumRows()>0)
    {    
        #Er zijn resultaten gevonden in onze databank
        foreach($db->GetResultArray() as $value)
        {
            $newSong = new beatlesSong($value['title'], $value['previewurl'], $value['id']);
            $results[] = $newSong;
        }
    }
    
    return $results;
}

function data_voteForSong($id)
{
    $query = 'UPDATE beatlespoll SET beatlespoll.votes = beatlespoll.votes+1 WHERE beatlespoll.id=@id';
    
    $db= new DataConnection();
    $db->setQuery($query);
    $db->setAttribute('id',$id);
    
    $db->ExecuteQuery();
}

function data_getTop5()
{
    $query = 'SELECT id,title,previewurl from beatlespoll ORDER BY VOTES DESC LIMIT 5';
    
    $db = new DataConnection();
    $db->setQuery($query);
    $db->ExecuteQuery();
    
    $result = $db->GetResultArray();
    
    $objectArray= array();
    
    if($db->GetNumRows()>0)
    {
        foreach($result as $value)
        {
            $newSong = new beatlesSong($value['title'], $value['previewurl'], $value['id']);
            $objectArray[] = $newSong;
        }
    }
    
    return $objectArray;
}

/*DEBUG*/

/*$results = data_searchBeatlesSong('let');
print_r($results);*/

/*data_voteForSong(1);*/

/*print_r(data_getTop5());*/