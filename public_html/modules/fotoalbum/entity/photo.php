<?php
class photo
{
    ###privates
    private $id;
    private $description;
    private $album;
    private $extension;
    
    ###constructor
    function __construct($album,$extension,$id=-1,$description="") {
        $this->description = $description;
        $this->id=$id;
        $this->album=$album;
        $this->extension = $extension;
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
    
    function getServerPath()
    {
        ###De bestandsnaam wordt toegekend op basis van het id
        ###Dus als het id groter is dan 0 gaat het om een opgeslagen foto
        ###En kunnen we het pad berekenen
        if($this->getId()>0)
        {
            $prefix = $_SERVER['DOCUMENT_ROOT'].'/modules/fotoalbum/photos/';
            $path = $prefix.$this->getId().'.'.$this->getExtension();
            return $path;
        }
        else
        {
            throw new Exception("You cannot get the path of a photo that has not been saved");
        }
    }
    
    function getFilename()
    {
        $file = $this->getId().'.'.$this->getExtension();
        return $file;
    }
    
    function setExtension($ext)
    {
        $this->extension = $ext;
    }
    
    function getExtension()
    {
        return $this->extension;
    }
}
?>
