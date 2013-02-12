<?php
 require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataconnection/componentselector.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/abonnement.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/abonnee.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/modules/nieuwsbrief/entity/nieuwsbrief.php';
 
 function data_getAbonnementenlijst()
 {
	 ###Deze moet een array teruggeven met alle abonnementen op de server.
	 $query = 'SELECT abonnementen.id,abonnementen.naam FROM abonnementen';
	 
	 $db = new dataconnection();
	 $db->setQuery($query);
	 $db->executeQuery();
	 
	 if($db->getNumRows()>0)
	 {
		$results = $db->getResultArray();
		
		foreach($results as $key=>$value)
		{
			list($id,$naam)=$results[$key];
			
			$abonnement = new abonnement(intval($id),$naam);
			
			###$id wordt als sleutel gegeven zodat je gemakkelijker kan zoeken in de array
			$return[$id] = $abonnement;
		}
		
		return $return;
	 }
         /*
	 else
	 {
		 throw new Exception('No subscriptions defined');
          *Was te streng.
          */
	 
 }
 
 function data_createAbonnement(abonnement $abonnement)
 {
	 ###Deze functie voegt een nieuw abonnement toe aan de database
	 $query = "INSERT INTO abonnementen (naam) VALUES ('@abonnementsnaam')";
	 
	 $db = new dataconnection();
	 $db->setQuery($query);
	 $db->setAttribute("abonnementsnaam",$abonnement->getNaam());
	 $db->executeQuery();
	 
	 $nieuwabonnement = new abonnement($db->getLastId(),$abonnement->getNaam());
	 
	return $nieuwabonnement;
	 
 }
 
 function data_createAbonnee(abonnee $abonnee)
 {
	 ###Deze query voegt een nieuwe abonnee toe aan de databank
	 $query = "INSERT INTO abonnees (voornaam,familienaam,mailadres,confirmed,secretkey) VALUES ('@voornaam','@familienaam','@mailadres','@confirmed','@secretkey')";
	 
	 $db = new dataconnection();
	 $db->setQuery($query);
	 $db->setAttribute("voornaam",$abonnee->getVoornaam());
	 $db->setAttribute("familienaam",$abonnee->getFamilienaam());
	 $db->setAttribute("mailadres",$abonnee->getMailadres());
	 $db->setAttribute("confirmed",'N');
	 
	 ###Nu moet de sleutel berekend worden
	$random = mt_rand();	
	$key = md5($abonnee->getMailadres().$random);
	
	$db->setAttribute("secretkey",$key);
	
	$db->ExecuteQuery();
	
	$nieuweabonnee = new abonnee($db->getLastId(),$abonnee->getVoornaam(),$abonnee->getFamilienaam(),$abonnee->getMailadres(),$key);
	
	return $nieuweabonnee;
 }

