<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';

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

function data_albumToevoegen($albumnaam)
{
    $query = "INSERT INTO albums (name) VALUES ('@albumnaam')";
    
    $db = new DataConnection();
    $db->setQuery($query);
    
    $db->setAttribute('albumnaam', $albumnaam);
    
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
        return $db->GetResultArray();
    }
    else
    {
        return false;
    }
}
?>
