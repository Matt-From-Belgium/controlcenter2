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
        $html->loadScript('/core/presentation/ajax/ajaxtransaction.js');
        $html->loadScript('/modules/fotoalbum/presentation/editalbum.js');
        $html->loadScript('/modules/fotoalbum/presentation/showalbum.js');
        $html->loadCSS('/modules/fotoalbum/presentation/css/fotoalbum.css');
        $html->loadCSS('/modules/fotoalbum/presentation/css/showphoto.css');
        $html->loadCSS('/modules/fotoalbum/presentation/css/editphoto.css');
        $html->setVariable('albumname', $album->getName());
        $html->setVariable('albumid', $album->getId());
        $html->setVariable('albumHTML', $album->getDescription());
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