function data_dubbelMailadres($mailadres)
{
	###Deze functie controleert of een mailadres reeds voorkomt. Als dat het geval is komt er true terug anders false.
	$query = "SELECT id from abonnees WHERE abonnees.mailadres='@mailadres'";
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute("mailadres",$mailadres);
	$db->ExecuteQuery();
	
	if($db->getNumRows() > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function data_editAbonnee($abonnee)
{
	$query = "UPDATE abonnees SET abonnees.voornaam='@voornaam',abonnees.familienaam='@familienaam',abonnees.mailadres='@mailadres' WHERE abonnees.id='@id'";
	
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute("voornaam",$abonnee->getVoornaam());
	$db->setAttribute("familienaam",$abonnee->getFamilienaam());
	$db->setAttribute("mailadres",$abonnee->getMailadres());
	$db->setAttribute("id",$abonnee->getId());
	
	$db->ExecuteQuery();
	
	###Nu moeten ook de abonnementen aangepast worden
	###Eerst verwijderen we alle bestaande lijnen in de tabel abonnementenlink
	$query = 'DELETE FROM abonnementenlink WHERE abonnementenlink.abonnee=@abonnementid';
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute("abonnementid",$abonnee->getID());
	$db->executeQuery();
	
	###Nu kunnen we de nieuwe links leggen
	$subscriptionlist = $abonnee->getSubscriptions();
	
	#print_r($subscriptionlist);
	
	$db = new dataconnection();
	
	foreach($subscriptionlist as $key=>$subscription)
	{
		###DEBUG
		#print_r($subscription);
		
		$query = "INSERT INTO abonnementenlink (abonnement,abonnee) VALUES (@abonnement,@abonnee)";
		$db->setQuery($query);
		$db->setAttribute("abonnement",$subscription->getID());
		$db->setAttribute("abonnee", $abonnee->getId());
		$db->executeQuery();
	}
}

function data_confirmAbonnee(abonnee $abonnee)
{
	###Deze functie verandert de waarde van confirmed voor de betreffende abonnee
	$query = "UPDATE abonnees SET abonnees.confirmed='Y' WHERE abonnees.id=@id";
	
	$db= new dataconnection();
	$db->setQuery($query);
	$db->setAttribute("id",$abonnee->getId());
	
	$db->ExecuteQuery();
}

function data_getAbonneeByKey($key)
{
	$query = "SELECT abonnees.id,abonnees.voornaam,abonnees.familienaam,abonnees.mailadres,abonnees.confirmed,abonnees.secretkey FROM abonnees WHERE abonnees.secretkey='@key'";
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute("key",$key);
	
	$db->executeQuery();
	
	if($db->getNumRows()>0)
	{
		###We hebben de abonnee in kwestie te pakken
		$result = $db->getResultArray();
		list($id,$voornaam,$familienaam,$mailadres,$confirmed,$secretkey) = $result[0];
		
		$abonnee = new abonnee(intval($id),$voornaam,$familienaam,$mailadres,$secretkey,$confirmed);
		
		###Nu moeten we de abonnementen ophalen, als die er al zijn
		$query = "SELECT abonnement from abonnementenlink WHERE abonnementenlink.abonnee='@id'";
		$db2 = new dataconnection();
		$db2->setQuery($query);
		$db2->setAttribute("id",$abonnee->getId());
		$db2->executeQuery();
		
		if($db2->getNumRows()>0)
		{
			###Er zijn abonnementinschrijvingen
			###We halen een lijst met mogelijke abonnementen op
			$abonnementenlijst = data_getAbonnementenlijst();
			
			$result2 = $db2->getResultArray();
			
			$inschrijvingslijst = array();
			
			foreach($result2 as $key=>$value)
			{
				$inschrijvingslijst[] = $abonnementenlijst[$value[0]];
			}
			
			###Nu hebben we een lijst met de abonnementsobjecten waarvoor de abonnee ingeschreven is
			###Deze moeten we nu toevoegen aan het abonnee-object
			$abonnee->editSubscriptions($inschrijvingslijst);
		}

		
		return $abonnee;

	}
	else
	{
		###Niks gevonden
		return false;
	}
}

function data_addNieuwsbrief(nieuwsbrief $nieuwsbrief,array $abonnementen)
{
	###Eerst voegen we de lijn in de tabel nieuwsbrief toe
	$query = "INSERT INTO nieuwsbrieven (maand,jaar,titel) VALUES (@maand,@jaar,'@titel')";
	
	$db = new dataconnection();
	$db->setQuery($query);
	$db->setAttribute('maand',$nieuwsbrief->getMaand());
	$db->setAttribute('jaar',$nieuwsbrief->getJaar());
        $db->setAttribute('titel',$nieuwsbrief->getTitel());
	

	$db->executeQuery();

        $nieuwsbriefid = $db->getLastId();
	
	###Nu creëren we een nieuw nieuwsbriefobject maar nu met het juiste id
	$nieuwsbrief = new nieuwsbrief($db->getLastId(),intval($nieuwsbrief->getMaand()),intval($nieuwsbrief->getJaar()),$nieuwsbrief->getTitel());
	
	###Nu moeten we de koppeling met de abonnementen aan de databank toevoegen
	foreach($abonnementen as $abonnement)
	{
		$db2 = new dataconnection();
		
		$query = "INSERT INTO nieuwsbriefabonnementen (nieuwsbrief,abonnement) VALUES (@nieuwsbriefid,@abonnementid)";
		$db2->setQuery($query);
		$db2->setAttribute('nieuwsbriefid',$nieuwsbrief->getID());
		$db2->setAttribute('abonnementid',$abonnement->getID());
		$db2->executeQuery();
	}

        return $nieuwsbriefid;
        ###Op het einde van de rit retourneert de functie het id van de nieuwsbrief, dat kan dan gebruikt worden als uniekebestandsnaam
	#print_r($nieuwsbrief);
}

function data_getnieuwsbrievenVoorAbonnement(abonnement $abonnement)
{
    ###Deze functie geeft de nieuwsbriefobjecten terug voor een bepaald abonnement
    $query = "SELECT nieuwsbrieven.id,nieuwsbrieven.timestamp,nieuwsbrieven.maand,nieuwsbrieven.jaar,nieuwsbrieven.titel,nieuwsbrieven.verstuurd from nieuwsbrieven LEFT JOIN nieuwsbriefabonnementen ON nieuwsbrieven.id=nieuwsbriefabonnementen.nieuwsbrief WHERE nieuwsbriefabonnementen.abonnement = @id";
    
    $id = $abonnement->getId();
    
    $db=new DataConnection();
    $db->setQuery($query);
    $db->setAttribute("id", $id);
    
    $db->ExecuteQuery();
    
    $result = $db->GetResultArray();
    
    return $result;
}  

function data_getNieuwsbrieven()
{
 ###Deze functie haalt alle nieuwsbrieven op en giet deze in nieuwsbriefobjecten
 $query = "SELECT nieuwsbrieven.id,nieuwsbrieven.maand,nieuwsbrieven.jaar,nieuwsbrieven.titel,nieuwsbrieven.verstuurd FROM nieuwsbrieven";
 
 $db= new DataConnection();
 $db->setQuery($query);
 $db->ExecuteQuery();
 
 $result = $db->GetResultArray();
 
 $nieuwsbrieven = Array();
 
 foreach($result as $value)
 {
     list($id,$maand,$jaar,$titel,$verstuurd)=$value;
     
     if($verstuurd=='Y')
     {
         $verstuurd = true;
     }
     else
     {
         $verstuurd = false;
     }
     
     $nieuwsbrief = new Nieuwsbrief(intval($id), intval($maand), intval($jaar), $titel, $verstuurd);
     $nieuwsbrieven[] = $nieuwsbrief;
 }
 
 return $nieuwsbrieven;
 
}

function data_getNieuwsbriefAbonnees(nieuwsbrief $nieuwsbrief)
{
    ###Deze functie haalt de abonnees op die de nieuwsbrief moeten ontvangen
    $query="SELECT DISTINCT abonnees.id,abonnees.voornaam,abonnees.familienaam,abonnees.mailadres,abonnees.secretkey from abonnees LEFT JOIN abonnementenlink ON abonnees.id=abonnementenlink.abonnee LEFT JOIN abonnementen on abonnementenlink.abonnement=abonnementen.id LEFT JOIN nieuwsbriefabonnementen ON abonnementen.id=nieuwsbriefabonnementen.abonnement LEFT JOIN nieuwsbrieven ON nieuwsbriefabonnementen.nieuwsbrief=nieuwsbrieven.id WHERE nieuwsbrieven.id=@id AND abonnees.confirmed='Y'";
  
    $db = new DataConnection();
    $db->setQuery($query);
    $db->setAttribute('id', $nieuwsbrief->getID());
    $db->executeQuery();
    
    $result = $db->GetResultArray();
    
    if($db->GetNumRows()>0)
    {
        $abonneelijst = array();
        ###We maken abonneeobjecten van ieder resultaat
        foreach($result as $value)
        {
        list($id,$voornaam,$familienaam,$mailadres,$secretkey)=$value;
        $abonnee = new abonnee(intval($id), $voornaam, $familienaam, $mailadres, $secretkey, true);
        
        $abonneelijst[]=$abonnee;
        }
        
        return $abonneelijst;
    }
    else
    {
        return false;
    }
}

function data_setNieuwsBriefStatusSent(nieuwsbrief $nieuwsbrief)
{
    ###Deze functie verandert het attribuut 'verstuurd' van de nieuwsbrieven die verzonden zijn
    $query = "UPDATE nieuwsbrieven SET nieuwsbrieven.verstuurd='Y' WHERE nieuwsbrieven.id=@id";
    
    $db= new DataConnection();
    $db->setQuery($query);
    $db->setAttribute("id", $nieuwsbrief->getID());
    $db->ExecuteQuery();
}

/*
###Debug 
$abonnement = data_getAbonnementbyKey(2);
data_getnieuwsbrieven($abonnement);
*/

function data_deleteAbonnee(abonnee $abonnee)
{
    $query = "DELETE FROM abonnees WHERE abonnees.id=@id";
    
    $db= new DataConnection();
    $db->setQuery($query);
    $db->setAttribute("id", $abonnee->getID());
    $db->ExecuteQuery();
}
?>