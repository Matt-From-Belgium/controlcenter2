<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/beatleslogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/ajaxresponse.php';

function ajaxSearchBeatlesSong()
{
    #$_POST['let'];

    $result = searchBeatlesSong($_POST['searchtext']);

    $response = new ajaxResponse('ok');
    $response->addField('id');
    $response->addField('title');
    $response->addField('previewurl');
    $response->addField('itunesID');

    //We limiteren het maximaal aantal resultaten
    $i=0;
    $limit = 5;
    
    foreach($result as $value)
    {
        $i++;
        
        if($i<=$limit)
        {
            $newitem = array();
            $newitem['id'] = $value->getID();
            $newitem['title'] = $value->getTitle();
            $newitem['previewurl'] = $value->getPreviewURL();
            $newitem['itunesID'] = $value->getITunesID();

            $response->addData($newitem);
        }
    }

    return $response->getXML();
}

function ajaxVoteForSong()
{
    if($_POST['itunesid'])
    {
        //De gebruiker koos een nummer uit de iTunes Store
        //We moeten dit nummer dus aanmaken in onze databank
        $newSong = addSongFromItunes($_POST['itunesid']);
        
        $votedId=$newSong->getID();
    }
    else
    {
        //Gebruiker koos voor een song die al gekend is
        $votedId=$_POST['songid'];
    }
    
    beatlesVote($votedId);
    
    $response = new ajaxResponse('ok');
    $response->addField('id');
    
    $responsedata['id']=$votedId;
    $response->addData($responsedata);
    
    return $response->getXML();
}

function ajaxTop5()
{
    $top5=getTop5();
    
    $response = new ajaxResponse('ok');
    $response->addField('id');
    $response->addField('title');
    $response->addField('previewurl');
    $response->addField('itunesID');
    
    foreach($top5 as $value)
    {
        $newitem = array();
        $newitem['id'] = $value->getID();
        $newitem['title'] = $value->getTitle();
        $newitem['previewurl'] = $value->getPreviewURL();
        $newitem['itunesID'] = $value->getITunesID();

        $response->addData($newitem);
    }
    
    return $response->getXML();
}

/*DEBUG*/
/*print_r(ajaxTop5());*/
?>
