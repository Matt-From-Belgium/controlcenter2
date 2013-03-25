<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/ajaxresponse.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/data/albumdata.php';

function addAlbum()
{
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
            $id=data_albumToevoegen($_POST['albumnaam']);
            
            $response = new ajaxResponse('ok');
            echo $response->getXML();
        }
    }
}

function getAlbums()
{
    if(is_array($albumarray = data_getAlbums()))
    {
        $response = new ajaxResponse('ok');
        $response->addField("id");
        $response->addField("name");
        
        foreach($albumarray as $value)
        {
               $item = array();
               $item['id'] = $value['id'];
               $item['name']= $value['name'];
               
               $response->addData($item);
        }    
        
        return $response->getXML();
    }
      
}
?>
