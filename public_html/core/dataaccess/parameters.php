<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/parameter.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/dataconnection/componentselector.php";

#Ophalen van parameters
function dataaccess_GetParameter($searchstring)
{
	#opbouwen van de query
	$query =  "SELECT parameters.id,parameters.name,parameters.value,parameters.overridable,parameters.environmental FROM parameters ";
	$query .= "WHERE parameters.name='@searchstring'";

	#Uitvoeren van de query
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute("searchstring","$searchstring");
	$db->ExecuteQuery($query);
		
	if($db->getNumRows() > 0)
	{
		$results = $db->getResultArray();
		foreach($results as $key=>$value)
		{
			list($id,$name,$value,$overridable,$environmental)=$results[$key];
			
                        if($environmental==1)
                        {
                            $environmental=true;
                        }
                        else
                        {
                            $environmental=false;
                        }                        
                        
			#de gegevens worden in een object gegoten
			$gevondenparameter = new parameter($id,$environmental);
			$gevondenparameter->setName($name);
			$gevondenparameter->setValue($value);
			$gevondenparameter->setOverridable($overridable);
                        

		}
	}
	else
	{
		throw new Exception("You tried to get the value for parameter '$searchstring', but that parameter is not defined");
	}
	
	#er wordt een parameter-object teruggegeven.
	return $gevondenparameter;
}

/*DEBUG
$parameter = dataaccess_GetParameter('CORE_SERVER_MAILADRESS');

if($parameter->getEnvironmental())
{
    echo 'ok';
}*/


#Toevoegen van parameters
function dataaccess_AddParameter($parameterobject)
{
	#Eerst wordt gecontroleerd of $parameterobject wel degelijk van de klasse parameteris
	if(is_a($parameterobject,"parameter"))
	{
		#Deze functie voegt het parameterobject toe aan de database
		$query = "INSERT INTO paramters (name,value,overridable) VALUES('@name','@value','@overridable')";
		
		$db = new dataconnection();
		
		$db->setQuery($query);
		$db->setAttribute("name",$parameterobject->getName());
		$db->setAttribute("value",$parameterobject->getValue());
		$db->setAttribute("overridable",$parameterobject->getOverridable());
		
		$db->ExecuteQuery($query); 
		$toegevoegdid = $db->getLastId();

	
	#het id-nummer van het laatst toegevoegde record wordt teruggegeven.
	return $toegevoegdid;
	}
	else
	{
		#$parameterobject is geen geldig parameter-object => exception
		throw new Exception("AddParameter() only accepts objects of the class parameter");
	}

}

#Wijzigen van parameters
function dataaccess_EditParameter($parameterobject)
{
#Eerst moet gecheckt worden of $parameterobject wel degelijk van de klasse parameter is.
	if(is_a($parameterobject,"parameter"))
	{
		#deze functie past parameterobjects aan. Het id wordt afgeleid uit het object zelf aangezien dit niet 
		#gewijzigd kan worden
		
		$query = "UPDATE parameters SET name='@name',value='@value',overridable='@overridable' WHERE parameters.id= @parameterid";

		$db = new dataconnection();
		$db->setQuery($query);
		
		$db->setAttribute("name",$parameterobject->getname());
		$db->setAttribute("value",$parameterobject->getValue());
		$db->setAttribute("overridable",$parameterobject->getOverridable());
		$db->setAttribute("parameterid",$parameterobject->getId());
		
		$db->ExecuteQuery();
	}
	else
	{
		throw new Exception("EditParameter() only accepts objects of the class parameter");
	}
}

?>