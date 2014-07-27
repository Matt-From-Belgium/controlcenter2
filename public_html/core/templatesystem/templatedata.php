<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
function DataAccess_AliasGetlinkeddir($alias,$platform)
{
        ###Templatesystem R3: platform is gedetecteerd in logic layer
        ###Er zijn 3 mogelijkheden = pc,tablet,phone
        ###In de databank zijn nu ook 3 directories voor ieder platform
        ###Als mobile en tablet null zijn wordt altijd het pc template gebruikt
        ###Als tablet niet ingevuld is wordt voor tablets het mobile template gebruikt
    
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataconnection/componentselector.php";
	$db = new dataconnection();
	
	$query = "SELECT templatealiases.pc_directory,phone_directory,tablet_directory FROM templatealiases WHERE templatealiases.name='@alias'";
	$db->setQuery($query);
	
	$db->setAttribute("alias",$alias);
	
	$db->ExecuteQuery();
	if($db->getNumRows() >0)
	{
            /*return $db->getScalar();*/
            ###Templatesystem R3: afhankelijk van platform
            $result = $db->GetResultArray();
            list($pc,$phone,$tablet) = $result[0];
            
            if(empty($phone))
            {
                $phone = $pc;
            }
            
            if(empty($tablet))
            {
                $tablet = $phone;
            }
            
            ###Hier zouden alle drie de variabelen moeten ingevuld zijn, zelfs al zijn ze niet alle drie gedefinieerd
            #in de databank
            
            return $$platform;
	}
	else
	{
	return false;
	}

}
?>