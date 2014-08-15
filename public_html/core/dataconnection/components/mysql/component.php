<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class DataConnection
{
    Private $parameters;
    Private $mysqli;
    Private $activeQuery;
    Private $queryResult=false;
    Private $attributes=array();
    Private $queryText;
    
    
    public function __construct()
    {
                #Ophalen van de verbindingsgegevens
		require_once $_SERVER['DOCUMENT_ROOT']."/core/fileaccess/database.php";
		$this->parameters = GetDataBaseParameters();
                
                ###We maken het mysqli object
                $this->mysqli = new mysqli();
                
                ###We verbinden met de database
                $this->connect();
                
    }
    
    private function connect()
    {
        	$host = $this->parameters['host'];
		$user = $this->parameters['user'];
		$password = $this->parameters['password'];
		$database = $this->parameters['database'];
                
                $confirmation = $this->mysqli->real_connect($host, $user, $password, $database);
                
                if(!$confirmation)
                {
                    throw new Exception($this->mysqli->connect_error, $this->mysqli->connect_errno);
                }
    }
    
    private function replaceAttribute($matches)
    {
        ###Dit is een callbackfunctie (zie setQuery)
        ###Voor iedere match wordt deze functie uitgevoerd
            #$matches[0] = de volledige match
            #$matches[1] = het datatype met de haakjes
            #$matches[2] = het datatype zonderhaakjes
            #$matches[3] = de naam van het attribuut
            #Enkel 2 en 3 zijn interessant de rest is om het patroon te doen werken
            
            ###Oude code definieert geen datatype => we nemen string als standaard
            if(empty($matches [2]))
            {
                $matches[2]='s';
            }
            
            $this->attributes[strtolower($matches[3])]['datatype']=strtolower($matches[2]);
            
            ###We geven ook een lege value aan ieder veld, deze wordt later ingevuld
            $this->attributes[strtolower($matches[3])]['value']=null;
            
            $replacement = '?';
            return $replacement;
    }
    
    ###Public methods
    public function setQuery($query)
    {
        ###Soms wordt één object gebruikt voor meerdere queries => bij setQuery alles resetten
        $this->queryResult=false;
        $this->attributes=array();
        
        $this->queryText = $query;
        
        ##ons formaat van query omzetten naar formaat met ? dat door mysqli begrepen wordt
        ##Ook de haakjes rond de attributen moeten weg
        $pattern = "/(?i)'?@(\((i|d|s|b)\))?([a-z0-9]*)'?/";
        
        $query=preg_replace_callback($pattern,array($this,'replaceAttribute'), $query);
        
        
        ###We geven de query door aan MySQLi
        $this->activeQuery=$this->mysqli->prepare($query);    
        
        ###Als hierna $this->activeQuery geen waarde heeft is er iets fout gelopen
        if(!$this->activeQuery)
        {
            throw new Exception('Query syntax is not correct. MySQL reported this error: '.$this->mysqli->error.' while preparing this statement: '.$this->queryText, $this->mysqli->errno);
        }
    }
    
    public function ExecuteQuery()
    {    
        if($this->activeQuery)
        {
            $typestring = null;
            $valuearray = array();

            foreach ($this->attributes as &$value)
            {
                $typestring = $typestring.$value['datatype'];

                $valuearray[]=&$value['value'];
            }

            $mergedarray[] = $typestring;
            $mergedarray = array_merge($mergedarray,$valuearray);

            ###Hier roepen we eigenlijk bind_param aan maar met de $mergedarray als argumenten
            if(count($this->attributes)>0)
            {
                call_user_func_array(array($this->activeQuery,'bind_param'), $mergedarray);
            }

            ###We voeren de query uit
            $confirmation=$this->activeQuery->execute();

            if($confirmation)
            {
                ###Query is geslaagd
                ###We halen het resultaat op
                $this->queryResult = $this->activeQuery->get_result();
            }
            else
            {
                ###Query is niet geslaagd
                throw new Exception('There was an error while executing the query '. $this->queryText . 'with attributearray' . print_r($this->attributes).' Mysql reported this error '.$this->mysqli->error, $this->mysqli->errno);
            }
        
        }
        else {
              throw new Exception("Impossible to execute a query if you haven't set one");
        }
    }
    
    public function setAttribute($attributename,$value)
    {
        $attributename=strtolower($attributename);
        
        if($this->attributes[$attributename])
        {
            ###Het attribuut bestaat in de query
            $this->attributes[$attributename]['value']=&$value;
        }
        else
        {
            ###Het attribuut is niet gevonden
            throw new Exception("The attribute '$attributename' was not found in the query '$this->queryText'");
        }
       
    }
    
    public function GetResultArray()
    {
        if($this->queryResult)
        {
        return $this->queryResult->fetch_all(MYSQLI_BOTH);
        }
        else
        {
            throw new Exception('you must first execute the query before getting the results');
        }
    }
    
    public function getRowAsObject()
    {
        ###deze functie geeft één rij terug als een object
        if($this->queryResult)
        {
                
                return $this->queryResult->fetch_object();
        }
        else
        {
            throw new Exception('you must first execute the query before getting the results');
        }

    }
    
    public function getScalar()
    {
        ###bij scalar willen we enkel het eerste veld van de eerste rij krijgen.
        ###Eerst controleren we of het wel degelijk om een scalar gaat
        if(($this->queryResult->num_rows==1) && ($this->queryResult->field_count==1))
        {
            ###Het gaat effectief om een scalar, we geven de waarde terug.
            $row = $this->queryResult->fetch_row();
            return $row[0];
        }
        else
        {
            throw new Exception('You tried getting the result with getScalar, but the result is not a scalar');
        }
    }
    
    public function GetNumRows()
    {
        return $this->activeQuery->affected_rows;
    }
    
    public function getLastId()
    {
        return $this->activeQuery->insert_id;
    }
}

/*//DEBUG
$debug = new DataConnection;
$debug->setQuery("SELECT parameters.id,parameters.name,parameters.value,parameters.overridable FROM parameters WHERE parameters.name='@searchstring'");
$debug->setAttribute('searchstring', 'CORE_SSL_ENABLED');
$debug->ExecuteQuery();

$result=$debug->GetResultArray();

print_r($result);
*/
 