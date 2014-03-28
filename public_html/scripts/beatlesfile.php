<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/beatlesentity.php';

function file_searchITunes($string)
{
    //Met deze functie gaan we op zoek naar beatlesnummers in de iTunes catalogus
    
    //We plaatsen plusjes ipv spaties
    $string= str_replace(' ', '+', $string);
 
    //We halen de gegevens op, we zoeken in iTunes Store naar songs met the beatles + zoekwoord
    $iTunesURL = "https://itunes.apple.com/search?term=$string&entity=song&country=us&media=music&attribute=songTerm";
    
    /*echo $iTunesURL;*/
    
    $content=file_get_contents($iTunesURL);
    
    //Alle items worden in een array gegoten. De resultaten zitten onder
    //$contentArray['results']
    $contentArray = json_decode($content,true);
    $contentArrayResults = $contentArray['results'];
    
    $filteredData = array();
    $tracknameArray = array();
    
    foreach($contentArrayResults as $value)
    {
        ###We kunnen niet rechtstreeks op artistid zoeken maar hier filteren we
        ###om de kwaliteit van het resultaat te verbeteren en geen tributes te krijgen
        if($value['artistId']==136975)
        {
            ###We willen iedere titel maar één keer in onze eindArray
            if(!in_array(strtolower($value['trackName']),$tracknameArray))
            {
                $discoveredsong = new beatlesSong($value['trackName'], $value['previewUrl']);
                $discoveredsong->setITunesID($value['trackId']);
                
                $filteredData[]=$discoveredsong;
            /*
                $newData = array();
                $newData['trackId'] = $value['trackId'];
                $newData['trackName']=$value['trackName'];
                $newData['previewUrl'] = $value['previewUrl'];

                $filteredData[] = $newData;
             * 
             */
                $tracknameArray[] = strtolower($value['trackName']);
            }
        }
    }
    
    
    
    return $filteredData;
}

function file_generateBeatlesSQL()
{
    //Met deze functie gaan we op zoek naar beatlesnummers in de iTunes catalogus
    
    //We plaatsen plusjes ipv spaties
    $string= str_replace(' ', '+', $string);
 
    //We halen de gegevens op, we zoeken in iTunes Store naar songs met the beatles + zoekwoord
    $iTunesURL = "https://itunes.apple.com/search?term=the+beatles+$string&entity=song&country=us&media=music";
    
    /*echo $iTunesURL;*/
    
    $content=file_get_contents($iTunesURL);
    
    //Alle items worden in een array gegoten. De resultaten zitten onder
    //$contentArray['results']
    $contentArray = json_decode($content,true);
    $contentArrayResults = $contentArray['results'];
    
    $filteredData = array();
    $tracknameArray = array();
    
    foreach($contentArrayResults as $value)
    {
        ###We kunnen niet rechtstreeks op artistid zoeken maar hier filteren we
        ###om de kwaliteit van het resultaat te verbeteren en geen tributes te krijgen
        if($value['artistId']==136975)
        {
            ###We willen iedere titel maar één keer in onze eindArray
            if(!in_array($value['trackName'],$tracknameArray))
            {
                $discoveredsong = new beatlesSong($value['trackName'], $value['previewUrl']);
                $discoveredsong->setITunesID($value['trackId']);
                
                $filteredData[]=$discoveredsong;
            /*
                $newData = array();
                $newData['trackId'] = $value['trackId'];
                $newData['trackName']=$value['trackName'];
                $newData['previewUrl'] = $value['previewUrl'];

                $filteredData[] = $newData;
             * 
             */
                $tracknameArray[] = $value['trackName'];
            }
        }
    }
    
    
    
    return $filteredData;
}

function file_lookupSong($songid)
{
    $iTunesURL = "https://itunes.apple.com/lookup?id=$songid&entity=musicTrack&limit=10";
    
    $content=file_get_contents($iTunesURL);
    
    //Alle items worden in een array gegoten. De resultaten zitten onder
    //$contentArray['results']
    $contentArray = json_decode($content,true);
    $contentArrayResults = $contentArray['results'];
    
    //We hebben op id gezocht dus er is maar 1 resultaat
    $result = $contentArrayResults[0];
    
    if($result)
    {
        $foundSong = new beatlesSong($result['trackName'], $result['previewUrl']);
        $foundSong->setITunesID($songid);
        
        return $foundSong;
    }
    else
    {
        throw new Exception('iTunes id does not exist');
    }
}
/*DEBUG*/

/*$content = file_searchITunes('let it be');
print_r($content);*/

/*$result = file_lookupSong(458032415);
print_r($result);*/
