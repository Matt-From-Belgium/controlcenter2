<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
class DataConnection
{
#De connectieparameters moeten beveiligd binnen de instantie blijven
Private $parameters;
Private $connectionid;
Private $result;
Private $activequery;

	public Function __construct()
	{
		#Ophalen van de verbindingsgegevens
		require_once $_SERVER['DOCUMENT_ROOT']."/core/fileaccess/database.php";
		$this->parameters = GetDataBaseParameters();
		$this->Connect();
	}
	
	Private function Connect()
	{
		#Er wordt geprobeerd om verbinding te maken met de server. De functie mysql_connect
		#geeft het linkid terug als er succesvol verbinding werd gemaakt. Wanneer er iets misloopt wordt
		#false teruggegeven
		$host = $this->parameters['host'];
		$user = $this->parameters['user'];
		$password = $this->parameters['password'];
		$database = $this->parameters['database'];
		
		$link = @mysql_connect($host,$user,$password);
		
		if(!$link)
		{
			throw new Exception(mysql_error(),mysql_errno());
		}
		else
		{
		$this->connectionid=$link;
		
		#Selectie van de database
		$success = mysql_select_db($this->parameters['database'],$this->connectionid);
		
		if(!$success)
		{
		throw new Exception("could not connect to database $this->parameters[database]",mysql_errno());
		}
		}
	}
	
	Public function setQuery($query)
	{
		###Deze functie zorgt dat een query kan worden ingevoerd
		$this->activequery = $query;
	}
        
        #De query wordt al weergegeven bij foutboodschappen. Kan handig zijn voor debug
        #Public function getQuery()
        #{
        #    return $this->activequery;
        #}
	
	Public function setAttribute($attributename,$value)
	{
		###Deze functie zal de attributen in de Query (aangeduid met @) vervangen door de werkelijke waarde
		###De aangeleverde waarde wordt eerst ontdaan van mogelijk schadelijke items.
                ###DEZE LIJN IS GEDISABLED: zorgt voor meer problemen dan oplossingen
		#$value = mysql_real_escape_string($value,$this->connectionid);
            
                ###Aanhalingstekens zijn een probleem => deze moeten ge-escaped worden
                $value1=str_replace('"', '\"', $value);
                $value2=  str_replace("'", "\'", $value1);
                
                $value = $value2;
		###bugfix: om de like acties in mysql ook te doen werken moet er naast de aanhalingstekens rekening gehouden 
		###met eventuele % tekens
		$pattern= "/(?U)('|\")?(%)?@$attributename(%)?('|\")?(,|\s|\)|$)/";
		
		###We vervangen het patroon door $value. We moeten er wel voor zorgen dat als er aanhalingstekens rond @atribute stonden dat 
		###die er blijven staan. Als er een komma of een spatie volgde op het attribuut moeten deze ook behouden blijven.
		$queryafterreplacement = preg_replace($pattern,"\${1}\${2}$value\${3}\${4}\${5}",$this->activequery);
	
		#echo "<p>query: $this->activequery";
		#echo "<br>attribute: $attributename";
		#echo "<br>value: $value";
		#echo "<br>".$queryafterreplacement;

		#BUGFIX: onderstaande code was uit de eerste versie van setAttribute waarbij het zoeken naar @attribute anders verliep
		#$searchstring = "@".$attributename;
		#$queryafterreplacement = str_ireplace($searchstring,$value,$this->activequery);
		
		###Als het attribuut niet werd gevonden dan is $queryafterreplacement=$this->activequery
		###=> Exception
		if($queryafterreplacement !== $this->activequery)
		{
			$this->activequery = $queryafterreplacement;
		}
		else
		{
			throw new Exception("The attribute '$attributename' was not found in the query '$this->activequery'");
		}
	}
	
	Public function ExecuteQuery()
	{
		if(!empty($this->activequery))
		{
			$result = @mysql_query($this->activequery,$this->connectionid);
		
			if(!$result)
			{
				#er is een fout opgetreden bij de uitvoer van de query.
				#De query wordt gelogd
				$extendedmessage = "MySQL reported an errormessage: \"".mysql_error($this->connectionid)."\" while executing query: \"".$this->activequery."\"";

				throw new Exception($extendedmessage);
			}
			else
			{
			$this->result = $result;
			}
		}
		else
		{
			throw new Exception("You must set a query before you try to execute it...");
		}
	}
	
	Public function GetResultArray()
	{
		#Eerst kijken we of er wel degelijk al een query is uitgevoerd.
		if(!empty($this->activequery))
		{
			#Er wordt gekeken of er wel rijen zijn om terug te geven.
			if($this->GetNumRows()>0)
			{
				while($row=mysql_fetch_array($this->result))
				{
					#de rijen worden toegevoegd in aan de resultarray
					$resultarray[]=	$row;
				}	
			
				#de resultarray wordt teruggegeven
				return $resultarray;
			}
			else
			{
			return false;
			}
		}
		else
		{
		#Er is nog geen query uitgevoerd, er kan dus ook onmogelijk een resultaat worden terugegeven.
		throw new Exception("You cannot get the result of a query when you haven't given me one...");
		}
	}
	
	Public function GetScalar()
	{
		if(!empty($this->activequery))
		{
			#Scalars worden alleen gebruikt wanneer er slechts 1 veld wordt teruggegeven.
			#Er wordt in dit geval dan ook een controle uitgevoerd of er wel degelijk maar 1 veld is.
			if ((mysql_num_fields($this->result) == 1) and ($this->getNumRows() == 1))
			{
				#Het eerste veld van de eerste rij moet worden weergegeven.
				$row = mysql_fetch_row($this->result);
				return $row[0];
			}
			else
			{
				throw new Exception("You executed a getScalar while the result is not a scalar. Query: $this->activequery");
			}
		}
		else
		{
		throw new Exception("You cannot get the result of a query when you haven't given me one...");
		}
	}
	
	Public function GetNumRows()
	{
		if(!empty($this->activequery))
		{
			$numberofrows = mysql_num_rows($this->result);
			return $numberofrows;
		}
		else
		{
		throw new Exception("You cannot get the result of a query when you haven't given me one...");
		}
	}
	
	Public function getLastId()
	{
		$nummer = mysql_insert_id($this->connectionid);
		if($nummer==0)
		{
			#Het nummer is nul dus er werd geen insert uitgevoerd
			throw new Exception("You tried to get the last insert id, but MySQL returned 0");
		}
		else
		{
			return $nummer;
		}
	}
}
?>