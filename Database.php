<?php

/**
* Les informations d'identifications sont stockés dans une classe de configuration (GlobalConfig).
* En voici un extrait :
*
*   private static $db_config = array(
*       'db_name' => 'yolo',
*       'db_user' => 'root',
*       'db_pass' => '',
*       'db_host' => 'localhost',
*       'db_charset' => 'utf8'
*   );
*
*   public static function getBddConfig() {
*       return self::$db_config;
*   }
*
*/

namespace App\Classes;

use App\Config\GlobalConfig;
use \PDO;

class Database
{
    private $db_config;

    private $db_name;
    private $db_user;
    private $db_pass;
    private $db_host;
    private $db_charset;

    private $pdo;

    public function __construct($dbname = null, $db_user = null, $db_pass = null, $db_host = null, $db_charset = null)
    {
        /** Récupération de la configuration DB */
        $this->db_config = GlobalConfig::getBddConfig();

        /** Récupération des arguments sous la forme d'un tableau */
        $parameters = func_get_args();

        /** Si aucun paramètre n'est donné, enregistrer la configuration par défaut */
        if (empty($parameters)) {
            $this->db_name = $this->db_config['db_name'];
            $this->db_user = $this->db_config['db_user'];
            $this->db_pass = $this->db_config['db_pass'];
            $this->db_host = $this->db_config['db_host'];
            $this->db_charset = $this->db_config['db_charset'];
        } else {

            /** Synchronisation des noms des paramètres */
            $link_parameters = array(
                    'db_name' => $parameters[0],
                    'db_user' => $parameters[1],
                    'db_pass' => $parameters[2],
                    'db_host' => $parameters[3],
                    'db_charset' => $parameters[4]
            );
            
            foreach ($link_parameters as $key => $data) {
                /** Si la valeur n'est pas égale à "false", l'enregistrer à la place de la valeur par défaut */
                if ($data !== false) {
                    $this->$key = $data;
                } else {
                    $this->$key = $this->db_config[$key];
                }
            }
        }
    }

    public function __debugInfo()
    {
        return [
                "Configuration de connexion" => [
                        "Base de données (db_name)" => $this->db_name,
                        "Uutilisateur (db_user)" => $this->db_user,
                        "Mot de passe (db_pass)" => "*****",
                        "Host (db_name)" => $this->db_host,
                        "Encodage (db_charset)" => $this->db_charset
                ]
        ];
    }

    private function getPDO()
    {
        /** N'autoriser aucune connexion simultanée */
        if ($this->pdo === null)
        {
            $pdo = new PDO("mysql:dbname=$this->db_name;host=$this->db_host", $this->db_user, $this->db_pass);
            $pdo->exec("set names $this->db_charset");

            /** Affichage de toute les erreurs (MODE FATAL) */
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

            /** Conversion des strings vides en valeur NULL */
            $pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);

            $this->pdo = $pdo;
        }

        return $this->pdo;
    }

    public function query($statement)
    {
        $req = $this->getPDO()->query($statement);
        $data_pack = $req->fetchAll(PDO::FETCH_OBJ);
        return $data_pack;
    }

}
