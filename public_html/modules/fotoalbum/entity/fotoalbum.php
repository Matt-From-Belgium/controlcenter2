<?php
class fotoalbum
{
    ###privates
    private $id;
    private $name;
    
    ###constructor
    function __construct($name,$id=-1) {
        $this->setName($name);
        $this->id=$id;
    }
    
    ###public methods
    function getId()
    {
        return $this->id;
    }
    
    
    function getName()
    {
        return $this->name;
    }
    
    function setName($name)
    {
        $this->name= strtolower($name);
    }
}
?>
