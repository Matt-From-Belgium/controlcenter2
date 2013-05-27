<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/ajaxresponse.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/data/albumdata.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/entity/fotoalbum.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/entity/photo.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/filevalidator.php';

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

function addPhoto()
{
    checkPermission('fotoalbum', 'manage albums');
    
    ###Eerst moeten we controleren of het bestand aan de vereisten voldoet
    $filevalidator = new fileValidator();
    $filevalidator->setExtension('jpg');
    $filevalidator->setMaxSize(5);
    $errors=$filevalidator->validateFile($_FILES['photopath']);
    
    ###Nu kijken we na of er errors zijn
    if(is_array($errors))
    {
        $AjaxResponse = new ajaxResponse('error');
        
        foreach($errors as $key=>$value)
        {
            $AjaxResponse->addErrorMessage('photopath', $value['message']);
        }
        
        return $AjaxResponse->getXML();
    }
    else
    {
        ###Geen errors, dus we gaan verder
        $photo = new photo($_POST['album']);
        
        #Eerst maken we een lijn aan in de databasetabel photos
        $photo2=data_addPhoto($photo);
        
        #De databasefunctie geeft een gewijzigd object terug met de waarde id ingevuld
        #Deze wordt gebruikt als bestandsnaam
        move_uploaded_file($_FILES['photopath']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/photos/'.$photo2->getId().'.jpg');
        
        $result = new ajaxResponse('ok');


        return $result->getXML();
    }
}
?>
