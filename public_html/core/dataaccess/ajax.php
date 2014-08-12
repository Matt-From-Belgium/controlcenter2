<?php

require_once $_SERVER['DOCUMENT_ROOT']."/core/dataconnection/componentselector.php";

function dataaccess_checkWhiteList($file,$function)
{
    ###Om hacking via de javascript ajax module te voorkomen wordt een whitelist ingevoerd
    #enkel bestands- en functiecombinaties op die lijst kunnen gebruikt worden bij het javascript
    
    $db = new DataConnection();
    $query='SELECT id from ajaxwhitelist WHERE file="@filename" and function="@function"';
    
    $db->setQuery($query);
    $db->setAttribute('filename',$file);
    $db->setAttribute('function', $function);
    
    $db->ExecuteQuery();
    
    if($db->GetNumRows()>0)
    {
        ###De combinatie staat op de lijst
        return true;
    }
    else
    {
        ###De combinatie staat niet op de lijst
        return false;
    }
    
}