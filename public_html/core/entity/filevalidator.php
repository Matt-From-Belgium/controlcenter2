<?php

class fileValidator
{   
    ###privates
    private $extension = null;
    private $maxSize = null;
    
    
    public function setExtension($extension)
    {
        $this->extension=$extension;
    }
    
    public function setMaxSize($sizeinMB)
    {
        $this->maxSize = $sizeinMB*1024*1024;
    }
    
    public function validateFile($filearray)
    {
        if($filearray['error']==0)
        {
            ###Geen fouten gebeurd bij het opladen
            $parts = explode('.',$filearray['name']);
            $extension = end($parts);
            
            if($extension <> $this->extension)
            {
                $newerror['message']="Enkel ".$this->extension."-bestanden worden aanvaard";
                $errors[]=$newerror;
            }
            
            #echo "bestandsgrootte is".$filearray['size']." maximale grootte is ".$this->maxSize;
            
            if($filearray['size']>$this->maxSize)
            {
                $sizeinMB = ($this->maxSize/1024)/1024;
                $newerror['message']="Bestanden mogen niet groter zijn dan ". $sizeinMB." megabytes";
                $errors[] = $newerror;
            }
          
            
        }
        else
        {
            ###Wel fouten bij het opladen
            $newerror['message']="Er is iets foutgelopen bij het uploaden";
            
            $errors[] = $newerror;
        }
        
        return $errors;
    }

}

?>
