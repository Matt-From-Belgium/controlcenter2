<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/nieuwsbrief.php';

class nieuwsbriefValidator
{
	function validateObject(nieuwsbrief $nieuwsbrief)
	{
		$errors[] = $this->validateMaand($nieuwsbrief->getMaand());
		$errors[] = $this->validateJaar($nieuwsbrief->getJaar());
		$errors[] = $this->validateTitel($nieuwsbrief->getTitel());
                
		$errors = array_filter($errors);
		
		return $errors;
	}
	
	function validateMaand($maand)
	{
		if(empty($maand))
		{
			$newerror['field'] = "maand";
			$newerror['message'] = "Je moet een maand opgeven";
			return $newerror;
		}
	}
	
	function validateJaar($jaar)
	{
		if(empty($jaar))
		{
			$newerror['field'] = "jaar";
			$newerror['message'] = "Je moet een jaar opgeven";
			return $newerror;
		}
	}
        
        function validateTitel($titel)
        {
            if(empty($titel))
            {
                        $newerror['field'] = "titel";
			$newerror['message'] = "Je moet een titel opgeven voor de nieuwsbrief";
			return $newerror;    
            }
        }
}
?>