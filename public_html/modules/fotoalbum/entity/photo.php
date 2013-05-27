<?php
class photo
{
    ###privates
    private $id;
    private $description;
    private $album;
    
    ###constructor
    function __construct($album,$id=-1,$description="") {
        $this->description = $description;
        $this->id=$id;
        $this->album=$album;
    }
    
    ###public methods
    function getId()
    {
        return $this->id;
    }
    
    function setDescription($description)
    {
        $this->description=$description;
    }
    
    function getDescription()
    {
        return $this->description;
    }
    
    function getAlbumId()
    {
        return $this->album;
    }
    
    function setAlbumId($id)
    {
        $this->album=$id;
    }
}
?>
