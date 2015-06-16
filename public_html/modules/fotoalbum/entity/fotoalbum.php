<?php
class fotoalbum
{
    ###privates
    private $id;
    private $name;
    private $desriptionHTML;
    private $coverphotoid;
    
    ###constructor
    function __construct($name,$id=-1,$cover=null) {
        $this->setName($name);
        $this->id=$id;
        $this->coverphotoid=$cover;
    }
    
    ###public methods
    function getId()
    {
        return $this->id;
    }
    
    
    function getName()
    {
        return ucfirst($this->name);
    }
    
    function setName($name)
    {
        $this->name= strtolower($name);
    }
    
    function getDescription()
    {
        return $this->desriptionHTML;
    }
    
    function setDescription($html)
    {
        $this->desriptionHTML=$html;
    }
    
    function setCoverPhoto(photo $coverphoto)
    {
        $this->coverphotoid=$coverphoto->getId();
    }
    
    function getCoverPhotoId()
    {
        return $this->coverphotoid;
    }
    
    
}
?>
