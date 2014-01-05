<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/logic/albumlogic.php';

function GetAlbumPhotosAjax()
{   
    /*###DEBUG
    $_POST['albumid']=1;*/
    
    $_POST['albumid']= intval($_POST['albumid']);
    $photoArray=getAlbumPhotos($_POST['albumid']);
    
    if(is_array(photoArray))
    {
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
    }
    else
    {
        $result = new ajaxResponse('error');
        $result->addErrorMessage('album', 'Dit album bevat nog geen fotos');
    }
    
    return $result->getXML();
}
?>
