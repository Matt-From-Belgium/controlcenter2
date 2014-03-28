<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class beatlesSong
{
    ###private vars
    private $songtitle;
    private $previewurl;
    private $id;
    private $itunesID;
    
    
    ###public vars
    
    
    ###CONSTRUCTOR
    public function __construct($songtitle,$previewurl,$id=-1) {
        $this->songtitle=$songtitle;
        $this->previewurl = $previewurl;
        $this->id = $id;
    }
    
    ###Public methods
    public function getID()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->songtitle;
    }
    
    public function getPreviewURL()
    {
        return $this->previewurl;
    }
    
    public function getITunesID()
    {
        return $this->itunesID;
    }
    
    public function setITunesID($id)
    {
        $this->itunesID=$id;
    }
    ###Private methods
}