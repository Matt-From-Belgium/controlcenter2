<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DataConnectionNew
{
    Private $parameters;
    Private $mysqli;
    Private $activeQuery;
    Private $queryResult;
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
            $this->attributes[strtolower($matches[3])]['datatype']=strtolower($matches[2]);
            
            $replacement = '?';
            return $replacement;
    }
    
    ###Public methods
    public function setQuery($query)
    {
        $this->queryText = $query;
        
        ##ons formaat van query omzetten naar formaat met ? dat door mysqli begrepen wordt
        $pattern = "/(?i)@(\((i|d|s|b)\))?([a-z0-9]*)/";
        
        //preg_match_all($pattern, $query, $attributes,PREG_SET_ORDER);
        $query=preg_replace_callback($pattern,array($this,'replaceAttribute'), $query);
        
        
        print_r($this->attributes);
        echo $query;
        
        
        ###We geven de query door aan MySQLi
        $this->activeQuery=$this->mysqli->prepare($query);    
        
        ###Als hierna $this->activeQuery geen waarde heeft is er iets fout gelopen
        if(!$this->activeQuery)
        {
            throw new Exception($this->mysqli->error, $this->mysqli->errno);
        }
    }
    
    public function ExecuteQuery()
    {
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
            throw new Exception($this->mysqli->error, $this->mysqli->errno);
        }
        
        
    }
    
    public function setAttribute($attributename,$value)
    {
        $attributename=strtolower($attributename);
        
        if($this->attributes[$attributename])
        {
            ###Het attribuut bestaat in de query
            echo 'ok';
        }
        else
        {
            ###Het attribuut is niet gevonden
            throw new Exception("The attribute '$attributename' was not found in the query '$this->queryText'");
        }
       
    }
    
    public function GetResultArray()
    {
        return $this->queryResult->fetch_array();
    }
    
    public function getScalar()
    {
        
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

//DEBUG
$debug = new DataConnectionNew;
$debug->setQuery('SELECT * from users WHERE users.id=@(i)id and users.username=@(s)username');
$debug->setAttribute('ide', 1);
//$debug->ExecuteQuery();
//echo $debug->GetNumRows();
//$result=$debug->GetResultArray();
//print_r($result);