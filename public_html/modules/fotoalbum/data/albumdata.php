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
    $query = "INSERT INTO albums (name) VALUES ('@albumnaam')";
    
    $db = new DataConnection();
    $db->setQuery($query);
    
    $db->setAttribute('albumnaam', $album->getName());
    
    $db->ExecuteQuery();
    
    $result = $db->getLastId();
    
    return $result;
}

function data_getAlbums()
{
    $query = "SELECT albums.id,albums.name from albums";
    
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
            $query = "SELECT albums.id,albums.name from albums WHERE albums.id=@id";
    
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
?>
