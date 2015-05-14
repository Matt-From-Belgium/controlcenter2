<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/logic/albumlogic.php';

function GetAlbumPhotosAjax()
{   
    /*###DEBUG
    $_POST['albumid']=1;*/
    
    if(is_numeric($_POST['albumid']))
    {
        $albumid= intval($_POST['albumid']);
    }
    else 
    {
        ###Het id is geen integer maar mogelijk wel een albumnaam
        ###We proberen het id van het album op te zoeken
        
        $album = data_getAlbumByName(strtolower($_POST[albumid]));
        $albumid = intval($album->getId());
        
    }
    
    
    $photoArray=getAlbumPhotos($albumid);
    
    if(is_array($photoArray))
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

function albumBeschrijvingWijzigenAjax()
{
    checkpermission('fotoalbum','manage albums');
    
    if($_POST['id'] && $_POST['nieuwebeschrijving'])
    {
        ###De nodige waarden zijn er
        #Eerst halen we het oorspronkelijk album op
        $albumid= intval($_POST['id']);
        
        $album = data_getAlbum($albumid);
        
        if($album)
        {
            ###Album gevonden
            $album->setDescription($_POST['nieuwebeschrijving']);
            
            ###We schrijven het gewijzigde album naar de database
            data_albumWijzigen($album);
            $response = new ajaxResponse('ok');
            return $response->getXML();
        }
        else
        {
            $response = new ajaxResponse('error');
            $response->addErrorMessage('id', 'id bestaat niet');
            return $response->getXML();
        }
    }
    else
    {
        $response = new ajaxResponse('error');
        $response->addErrorMessage('nieuwebeschrijving', 'Er moet een id en beschrijving gegeven worden');
        return $response->getXML();
    }
}

function setCoverPhoto()
{
    checkpermission('fotoalbum','manage albums');
    
    if($_POST['albumid'] && $_POST['photoid'])
    {
            $albumid = intval($_POST['albumid']);
            $photoid = intval($_POST['photoid']);
            
            $album = data_getAlbum($albumid);
            $photo = data_getPhotoById($photoid);
            
            $album->setCoverPhoto($photo);
            data_albumWijzigen($album);
            
            $response = new ajaxResponse('ok');
            return $response->getXML();
    }
    else
    {
        $response = new ajaxResponse('error');
        $response->addErrorMessage('nieuwebeschrijving', 'Er moet een album en foto gegeven worden');
        return $response->getXML();
    }
}
?>
