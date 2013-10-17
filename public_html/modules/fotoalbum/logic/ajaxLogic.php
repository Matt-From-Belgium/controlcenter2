<?php

function ajaxGetAlbumPhotos()
{
    ###DEBUG
    $_POST['albumid']=1;
    
    $photoArray=getAlbumPhotos($_POST['albumid']);
    
    $result = new ajaxResponse('ok');
    $result->addField('id');
    $result->addField('album');
    $result->addField('path');
    
    foreach($photoArray as $value)
    {
        $newarray = array();
        $newarray['id'] = $value->getId();
        $newarray['album']=$value->getAlbumId();
        $newarray['path'] = $value->getServerPath();
        
        $result->addData($newarray);
    }
    
    return $result->getXML();
}
?>
