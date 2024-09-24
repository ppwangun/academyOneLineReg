<?php
/*****************************************************************
* Projet    : W-Academy - Angela
* Code PHP 	: Synchronisation.php
*****************************************************************
* Auteur : Njine Chuangueu
* E-mail : <chuanguen@gmail.com>
*****************************************************************
* Date de création      : 08-08-2024 (08 Aout 2024)
* Derniére modification : 19-08-2024 (19 Aout 2024)
*****************************************************************
* Description
* 	Les codes ci-dessous permettent de synchroniser les  
* 	informations de l'application W-Academy avec celles de Odoo.  
****************************************************************/

namespace Njine\Odoo;

use Laminas\Http\Headers;  
use Laminas\Http\Cookies; 
use Laminas\Http\Request;  
use Laminas\Http\Response;  
use Laminas\Http\Client;  

class Synchronisation
{

    /**
     * @var string $user, $pass, $db, $host : Représentent les paramètres de connexion au serveur ODOO
     */
	private $user = "webservice@agenla.com";
	private $pass = "webservice";
	private $db   = "testagenla";
	private $host = "http://testagenla.groupesia.com";

    /**
     * @var Client $client : Représente une connexion HTTP au serveur ODOO
     */
	private $client = null;

    /**
     * Constructeur de la classe
     *
     * @param array $parametres : Contient les paramètres d'authentification pour la connexion au serveur ODOO
     */
    public function __construct($parametres = null)
    {
        if ($parametres !== null) 
		{
			extract($parametres);			
            $this->user = $user;
            $this->pass = $pass;
            $this->db = $db;
            $this->host = $host;
        }
    }

    /**
     * Méthode qui tente d'établire une connexion au serveur Odoo 
     *
     * @return array $info : Contient le résultat du traitement la requête
     */
	public function connexionOdoo()
	{
		if($this->client === null)
		{	
			$url = $this->host."/web/session/authenticate";		
			$request = new Request();
			$request->setMethod(Request::METHOD_POST);
			$request->setUri($url);
			$header = $request->getHeaders();
			$header->addHeaders(['Content-Type' => 'application/json']);
			$header->addHeaders(['User-Agent' => 'w-academy']);
			$content = '{"jsonrpc":"2.0","method":"call","params":{"login":"'.$this->user.'","password":"'.$this->pass.'","db":"'.$this->db.'"}}';
			$request->setContent($content);
			$client = new Client();
			$response = $client->send($request);
			$info = $this->traitementReponseOdoo($request, $response);
			if($info["resultat"] == "success")
			{ $this->client = $client; }
			return $info;
		}
	}

