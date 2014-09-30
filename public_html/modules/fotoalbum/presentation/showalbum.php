<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/presentation/general/commonfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/logic/albumlogic.php';


if(isset($_GET['id']))
{
    ###Als er een id wordt ingevoerd dan tonen we het album in kwestie
    $id = intval($_GET['id']);
    $album = getAlbum($id);
    
    ###Als het id gekend is hebben we nu een albumobject
    if($album)
    {
        ###Album gevonden, weergave
        $html = new htmlpage('frontend');
        $html->loadScript('/modules/fotoalbum/presentation/showalbum.js');
        $html->loadScript('/core/presentation/ajax/ajaxtransaction.js');
        $html->LoadAddin('/modules/fotoalbum/addins/showalbum.tpa');
    
        $html->setVariable('albumid', $id);
        $html->setVariable('albumtitel', $album->getName());
        $html->setVariable('description', $album->getDescription());
        $html->loadCSS('/modules/fotoalbum/presentation/css/showphoto.css','/modules/fotoalbum/presentation/css/showphoto-mobile.css');
    
        $html->PrintHTML();
    }
    else
    {
        ###Album niet gevonden => foutmelding
        showMessage('Geen geldig album','Geen geldig albumID opgegeven');
    }
    
}
 ###Als er geen id ingevoerd wordt dan tonen we een lijst van albums
 else
 {
     $html = new htmlpage('frontend');
     $html->LoadAddin('/modules/fotoalbum/addins/albumlist.tpa');
     
     ###We halen de albums op en converteren de gegevens naar een array die met de loop gebruikt kan worden
     $albums = getAlbumObjects();
     $albumarray = array();
     
     foreach($albums as $value)
     {
         $newAlbum['titel']=$value->getName();
         $newAlbum['link']='/modules/fotoalbum/presentation/showalbum.php?id='.$value->getId();
         
         $albumarray[] = $newAlbum;
     }
     
     $html->setVariable('albums', $albumarray);
     
     $html->PrintHTML();
     
     
     
 }