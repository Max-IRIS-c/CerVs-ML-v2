<?php 
    require_once(__DIR__.'/../../src/class/Db.class.php');
    require_once(__DIR__.'/locationObject.php');
    //include("../variables.php");

    /*
    *   this entity represent 'tblLocation in the db
    */
    class LocationReservation {
        public $id;
        public $infos;
        public $client;
        public $locationObject;
        public $adminResponsable;
        public $groupResponsable;
        public $startDate;
        public $endDate;
        
        public function __construct(Int $givenId){ 
            $this->id = $givenId; 
            $this->getInfos(); 
        } 
        private function getInfos(){ 
            try{ 
                $db = new Db(); 
                $req = "SELECT 
                        tblLocation.*, 
                        conId, conSociete, conPrenom, conNom, conAdresse, conAdresse2, conLocaliter, conNpa
                        FROM tblLocation 
                        INNER JOIN tblContact ON tblContact.conId = tblLocation.locConId
                        WHERE locId = $this->id"; 
                $data = $db->query($req); 
                if(!$data[0]) throw new Exception(); 
                $this->infos = $data[0];
                $this->client = $this->formatClient($data[0]); 
                $this->adminResponsable = $this->formatResponsable($data[0], 'admin');
                $this->groupResponsable = $this->formatResponsable($data[0], 'group');
                $idPavillon = intval($data[0]['locPavId']); 
                $this->locationObject = new LocationObject($idPavillon); 
                $this->startDate = $data[0]['locDateEnt'] ? dateToUser2($data[0]['locDateEnt']) : null; 
                $this->endDate = $data[0]['locDateDep'] ? dateToUser2($data[0]['locDateDep']) : null;   
            }catch(Exception $e){
                $this->client = '?';
            }
        }     
        /** CLIENT INFOS : 'name' + 'adress' + 'fct' */
        private function formatClient($locationData){
            try{
                return [
                    'societe' => $this->formatClientName($locationData),
                    'name' => $locationData['locRespNomPrenom'],
                    'adress' => $this->formatClientAdress($locationData)
                ];
            }catch(Exception $e){
                $this->client = [];
            }
        }
        private function formatResponsable($givenData, $type){
            try{
                return [
                    'nameAndFirstname' => $type === 'admin' ? $givenData['locRespNomPrenom'] : $givenData['locCoRespNomPrenom'],
                    'phone' => $type === 'admin' ? $givenData['locRespTel'] : $givenData['locCoRespTel'],
                    'mail' => $type === 'admin' ? $givenData['locRespMail'] : $givenData['locCoRespMail']
                ];
            }catch(Exception $e){
                $this->client = [];
            }
        }
        private function formatClientName($givenData){
            try{
                if(!$givenData['conSociete'] && !$givenData['conPrenom'] && !$givenData['conNom']) throw new Exception();
                if($givenData['conSociete']) return $givenData['conSociete'];
                else return $givenData['conPrenom'].' '.$givenData['conNom'];
            }catch(Exception $e){
                return '';
            }
        }
        private function formatClientAdress($givenData){
            try{
                if(!$givenData['conAdresse'] && !$givenData['conAdresse'] &&
                    !$givenData['conLocaliter'] && !$givenData['conNpa']) throw new Exception();
                $adress = $givenData['conAdresse'] ? $givenData['conAdresse'] : ( $givenData['conAdresse2'] ? $givenData['conAdresse2'] : '');
                return $adress.', '.$givenData['conNpa'].', '.$givenData['conLocaliter'];
            }catch(Exception $e){
                return '';
            }
        }
    }
    // présent dans variables.php, include à refaire proprment
function dateToUser2($dateSql){
    if (isset($dateSql)){
        $nbr = strlen($dateSql);
        if ($nbr != 10) $dateSql = '0' . $dateSql;
        setlocale (LC_TIME, 'fr_FR.utf8','fra');

        $jour = substr($dateSql, 8, 2);
        $Mois = substr($dateSql, 5, 2);
        $annee = substr($dateSql, 0, 4);
        $date = $jour . '.' . $Mois . '.' . $annee;
        return $date;
    }
}
?>