    /**
     * Methode qui tente d'ajouter un enseignant dans la base de donnée du serveur Odoo
     *
     * @params string $partner_id, $name, $email, $phone, $street : Contiennent les paramètres de la requête
     * @return array $info : Contient le résultat du traitement la requête
     */
	public function ajouterEnseignant($partner_id, $name, $email, $phone, $street)
	{
		$url = $this->host."/api/agenla/create_teacher";		
		$msg = '{
			"jsonrpc":"2.0",
			"data":[{
				"partner_id":"'.$partner_id.'",
				"name":"'.strtoupper($name).'",
				"email":"'.$email.'",
				"phone":"'.$phone.'",
				"street":"'.$street.'"
			}]
		}';
		$info = $this->executionRequeteOdoo($url, $msg);
		return $info;
	}

    /**
     * Methode qui tente d'ajouter un étudiant dans la base de donnée du serveur Odoo
     *
     * @params string $partner_id, $name, $email, $phone, $street : Contiennent les paramètres de la requête
     * @return array $info : Contient le résultat du traitement la requête
     */
	public function ajouterEtudiant($partner_id, $name, $email, $phone, $street)
	{
		$url = $this->host."/api/agenla/create_student";		
		$msg = '{
			"jsonrpc":"2.0",
			"data":[{
				"partner_id":"'.$partner_id.'",
				"name":"'.strtoupper($name).'",
				"email":"'.$email.'",
				"phone":"'.$phone.'",
				"street":"'.$street.'"
			}]
		}';
		$info = $this->executionRequeteOdoo($url, $msg);
		return $info;
	}

    /**
     * Methode qui tente d'ajouter un type de prestation à facturer dans la base de donnée du serveur Odoo
     *
     * @params int $product_id, string $name, $list_price, $type : Contiennent les paramètres de la requête
     * @return array $info : Contient le résultat du traitement la requête
     */
	public function ajouterTypeFacturation($product_id, $name, $list_price, $type)
	{
		$url = $this->host."/api/agenla/product/create";		
		$msg = '{
			"jsonrpc":"2.0",
			"data":[{
				"product_id":'.$product_id.',
				"name":"'.$name.'",
				"list_price":"'.$list_price.'",
				"type":"'.$type.'"
			}]
		}';
		$info = $this->executionRequeteOdoo($url, $msg);
		return $info;
	}
	
    /**
     * Methode qui tente d'ajouter une facture d'enseignant dans la base de donnée du serveur Odoo
     *
     * @params int $partner_id, $product_id, $quantity, $price_unit : Contiennent les paramètres de la requête
     * @params string $name, $date_invoice : Contiennent les paramètres de la requête
     * @return array $info : Contient le résultat du traitement la requête
     */
	public function factureEnseignant($partner_id, $date_invoice, $product_id, $name, $quantity, $price_unit)
	{
		$url = $this->host."/api/agenla/create_teacher_invoice";		
		$msg = '{
			"jsonrpc":"2.0",
			"data":{
				"partner_id":"'.$partner_id.'",
				"date_invoice":"'.$date_invoice.'",
				"invoice_line_ids":[{
					"product_id":'.$product_id.',
					"name":"'.$name.'",
					"quantity":'.$quantity.',
					"price_unit":'.$price_unit.'
				}]
			}
		}';
		$info = $this->executionRequeteOdoo($url, $msg);
		return $info;
	}
	
    /**
     * Methode qui tente d'ajouter des frais d'inscription d'étudiant dans la base de donnée du serveur Odoo
     *
     * @params int $partner_id, $product_id, $quantity, $price_unit : Contiennent les paramètres de la requête
     * @params string $name, $date_invoice : Contiennent les paramètres de la requête
     * @return array $info : Contient le résultat du traitement la requête
     */
	public function factureEtudiant($partner_id, $date_invoice, $product_id, $name, $quantity, $price_unit)
	{
		$url = $this->host."/api/agenla/create_student_invoice";		
		$msg = '{
			"jsonrpc":"2.0",
			"data":{
				"partner_id":"'.$partner_id.'",
				"date_invoice":"'.$date_invoice.'",
				"invoice_line_ids":[{
					"product_id":'.$product_id.',
					"name":"'.$name.'",
					"quantity":'.$quantity.',
					"price_unit":'.$price_unit.'
				}]
			}
		}';
		$info = $this->executionRequeteOdoo($url, $msg);
		return $info;
	}
	
    /**
     * Methode qui tente de transmettre les requêtes HTTP au serveur Odoo
     *
     * @params string $url, $msg : Contiennent l'url et le corps de la requête (objet JSON) 
     * @return array $info : Contient le résultat du traitement la requête
     */
	private	function executionRequeteOdoo($url, $msg)
	{
		if($this->client === null)
		{ 
			$info = $this->connexionOdoo();
			if($info["resultat"] != "success")
			{ return $info; }			
		}
		$request = $this->client->getRequest();
		$request->setUri($url);
		$request->setContent($msg);
		$response = $this->client->send($request);
		$info = $this->traitementReponseOdoo($request, $response);
		return $info;
	}

    /**
     * Methode qui traite les réponses HTTP du serveur Odoo
     *
     * @param Request $requetehttp, Response $responsehttp : Contiennent la requête transmise et la réponse reçue
     * @return array $info : Contient le résultat du traitement de la réponse
     */
	private	function traitementReponseOdoo($requetehttp, $responsehttp)
	{	
		$info["code"] = $responsehttp->getStatusCode();
		if ($responsehttp->isSuccess()) 
		{ 
			$php_obj = json_decode($responsehttp->getBody());
			if(isset($php_obj->result))
			{ 
				if(!isset($php_obj->result->status))
				{
					$info["resultat"] = "success" ; 
					$info["reponse_odoo"] = $php_obj->result; 
				}
				elseif(isset($php_obj->result->status) && $php_obj->result->status == "success")
				{
					$info["resultat"] = "success" ; 
					$info["reponse_odoo"] = $php_obj->result->msg; 
				}
				else
				{
					$info["resultat"] = "echec" ; 
					$info["details"]["status_odoo"] = $php_obj->result->status;
					$info["details"]["message_odoo"] = $php_obj->result->msg;
					$info["details"]["requete_wadm"] = $requetehttp->toString();
					$info["details"]["reponse_odoo"] = $responsehttp->getBody()?$responsehttp->getBody():null;
				}					
			}
			else
			{
				$info["resultat"] = "echec"; 
				if(isset($php_obj->error))
				{ $info["details"]["erreur_odoo"] = $php_obj->error->message; }
				else
				{ $info["details"]["erreur_odoo"] = "Erreur inconnue !"; }
				$info["details"]["requete_wadm"] = $requetehttp->toString();
				$info["details"]["reponse_odoo"] = $responsehttp->getBody();
			}
		}
		else
		{ 
			$info["resultat"] = "echec"; 
			$info["details"] = $responsehttp->toString(); 
		}
		return $info;
	}
}
?>