<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/logic/albumlogic.php';

function GetAlbumPhotosAjax()
{   
    /*###DEBUG
    $_POST['albumid']=1;*/
    
    $_POST['albumid']= intval($_POST['albumid']);
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
