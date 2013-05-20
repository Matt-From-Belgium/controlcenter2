<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/ajaxresponse.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/data/albumdata.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/entity/fotoalbum.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';

function addAlbum()
{
    checkPermission('fotoalbum', 'manage albums');
    
    if(empty($_POST['albumnaam']))
    {
        $response = new ajaxResponse('error');
        $response->addErrorMessage('albumnaam', 'U bent verplicht een albumnaam op te geven');
        
        echo $response->getXML();
    }
    else
    {
        if(data_albumExists($_POST['albumnaam']))
        {
            ###Er bestaat al een album met deze naam
            $response = new ajaxResponse('error');
            $response->addErrorMessage('albumnaam', 'Er bestaat reeds een album met deze naam');
            
            echo $response->getXML();
            
        }
        else
        {
            ###Alles ok, album mag toegevoegd worden
            $newalbum = new fotoalbum($_POST['albumnaam']);
            
            $id=data_albumToevoegen($newalbum);
            
            $response = new ajaxResponse('ok');
            echo $response->getXML();
        }
    }
}

function getAlbums()
{

        $response = new ajaxResponse('ok');
        $response->addField("id");
        $response->addField("name");

        if(is_array($albumarray = data_getAlbums()))
        {        
            foreach($albumarray as  $fotoalbum)
            {
                   $item = array();
                   $item['id'] = $fotoalbum->getID();
                   $item['name']= $fotoalbum->getName();

                   $response->addData($item);
            }    
        }
        
        return $response->getXML();
    
      
}

function getAlbum($id)
{
    if(is_int($id))
    {
        ###Functie kan enkel zoeken naar een id = integer
        return data_getAlbum($id);
    }
    else
    {
        throw new Exception('getAlbum only accepts an integer as argument');
    }
}
?>
