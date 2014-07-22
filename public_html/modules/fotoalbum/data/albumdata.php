<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/entity/fotoalbum.php';

function data_albumExists($albumnaam)
{
    $albumnaam=  strtolower($albumnaam);
    ###Deze functie controleert of er nog geen album bestaat met die naam
    ###Als er al een album bestaat krijg deze de waarde true, anders false.
    $query = "SELECT id FROM albums WHERE albums.name='@albumnaam'";
    
    $db = new DataConnection();
    $db->setQuery($query);
    $db->setAttribute('albumnaam', $albumnaam);
    
    $db->ExecuteQuery();
    
    if($db->GetNumRows()>0)
    {
        return true;
    }
    
    else
    {
        return false;
    }
}

function data_albumToevoegen(fotoalbum $album)
{
    $query = "INSERT INTO albums (name,description) VALUES ('@albumnaam','@description')";
    
    $db = new DataConnection();
    $db->setQuery($query);
    
    $db->setAttribute('albumnaam', $album->getName());
    $db->setAttribute('description', $album->getDescription());
    
    $db->ExecuteQuery();
    
    $result = $db->getLastId();
    
    return $result;
}

function data_getAlbums()
{
    $query = "SELECT albums.id,albums.name,albums.description from albums";
    
    $db = new DataConnection;
    $db->setQuery($query);
    
    $db->ExecuteQuery();
    
    if($db->GetNumRows()>0)
    {
        #return $db->GetResultArray();
        
        $result = $db->GetResultArray();
        foreach($result as $key=>$value)
        {
            $newFotoalbum = new fotoalbum($value['name'], $value['id']);
            $newFotoalbum->setDescription($value['description']);
            $albumarray[] = $newFotoalbum;
        }
        
        return $albumarray;
    }
    else
    {
        return false;
    }
}

function data_getAlbum($id)
{
    if(is_int($id))
    {
            $query = "SELECT albums.id,albums.name,albums.description from albums WHERE albums.id=@id";
    
            $db = new DataConnection;
            $db->setQuery($query);
            $db->setAttribute("id", $id);

            $db->ExecuteQuery();

            if($db->GetNumRows()>0)
            {
                #return $db->GetResultArray();

                $resultarray = $db->GetResultArray();
                $result = $resultarray[0];
                
                $fotoalbum = new fotoalbum($result['name'], $result['id']);
                $fotoalbum->setDescription($result['description']);

                return $fotoalbum;
            }
            else
            {
                return false;
            }
    }
    else
    {
        throw new Exception("data_getAlbum only accepts an integer as argument");
    }
}

function data_getAlbumByName($albumname)
{
    
    if(isset($albumname))
    {
        $query = "SELECT albums.id FROM albums WHERE albums.name='@name'";
        $db = new DataConnection();
        $db->setQuery($query);
        $db->setAttribute('name', $albumname);
        $db->ExecuteQuery();
        
        if($db->GetNumRows()==1)
        {
            ###Het albumid is gevonden, nu gaan we het album ophalen
            
            return data_getAlbum(intval($db->GetScalar()));
        }
        else
        {
            throw new Exception('album $albumname does not exist');
        }
    }
    else
    {
        throw new Exception('$albumname has no value');
    }
    
}

function data_addPhoto(photo $photo)
{
    ###We voegen een rij toe in de tabel photo en vervangen de id in het object.
    ###Bij het uploaden zelf wordt er geen beschrijving meegegeven
    
    $query = "INSERT INTO photos (album,extension,description) VALUES (@album,'@extension','@description')";
    
    $db = new DataConnection();
    $db->setQuery($query);
    $db->setAttribute('album', $photo->getAlbumId());
    $db->setAttribute('extension', $photo->getExtension());
    $db->setAttribute('description', $photo->getDescription());
    $db->ExecuteQuery();
    
    ###We vervangen het id in het object door een nieuw te creëren
    $newphoto = new photo($photo->getAlbumId(), $photo->getExtension(), $db->getLastId());
    
    
    return $newphoto;
}

function data_editPhoto(photo $photo)
{
    $query = "UPDATE photos SET album=@albumid,extension='@extension',description='@description' WHERE photos.id=@photoid";
    
    $db = new DataConnection();
    $db->setQuery($query);
    $db->setAttribute('albumid', $photo->getAlbumId());
    $db->setAttribute('extension', $photo->getExtension());
    $db->setAttribute('description', $photo->getDescription());
    $db->setAttribute('photoid', $photo->getId());
    
    $db->ExecuteQuery();
}

function data_getPhotoById($id)
{
    ###We zoeken de foto gegevens op en creëren een object
    $query = 'SELECT id,album,extension,description FROM photos WHERE photos.id=@id';
    
    $db = new DataConnection();
    $db->setQuery($query);
    $db->setAttribute('id',$id);
    
    $db->ExecuteQuery();
    
    if($db->GetNumRows()>0)
    {
        $result = $db->GetResultArray();
    
        $photo = new photo($result[0]['album'],$result[0]['extension'],$result[0]['id'],$result[0]['description']);
        return $photo;
    }
    else
    {
        return false;
    }
}

function data_getAlbumPhotos($albumid)
{
    require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/entity/photo.php';
    
    if(is_int($albumid))
    {
        $query = "SELECT photos.id,photos.extension,photos.album,photos.description FROM photos WHERE photos.album='@id'";
        $db = new DataConnection();
        $db->setQuery($query);
        $db->setAttribute("id", $albumid);
        $db->ExecuteQuery();
        
        $result = $db->GetResultArray();
        $photos = Array();
        
        if($db->GetNumRows()>0)
        {
            
         foreach($result as $value)
            {
                $newphoto = new photo($value['album'],$value['extension'], $value['id'], $value['description']);
                $photos[] = $newphoto;     
                
               
                
            }  
             return $photos;
        }
        else
        {
            return false;
        }
        
        
        ###Aan het einde van de rit geven we het array met objecten terug
        
        ###DEBUG
        /*print_r($photos);*/
        
        
    }
    else
    {
        throw new exception('$albumid must be an integer');
    }
}

function data_deletePhoto($photo)
{
    if($photo instanceof photo)
    {
        $query = 'DELETE FROM photos where id=@id';
        $db = new DataConnection();
        $db->setQuery($query);
        $db->setAttribute('id', $photo->getId());
        $db->ExecuteQuery();
    }
    else
    {
        throw new Exception('$hoto moet instantie zijn van photo');
    }
}

?>
