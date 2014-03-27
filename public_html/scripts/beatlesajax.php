<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/beatleslogic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/ajaxresponse.php';

function ajaxSearchBeatlesSong()
{
    #$_POST['let'];

    $result = searchBeatlesSong($_POST['searchtext']);

    $response = new ajaxResponse('ok');
    $response->addField('id');
    $response->addField('title');
    $response->addField('previewurl');

    foreach($result as $value)
    {
        $newitem = array();
        $newitem['id'] = $value->getID();
        $newitem['title'] = $value->getTitle();
        $newitem['previewurl'] = $value->getPreviewURL();

        $response->addData($newitem);
    }

    return $response->getXML();
}
?>
