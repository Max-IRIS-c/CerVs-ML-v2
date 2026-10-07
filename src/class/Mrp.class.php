<?php
	require('../inc/config-mrp.php');
/**
 *
 * Pseudo Classe utilitaire permettant de gérer la session et les autres fonctions utiles
 * ce n'est pas une casse réelle mais il serai trop long de tout re-coder, j'ai donc juste ajouté des garde fou
 * 
 * 
 * ATTENTION POUR LA PROD il faut enlever devdirectory
 *
 */
if(empty($_SESSION["langCode"])){
    $_SESSION["langCode"]='fr';
}
class Mrp extends Db
{
	private $_db;
	private $_userSession;
	public $language;
	public $filedirectory;
	public $devdirectory;
	public $directory;
	public $langPath;

	function __construct()
		{
			// Need to be changed !!!! warning		
			$this->filedirectory = '/var/data/hosting/docs';
			$this->directory = URL_CONFIG_DIRECTORY;
			$this->langPath = URL_CONFIG_LANG_PATH;
			$this->_db = new DB();
						
			if(isset($_SESSION["userSession"])){
				$this->_userSession=$_SESSION["userSession"];
			}else{
				$this->_userSession=false;
			}
			if(empty($this->language))
			if (isset($_SESSION['langCode']))
			{
				$this->language=$_SESSION["langCode"];
			}
			else
			{			
				$this->language="fr";
			}
			// we check now what language was the last language selected by the user and we setup with it the interface.
		
			if (isset($_SESSION['id']))
			{
				$ligne = $this->_db->single('SELECT lang FROM tblLangueChoice WHERE employer='.$_SESSION['id']);
			}
			else
			{
				$ligne=false;
			}
			
			if($ligne != false){
				// if there is an entry in the db for this user, we setup default lang with it .
				$this->language=$ligne['lang'];
			}
			// delete all sessions not used for more than 1 hour
			$del = $this->_db->query('UPDATE admSessions SET sesConnected=0 WHERE sesUpdate <= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
		
		// We will load the translation here 
		
		// We have only DE for the moment then we simplify with DE.
		
		// Load FR-DE file (lang/de.csv)
		// If another lang is added we need to complexify a bit the logic but we have the process
		
		$nameofthefileoflanguage="de.csv";
		$loadFile=strtolower(file_get_contents($this->langPath.$nameofthefileoflanguage));
		$array_csv=str_getcsv($loadFile, ",", "\"", "\\");
		$this->traduction=$array_csv;
		
		}
	
	public function isConnected(){
		if(!isset($this->_userSession) || empty($this->_userSession)){
			return false;
		}else{
			$this->_db->bindTxt('session',$this->_userSession);		
			$ligne = $this->_db->single('SELECT * FROM admSessions WHERE session=:session AND sesConnected=1');
			if($ligne != false){
				$this->_db->bindInt('sesId', $ligne["sesId"]);
				$up = $this->_db->query('UPDATE admSessions SET sesUpdate="'.date('Y-m-d H-i:s').'" WHERE sesId=:sesId');
				return $ligne;
			}else{
				foreach ($_SESSION as $sessionkey=>$sessionvalue){
					$_SESSION[$sessionkey] = "";
				}
				$this->_userSession='';
				header('Location: index.php?err=sessionlost');
				//die('lostsession');	
			}
		}
	} 
	
	public function connectUser($ligne){
	    //On effectue le login
		$dir = substr(basename(getcwd()),-1);
		$session=$dir.md5(date('Y-m-d H:i:s').$ligne["utiId"].$ligne["groupId"]);

		$this->_db->bindTxt('session', $session);
		$this->_db->bindInt('empId', $ligne["empId"]);
		$this->_db->bindInt('autId', $ligne["autId"]);
		$this->_db->bindTxt('langCode', $ligne["langCode"]);

		$return = $this->_db->query("INSERT INTO 
			admSessions(session,empId,autId,langCode,sesConnected) 
			VALUES(:session,:empId,:autId,:langCode,1)");


        $_SESSION['nom']= $ligne['utiNom'].' '.$ligne['utiPrenom'];
        $_SESSION['id'] = $ligne['empId'];
		$_SESSION["userSession"] = $session;
        $_SESSION["langCode"] = $ligne["langCode"];

        $_SESSION['id'] = $ligne['empId'];
        $_SESSION['auth'] = $ligne['autId'];
        $_SESSION['statut'] = $ligne['empStatut'];
        $_SESSION['Nom'] = $ligne['empNom'];
        $_SESSION['Prenom'] = $ligne['empPrenom'];
			
		$this->setLanguage($ligne["langCode"]);
	}
	
	public function disconnect(){
		//On vide la session	
		$this->_db->bindTxt('session', $this->_userSession);
		$ligne = $this->_db->query('UPDATE admSessions SET sesConnected=0 WHERE session=:session');
		
		foreach ($_SESSION as $sessionkey=>$sessionvalue){
			$_SESSION[$sessionkey] = "";
		}
	}
	
    /**
     *
     * Stocke une donnée en session
     *
     * @param $key
     * @param $value
     */
    public static function set ($key, $value) {
       $_SESSION[$key] = serialize($value);
       return true;
    }
         
     
    /**
     *
     * Récupère une donnée de la sessin
     *
     * @param unknown_type $key
     */
    public static function get ($key) {
        if (self :: check($key)) {
            return unserialize($_SESSION[$key]);
        } else {
            return false;
        }
    }
     
     
    /**
     *
     * Vérifie qu'une données est présente en session
     *
     * @param $key
     */
    public static function check ($key) {
        return isset($_SESSION[$key]);
    }
     
     
    /**
     *
     * Supprime une donnée en session
     * @param unknown_type $key
     */
    public static function delete ($key) {
        if (self :: check($key)) {
            unset($_SESSION[$key]);
            return !self :: check($key);
        } else {
            return false;
        }
    }
     
     
    /**
     *
     * Ajoute un message d'erreur en session
     *
     * @param $message
     * @param $type
     */
    public static function message($message = null, $type = null) {
         
        if (is_null($message) && is_null($type)) {
            return self :: get('messages');
        }
         
        self :: set('messages', array($type => $message));
    }
    
    public function pageAccess(){
		$pageUrl=str_replace($this->devdirectory,'', $_SERVER['PHP_SELF']);
        $page=ucfirst(str_replace(array('.php'),'',basename($_SERVER['PHP_SELF'])));
        $usr_sess=$this->isConnected();

        if(!$usr_sess){
            header("location: ../index.php");
        }

        $this->_db->bindTxt('pageUrl',$pageUrl);
        $ligne = $this->_db->single('SELECT * FROM admPages WHERE pageUrl=:pageUrl');
        if($ligne==false){
            $this->_db->bindTxt('pageUrl',$pageUrl);
            $this->_db->bindTxt('pageName',$page);
            $this->_db->bindTxt('pageProtected',1);
            $this->_db->query('INSERT INTO admPages (pageName,pageUrl,pageProtected) VALUES (:pageName,:pageUrl,:pageProtected);');
            $ligne['pageId']=$this->_db->lastInsertId();
        }
        $this->_db->bindInt('pageId',$ligne['pageId']);
        $select = $this->_db->single('SELECT * FROM admPagesAutor WHERE pageId=:pageId');
        if($select==false){
            $sqlQuery = 'SELECT * FROM tblAutorisation';
            $resultGp = $this->_db->query($sqlQuery);
            foreach( $resultGp as $ligneGP ) {
                $this->_db->bindInt('pageId',$ligne['pageId']);
                $this->_db->bindInt('autId',$ligneGP['autId']);
                $this->_db->query('INSERT INTO admPagesAutor (pageId,autId) VALUES (:pageId,:autId);');

            }
        }

        if($usr_sess!=false){
            $query='SELECT * FROM admPages as p
                INNER JOIN admPagesAutor as pg ON p.pageId= pg.pageId
                WHERE pageUrl=:pageUrl AND autId=:autId';
            $this->_db->bindTxt('pageUrl',$pageUrl);
            $this->_db->bindInt('autId',$_SESSION['auth']);
            $ligne = $this->_db->query($query);
            if($ligne==false){
                header('Location: ../index.php?errad=noaccess&1');
                die('No access to this page connected');
            }
        }else{
            $query='SELECT * FROM admPages as p
                INNER JOIN admPagesAutor as pg ON p.pageId= pg.pageId
                WHERE pageUrl=:pageUrl';
            $this->_db->bindTxt('pageUrl',$pageUrl);
            $ligne = $this->_db->single($query);
            if($ligne==false){
                header('Location: ../index.php?errad=noaccess&2');
                die('No access to this page');
            }else{
                if($ligne['pageProtected']==1){
                    header('Location: ../index.php?errad=noaccess&3');
                    die('No access to this page');
                }
            }
        }

	}

    public function hasPageAccess($pageName){
        //die($_SERVER['PHP_SELF']);
        $pageUrl=str_replace($this->devdirectory,'', $pageName);
        $page=ucfirst(str_replace(array('.php'),'',basename($pageName)));
        $usr_sess=$this->isConnected();

        if(!$usr_sess){
            return false;
        }
        $query='SELECT * FROM admPages as p
                INNER JOIN admPagesAutor as pg ON p.pageId= pg.pageId
                WHERE pageUrl=:pageUrl AND autId=:autId';
        $this->_db->bindTxt('pageUrl',$pageUrl);
        $this->_db->bindInt('autId',$_SESSION['auth']);
        $ligne = $this->_db->query($query);
        if($ligne==false){
            return false;
        }else{
            return true;
        }
    }
	
	public function getMenu(){
		$usr_sess=$this->isConnected();
		
		if($usr_sess){
		    $query='SELECT * FROM admPages as p
				INNER JOIN admPagesAutor as pg ON p.pageId= pg.pageId
				WHERE pageMenuorder is not null AND idGroup=:idGroup
				ORDER BY pageMenuorder';
		    $this->_db->bindInt('idGroup',$usr_sess['idGroup']);
			return $this->_db->query($query);
		}else{
			die('No access');
		}
	}
	
	public function setLanguage($changelan=''){
	
		/*
		if(!empty($changelan)){
			$this->_db->bindTxt('langCode',$changelan);
			$ligne = $this->_db->single('SELECT * FROM tblLangue WHERE langCode =:langCode');
		}
			 
		if(empty($changelan) | $ligne==false){
			$this->_db->bindTxt('langCode','fr');
			$ligne =$this->_db->single('SELECT * FROM tblLangue WHERE langCode=:langCode');
		}
		*/
		$_SESSION["langCode"] = $changelan;
		$this->language=$changelan;
		// Id of the user of this session
		
		$iduser=$_SESSION['id'];
		
		// Set the lang in the DB for this USER
		$this->_db->query('DELETE FROM tblLangueChoice WHERE employer='.$iduser);
		$this->_db->query("INSERT INTO tblLangueChoice VALUES($iduser,'".$this->language."')");
	}

    public function getLanguage(){
        return $this->language;
    }

	public function getText($label,$code=''){
		if(!$label) $label = '';
		$label=strtolower($label);
		$code=$label;
		// we use the array of traduction
		$traduction=$this->traduction;		
		// if lang is not FR, we search the first entry
		
		if (isset($_SESSION['langCode'])){			
			// Search for first entry with the text related to label.
			//echo "search :".$label;
			$key=array_search($label,$traduction);		
			// if lang is de...the next entry + 1 is the de ...	
			if ($this->language=="de"){
				$key_de=$key+1;
				$label=$traduction[$key_de];
			}	
		}
		if ($label=="code"){
			$label=$code;		
		}
		return $label;
	}

    public function getTextXL($label,$code=''){
        $tradsPage=str_replace(array($this->devdirectory,$this->directory),'',$_SERVER['PHP_SELF']);
        if(!empty($label)) {
            if(empty($code)){
                $this->_db->bindTxt('tradsTexte', $label);
                $this->_db->bindTxt('tradsCode', $code);
                $this->_db->bindTxt('langCode', $this->language);
                $ligne = $this->_db->single('SELECT src.tradsID as tradsID,tradtTexte
                    FROM admTradSourceXL as src
                    LEFT OUTER JOIN admTradTexteXL as txt ON txt.tradsId=src.tradsID AND langCode = :langCode
                    WHERE src.tradsTexte=:tradsTexte AND (src.tradsCode=:tradsCode OR src.tradsCode IS NULL)');
            }else{
                $this->_db->bindTxt('tradsCode', $code);
                $this->_db->bindTxt('langCode', $this->language);
                $ligne = $this->_db->single('SELECT src.tradsID as tradsID,tradtTexte
                    FROM admTradSourceXL as src
                    LEFT OUTER JOIN admTradTexteXL as txt ON txt.tradsId=src.tradsID AND langCode = :langCode
                    WHERE src.tradsCode=:tradsCode');
            }
            if ($ligne == false && $this->language == 'fr') {
                $this->_db->bindTxt('tradsTexte', $label);
                $this->_db->bindTxt('tradsCode', $code, true);
                $this->_db->bindTxt('tradsPage', $tradsPage);
                $this->_db->query('INSERT INTO admTradSourceXL (tradsCode,tradsTexte,tradsPage) VALUES (:tradsCode,:tradsTexte,:tradsPage);');
                $ligne['tradsId'] = $this->_db->lastInsertId();

                return $label . '+';
            } else {
                if ($this->language != 'fr') {
                    if (empty($ligne['tradtTexte'])) {
                        return $label.'*';
                    } else {
                        return $ligne['tradtTexte'];
                    }
                } else {
                    return $label;
                }
            }
        }
    }

    public function formatText($aTemplate,$text){
        return str_replace(
            array_keys($aTemplate),
            array_values($aTemplate),
            $text
        );
    }
	
	public function isStrongPassword($password){
		if(preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{6,16}$/m', $password) ){
			return true;
		}else{
			return false;
		}
	}
	
	public function get_mime_type($file = ''){

	    if (empty ($file)){
	        exit ('Paramètre invalide');
	    }

	    if( function_exists('finfo_open') ){
	        $finfo = finfo_open(FILEINFO_MIME_TYPE); // Retourne le type MIME à l'extension mimetype.
	        $return = finfo_file($finfo, $file);
	        finfo_close($finfo);
	    } elseif(file_exists('mime.ini')){
	        $return = $this->typeMime($file);
	    } else {
	        $return = mime_content_type ( $file );
	    }

	    return $return;

	}
	
	public function typeMime($filename)
	/* Function found on the web.
		retourne le type MIME à partir de l'extension de fichier contenu dans $filename
	 * Exemple : $filename = "fichier.pdf" => type renvoyé : "application/pdf" */
	{
		// On détecte d'abord le navigateur, ça nous servira plus tard.
		if(preg_match("@Opera(/| )([0-9].[0-9]{1,2})@", $_SERVER['HTTP_USER_AGENT'], $resultats))
		$navigateur="Opera";
		elseif(preg_match("@MSIE ([0-9].[0-9]{1,2})@", $_SERVER['HTTP_USER_AGENT'], $resultats))
		$navigateur="Internet Explorer";
		else $navigateur="Mozilla";

		// On récupère la liste des extensions de fichiers et leurs types MIME associés.
		$mime=parse_ini_file("mime.ini");
		$extension=substr($filename, strrpos($filename, ".")+1);

		/* On affecte le type MIME si l'on a trouvé l'extension, sinon le type par défaut (un flux d'octets).
		 Attention : Internet Explorer et Opera ne supportent pas le type MIME standard. */
		if(array_key_exists($extension, $mime)){
			$type=$mime[$extension];
		}
		else{
			$type=($navigateur!="Mozilla") ? 'application/octetstream' : 'application/octet-stream';
		}

		return $type;
	}
	
	public function simple_crypt( $string, $crypt = true ) {
	    // you may change these values to your own
	    $secret_key = 'MRP8M34GBEOW8t_key';
	    $secret_iv = 'MRPL12DfD9STYcT_iv';
	 
	    $output = false;
	    $encrypt_method = "AES-256-CBC";
	    $key = hash( 'sha256', $secret_key );
	    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
	 
	    if( $crypt ) {
	        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
	    }
	    else if( !$crypt ){
	        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
	    }
	 
	    return $output;
	}

}

?>