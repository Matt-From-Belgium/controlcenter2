<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/logic/albumlogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/usermanagement/userfunctions.php';

checkPermission('fotoalbum', 'manage albums');

if(isset($_GET['id']))
{
    ###Eerst halen we de gegevens van het album op
    $album = getAlbum(intval($_GET['id']));
    
    if($album instanceof fotoalbum)
    {
      ###We hebben ons album gevonden   
        $html = new htmlpage('backend');
        $html->LoadAddin('/modules/fotoalbum/addins/editalbum.tpa');
        $html->setVariable('albumname', $album->getName());
        $html->PrintHTML();
    }   
    else
    {
        ###Geen album gevonden
        echo "invalid album";
    }
}
else
{
    throw new exception('No album id defined');
}
?>
