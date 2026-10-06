<?php
    require_once(__DIR__.'/../../src/class/Db.class.php');
    require(__DIR__.'/locationReservation.php');

    //include('./locationReservation.php');
    /**
     **      this entity is represented in 'tblLogement' in the db  
     **      logType : 1) Logement 2) Véhicule 3) Autres  
     **/
    class LocationObject {
        public $id;
        public $infos;
        public $name;
        public $color;
        public $fontColor;
        public $type;
        public $personMin;
        public $personMax;
        public $immatriculation;
        public $prices;
        public $propriety;
        public function __construct(int $givenId) {
            $this->id = $givenId;
            $this->getInfos();
            $this->name = $this->infos[0]['logNom'] ?? '';
            $this->color = $this->infos[0]['logCouleur'] ?? '';
            $this->fontColor = $this->infos[0]['logFontColor'];
            $this->type = intval($this->infos[0]['logtype']);
            $this->personMin = intval($this->infos[0]['logPersonneMin']);
            $this->personMax = intval($this->infos[0]['logPersonneMax']);
            $this->prices = $this->formatPrices($this->infos);
            $this->propriety = $this->formatPropriety($this->infos[0]['propriety']);
            $this->immatriculation = $this->infos[0]['immatriculation'] ?? '';
        }
        public function getInfos(){
            try{
                $db = new Db();
                $safeId = intval($this->id);
                $req = "SELECT * FROM tblLogement WHERE logId = $safeId";
                $data = $db->query($req);
                if(is_array($data) && count($data) > 0) $this->infos = $data;
                else throw new Error();
            }catch(Exception $e){
                $this->infos = [];
            }
        }
        // return tableau de LocationObject correspondant au query
        static public function executeQuery($query){
            try{
                $db = new Db();
                $data = $db->query($query);
                return self::formatLocationObjects($data);
            }catch(Exception $e){
                return [];
            }
        }
        
        /******************************************************************/
        /********************* data formating *****************************/
        /******************************************************************/
        static public function formatLocationObjects($objectsToFormat){
            try{       
                $allObjects = array_map(function($objects){
                    $idObject = intval($objects['logId']);
                    if($idObject > 0) return new LocationObject($idObject);
                },$objectsToFormat);
                return $allObjects;
            }catch(Exception $e){
                return [];
            }
        }
        private function formatPropriety($idOwner){
            return intval($idOwner) == 0 ? 'Cerebral' : 'Parenthèse';
        }
        /** format array with all labels [0][[label => logLibeller1], [priceUnity => logUniterPrix1], [price => logPrix1]]  */
        private function formatPrices($allObjectData){
            try{
                $obj = $allObjectData[0] ?? []; 
                return empty($obj) ? 0 : [
                    [
                        'idLabel' => $obj['logLibeller1'] ?? 0,
                        'label' => $this->getLabelName($obj['logLibeller1']) ?? 0,
                        'idUnity' => $obj['logUniterPrix1'] ?? 0,
                        'priceUnity' => $this->getPriceUnityName($obj['logUniterPrix1']) ?? 0,
                        'price' => $obj['logPrix1'] ?? 0
                    ],[
                        'idLabel' => $obj['logLibeller2'] ?? 0,
                        'label' => $this->getLabelName($obj['logLibeller2']) ?? 0,
                        'idUnity' => $obj['logUniterPrix2'] ?? 0,
                        'priceUnity' => $this->getPriceUnityName($obj['logUniterPrix2']) ?? 0,
                        'price' => $obj['logPrix2'] ?? 0
                    ],[
                        'idLabel' => $obj['logLibeller3'] ?? 0,
                        'label' => $this->getLabelName($obj['logLibeller3']) ?? 0,
                        'idUnity' => $obj['logUniterPrix3'] ?? 0,
                        'priceUnity' => $this->getPriceUnityName($obj['logUniterPrix3']) ?? 0,
                        'price' => $obj['logPrix3'] ?? 0
                    ],
                ];
            }catch(Exception $e){
                return [];
            }
        }
        /**** 
         * check if reservation (tblLocation) exist for the object and givenDate :
         * handle Oasis & Source exception 
         * if reservation exists return 'client' 
         * else return ''
         ****/
        public function isReservedForDate(String $givenDate){
            try{
                if($this->id === 2 || $this->id === 3){ /** check oasis & source */
                    $check = $this->handleOasisAndSourceExeption($givenDate);
                    if($check['status'] !== 'available') return $check;
                } 
                $db = new Db();
                $req = "SELECT locId FROM tblLocation
                        WHERE ('$givenDate' BETWEEN locDateEnt AND locDateDep) AND locPavId = $this->id";
                $data = $db->query($req);
                if(!$data || !$data[0]) return ['status' => ''];
                $idLocation = $data[0]['locId'];
                $location = new LocationReservation($idLocation);
                if (is_array($location->client) && isset($location->client['societe'])) {
                    return ['status' => $location->client['societe'], 'id' => $location->id];
                } else {
                    // Fallback si client est déjà une chaîne ou invalide
                    $clientName = is_string($location->client) ? $location->client : '';
                    return ['status' => $clientName, 'id' => $location->id];
                }
                // return ['status' => $location->client['societe'], 'id' => $location->id];
            }catch(Exception $e){
                $msg = $e->getMessage();
                return ['status' => ''];
            } 
        } 
        /*  
        **  if object UAT(source + oasis) is reserved, return 'indisponible' 
        **  if yes return 'non disponible' 
        */ 
        private function handleOasisAndSourceExeption($givenDate){
            try{
                $uat = new LocationObject(1);
                $isUatReserved = $uat->isReservedForDate($givenDate);
                if($isUatReserved['status'] !== '') throw new Exception('Non disponible');
                return ['status' => 'available'];
            }catch(Exception $e){
                $msg = $e->getMessage();
                return ['status' => $msg];
            }
        }
        /******************************************************************/
        /********************* SQL QUERIES ********************************/
        /******************************************************************/
        /****** return id of all location objects  ******/
        static function getAllCerebral(){
            try{
                $query = "SELECT logId FROM tblLogement WHERE actif = 1 AND propriety = 0 ORDER BY logtype";
                return self::executeQuery($query);
            }catch(Exception $e){
                return [];
            }
        }
        static function getAllLogementCerebral(){
            try{
                $query = "SELECT logId FROM tblLogement WHERE actif = 1 AND propriety = 0 AND logtype = 1";
                return self::executeQuery($query);
            }catch(Exception $e){
                return [];
            }
        }
        static function getAllBusCerebral(){
            try{
                $query = "SELECT logId FROM tblLogement WHERE actif = 1 AND propriety = 0 AND logtype = 2";
                return self::executeQuery($query);
            }catch(Exception $e){
                return [];
            }
        }
        static function getAllParenthese(){
            try{
                $query = "SELECT logId FROM tblLogement WHERE actif = 1 AND propriety = 1 ORDER BY logtype";
                return self::executeQuery($query);
            }catch(Exception $e){
                return [];
            }
        }
        /** tblTypePresta */
        private function getLabelName($idLabel){
            try{
                if(intval($idLabel) == 0) throw new Exception();
                $db = new Db();
                $req = $db->query("SELECT TprestaNom FROM tblTypePresta WHERE TprestaId = $idLabel");
                if(!$req) throw new Exception();
                return $req[0]['TprestaNom'];
            }catch(Exception $e){
                return '';
            }
        }
        /** tblUnite */
        private function getPriceUnityName($idPriceUnity){
            try{
                if(intval($idPriceUnity) === 0) throw new Exception();
                $db = new Db();
                $req = $db->query("SELECT uniNom FROM tblUnite WHERE uniId = $idPriceUnity");
                if(!$req) throw new Exception();
                return $req[0]['uniNom'];
            }catch(Exception $e){
                return '';
            }
        }
        
        /** tblTypePreta */
        static public function getListOfTypePresta(){
            try{
                $db = new Db();
                $req = $db->query("SELECT TprestaId as idTypePresta, TprestaNom as nameTypePresta FROM tblTypePresta");
                if(count($req) == 0) throw new Exception();
                return $req;
            }catch(Exception $e){
                return [];
            }
        }
        /** tblUnite */
            static public function getListOfPriceUnity(){
            try{
                $db = new Db();
                $req = $db->query("SELECT uniId, uniNom FROM tblUnite");
                if(count($req) == 0) throw new Exception();
                return $req;
            }catch(Exception $e){
                return [];
            }
        }
        /******************************************************************/
        /******************************************************************/
        /******************************************************************/
        /*
        ** return * of all objects specified manually in 'filterObjects()'
        ** Ids specified as 'Parentèse':
        ** (8) : bus Peugot
        ** (9) : bus mercedes 
        
        static public function getAllParenthese(){
            return self::filterObjects('parenthese');
        }
        static public function getAllNotParenthese(){
            return self::filterObjects('not-parenthese');
        }
        static private function filterObjects(String $condition){
            try{ 
                $allObjects = self::getListObjects();
                if(count($allObjects) === 0) throw new Exception();
                /** séléction des objects spécifié comme 'Parenthèse'  
                if($condition === 'parenthese'){
                    $ParentheseObjects = array_filter($allObjects, function ($object){;
                        return  $object->id === 8 || 
                                $object->id === 9 || 
                                $object->id === 17 ||
                                $object->id === 18 ||
                                $object->id === 19 ||
                                $object->id === 20;
                    });
                    return $ParentheseObjects;
                }
                if($condition === 'not-parenthese'){
                    $ParentheseObjects = array_filter($allObjects, function ($object){;
                        return  $object->id !== 8 && 
                                $object->id !== 9 &&
                                $object->id !== 17 && 
                                $object->id !== 18 &&
                                $object->id !== 19 && 
                                $object->id !== 20;

                    });
                    return $ParentheseObjects;  
                }
                /*
                ** cas classique des 'logements', 'vehicules' et 'autres'
                ** filtre selon le $this->type 
                ** exclusions des objets spécifiés comme parenthèse
                                       
                $refType = $condition === 'logement' ? 1 : ($condition === 'vehicule' ? 2 : 3);
                $objects = array_filter($allObjects, function ($object) use($refType){
                    $typeExclusion = ($object->type === $refType);
                    $parentheseExclusion = ($object->id !== 8 && 
                                            $object->id !== 9 &&
                                            $object->id !== 17 && 
                                            $object->id !== 18 &&
                                            $object->id !== 19 && 
                                            $object->id !== 20);
                    return $typeExclusion && $parentheseExclusion;
                });
                return $objects;
            }catch(Exception $e){
                return [];
            }
        }
        */
        /*
		** Impression du planning pour chaque date de l'intervalle
		** parcours de chaque date de l'intervalle entre start et end 
        */
        static public function displayPlanningTable($givenLocationsObjects, $start, $end){
            echo '<table style="background-color: white; width: 100%;" class="tbl-layout">';
            self::displayHeader($givenLocationsObjects);
            $timeInterval = new DateInterval("P1D");
            for ($date = clone $start; $date <= $end; $date->add($timeInterval)){
                /*
                **  insertion des sous-titre "n° de semaine" ou "Nom du mois"                 
                */
                $dateInfos = self::handleDate($date);
                $nbrOfCol = count($givenLocationsObjects) + 2;
                $isDateNewMonth = $dateInfos['firstDayOfMonth'] ?? false;
                $isDateNewWeek = $dateInfos['firstDayOfWeek'] ?? false;
                if($isDateNewMonth){
                    echo '<tr>';
                    echo '<th class="month" colspan="'.$nbrOfCol.'"><strong>'.$dateInfos['monthName'].'</strong></th>'; //  colspan="'.$nbrOfCol.'"
                    echo '</tr>';
                }
                if($isDateNewWeek){
                    echo '<tr>';
                    echo '<th colspan="'.$nbrOfCol.'" class="week"><strong>'.$dateInfos['weekNbr'].'</strong></th>'; //  colspan="'.$nbrOfCol.'"
                    echo '</tr>';
                }
                if($isDateNewMonth || $isDateNewWeek) self::displayHeader($givenLocationsObjects);
                /*
                ** impression de chaque ligne par date 
                */
                echo '<tr>'; 
                    echo '<td>'.$date->format('d.m.Y').'</td>';
                    $lastObjectType = $givenLocationsObjects[0]->type;
                    foreach($givenLocationsObjects as $object){
                        $reservation = $object->isReservedForDate($date->format('Y-m-d'));
                        $status = $reservation['status'];
                        if($object->type !== $lastObjectType) echo '<th style="width: 10px;" class="separator"></th>';
                        switch($status){
                            case '': 
                                echo '<td><strong>'.$status.'</strong></td>';;
                                break;
                            case 'Non disponible':
                                echo '<td style="background-color: red;"><strong>'.$status.'</strong></td>'; 
                                break;
                            default: 
                                echo '<td style="background-color: '.$object->color.';"><strong>';
                                echo '<a style="color: '.$object->fontColor.' ;"href="./DetLocation.php?Id='.$reservation['id'].'&start='.$start->format('Y-m-d').'&end='.$end->format('Y-m-d').'">';
                                echo $status.'</strong></td>';
                                break;
                        }
                        $lastObjectType = $object->type;
                    }
                echo '</tr>'; 
            }
            echo '</table>';
        }
        // affiche chaque ligne qui contient le nom de l'objet eb location
        // determine la couleur de fond de la case selon le type (vehicuzle, logement etc.)
        static private function displayHeader($locationObjects){ 
            $mrp = new Mrp();
			echo '<tr>';
			echo '<th>'.$mrp->getText("Date") .'</th>';
            $lastObjectType = $locationObjects[0]->type; 
			foreach($locationObjects as $object){            
                $headerBckColor = $object->type ===  1 ? '#4caf50' : '#008cba';
                $headerColor = $object->type ===  1 ? 'white' : 'white';
                if($object->type !== $lastObjectType) echo '<th style="width: 10px;" class="separator"></th>';
				echo '<th style="color: '.$headerColor.'; background-color:'.$headerBckColor.'">'.$object->name.'</th>';
                $lastObjectType = $object->type;
			}
			echo '</tr>';
	 	} 
        /**
		 * 	take date format DateTime
		 * 	1) check if date is first day of month, return the complete french name of month
 		 * 	2) check if date is monday, if yes return the number of the week
         *  return  ['firstDayOfMonth' => bool, 'monthName' => value]
         *          ['firstDayOfWeek' => bool, 'weekNbr' => value]
         *  or []
		 */
        static public function handleDate($givenDate){
            if (!($givenDate instanceof DateTime)) return [];
            $res = [];
            if ($givenDate->format('d') === '01') 
                $res = ['firstDayOfMonth' => true, 'monthName' => self::$moisFr[(int)$givenDate->format('n')]];
            
            if ($givenDate->format('N') === '1') {
                $mrp = new Mrp();
                $res['firstDayOfWeek'] = true;
                $res['weekNbr'] = $mrp->getText("semaine") . " " . $givenDate->format('W');
            }
            return $res;
        }
        private static $moisFr = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
        /*
		static public function handleDate($givenDate){
			try{
                if (!($givenDate instanceof DateTime)) throw new Exception();
                $monthCheck = (int)$givenDate->format('d'); // 1) Vérifie si c'est le 1er du mois
                $dateStatus = [];
                if ($monthCheck === 1) {
                    setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');
                    $monthName = strftime('%B', $givenDate->getTimestamp()); // Nom complet du mois en français
                    $monthData= ['firstDayOfMonth' => true, 'monthName' => $monthName];
                    $dateStatus = array_merge($dateStatus, $monthData);
                }
                $weekCheck = (int)$givenDate->format('N'); // 2) Vérifie si c'est un lundi
                if ($weekCheck === 1) { // 1 = Lundi
                    $mrp = new Mrp();
                    $weekNbr = $mrp->getText("semaine") . " ". $givenDate->format('W');
                    $weekData = ['firstDayOfWeek' => true, 'weekNbr' => $weekNbr];           
                    $dateStatus = array_merge($dateStatus, $weekData);
                }
                return $dateStatus;
			}catch(Exception $e){
				return [];
			}
		}*/
    }
?>