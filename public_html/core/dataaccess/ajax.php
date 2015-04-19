<?php

require_once $_SERVER['DOCUMENT_ROOT']."/core/dataconnection/componentselector.php";

function dataaccess_checkWhiteList($file,$function)
{
    ###Om hacking via de javascript ajax module te voorkomen wordt een whitelist ingevoerd
    #enkel bestands- en functiecombinaties op die lijst kunnen gebruikt worden bij het javascript
    
    $db = new DataConnection();
    $query='SELECT id from ajaxwhitelist WHERE file="@(s)filename" and function="@function"';
    
    $db->setQuery($query);
    $db->setAttribute('filename',strtolower($file));
    $db->setAttribute('function', strtolower($function));
    
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

/*//DEBUG
$result = dataaccess_checkWhiteList('/core/templatesystem/templatelogic.php', 'setCookiesOk');
echo $result;
*/
 