<?php

function ajaxGetAlbumPhotos()
{
    ###DEBUG
    $_POST['albumid']=1;
    
    $photoArray=getAlbumPhotos($_POST['albumid']);
    
    $result = new ajaxResponse('ok');
    $result->addField('id');
    $result->addField('album');
    $result->addField('filename');
    $result->addField('description');
    
    
    foreach($photoArray as $value)
    {
        $newarray = array();
        $newarray['id'] = $value->getId();
        $newarray['album']=$value->getAlbumId();
        $newarray['filename'] = $value->getFilename();
        $newarray['description']= $value->getDescription();
        
        
        $result->addData($newarray);
    }
    
    return $result->getXML();
}
?>
