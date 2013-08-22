<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/parameters.php';

class fileValidator
{   
    ###privates
    private $extension = Array();
    private $maxSize = null;
    
    
    public function setExtension($extension)
    {
        ###REVISIE 1: verschillende extensies toegelaten
        if(is_array($extension))
        {
            $this->extension=$extension;
        }
        else
        {
        $this->extension[]=$extension;
        }
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
            
            ###REVISIE 1: verschillende extensies
            if(array_search($extension, $this->extension)===FALSE)
            {
                ###REVISIE 1: verschillende extensies
                $extensionstring = $this->extension[0];
               
                for($i=1;$i<count($this->extension);$i++)
                {
                    $extensionstring = $extensionstring.', '.$this->extension[$i];
                }
                
                $newerror['message']="Enkel bestanden met extensies $extensionstring worden aanvaard";
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
            ###REVISIE1: Als CORE_DEBUG_MODE gelijk is aan 1 tonen we de fout
            ###Anders tonen we gewoon de melding 'er is iets fout gelopen bij het uploaden'
            if(getDebugMode())
            {
               $newerror['message']="DEBUG: errorcode ".$filearray['error'];
               $errors[] = $newerror;
            }
            else
            {
                $newerror['message']="Er is iets fout gelopen bij het uploaden";
                $errors[] = $newerror;
            }
        }
        
        return $errors;
    }

}

?>
