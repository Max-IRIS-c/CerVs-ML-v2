<?php 
    class Contract{
        /** 
         * return false if there is no file recorded
         * else return the file (the first pdf of the folder)
         */
        public static function signedContrat(String $type, Int $idContrat){
            try{
                if(!$idContrat || !$type) throw new Exception();
                $folder = Contract::determineFolder($type, intval($idContrat));
                if(!is_dir($folder)) throw new Exception();
                else{
                    $allPDF = glob($folder . '/*.pdf');
                    $filename = basename($allPDF[0]);
                    $url = $idContrat.'/'.$filename;
                    if (!empty($allPDF)) return [ 'exist' => true, 'url' => $url]; 
                }
                throw new Exception();
            }catch(Exception $e){
                return [ 'exist' => false ];
            }
        }
        /** 
         * record in the correct folder int|acc contrat.php / 
         * |=> url : idContrat/givenFileName.pdf
         * return boolean that say if record worked or no
         */
        public static function recordNewPDFfile(String $type, Int $idContrat, $file){
            try{	
                if(!$idContrat || !$type) throw new Exception();	
                $folder = Contract::determineFolder($type, intval($idContrat));	
                if(!$folder) throw new Exception();			
                if(!is_dir($folder)) mkdir($folder, 0777, true); /** folder creation */
                else { /** delete all files in the folder before new record */
                    $allFiles = glob($folder . '/*');
                    if(count($allFiles) > 0) foreach($allFiles as $file) unlink($file);
                }
                /** file existance */	
                if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) throw new Exception(); 
                $targetFolder = self::formatURL($folder, $file['name']);
                $creation = move_uploaded_file($file['tmp_name'], $targetFolder);
                return $creation;
            }catch(Exception $e){
                return null;
            }
        }
        public static function deletePDF(String $type, Int $idContrat){
            try{
                if(!$idContrat || !$type) throw new Exception();	
                $folder = Contract::determineFolder($type, intval($idContrat));         	
                //if(!$folder || !is_dir($folder)) throw new Exception();	
                $delete = self::deleteDirectory($folder); //rmdir($folder);
                if(!$delete) throw new Exception();
                return true;
            }catch(Exception $e){
                return false;
            }
        }
        // https://stackoverflow.com/questions/1653771/how-do-i-remove-a-directory-that-is-not-empty
        private function deleteDirectory($dir) {
            if (!file_exists($dir)) {
                return true;
            }
            if (!is_dir($dir)) {
                return unlink($dir);
            }
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') {
                    continue;
                }
                if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                    return false;
                }
            }
            return rmdir($dir);
        }
        /** format filename with good extension then return final path */
        private function formatURL($folder, $giventext){
            $extension = strtolower(pathinfo($giventext, PATHINFO_EXTENSION));
            $basename = pathinfo($giventext, PATHINFO_FILENAME);
            $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $basename);
            $safeName = $safeBase . '.' . $extension;
            return $targetFolder = $folder . '/' . $safeName;
        }
        static public function determineFolder(String $type, Int $idContrat){
            if($type === 'intervenant' ){
                $basePath = realpath(__DIR__ . '/../../Adresse');
                return $basePath.'/intervenant_contractsFiles/'.$idContrat;
            }
            else if($type === 'accompagnant'){
                $basePath = realpath(__DIR__ . '/../../Adresse');
                return $basePath.'/accompagnant_contractsFiles/'.$idContrat;
            }
            else if($type === 'liberty'){ 
                $basePath = realpath(__DIR__ . '/../../Liberty');   
                return $basePath.'/location_contracts/'.$idContrat;
            }
            else return null;
        }
    }

    /*
     * 
	 * return false if there is no file recorded
	 * else return the file (the first pdf of the folder)
	 *
	function signedContrat($idContrat){
		try{
			$folder = __DIR__.'/intervenant_contractsFiles/'.$idContrat; 
			if(!is_dir($folder)) throw new Exception();
			else{
				$allPDF = glob($folder . '/*.pdf');
				$filename = basename($allPDF[0]);
				$url = $idContrat.'/'.$filename;
				if (!empty($allPDF)) return [ 'exist' => true, 'url' => $url]; 
			}
			throw new Exception();
		}catch(Exception $e){
			return [ 'exist' => false ];
		}
	}
	function recordNewPDFfile($idContrat, $file){
		try{	
			if(!$idContrat) throw new Exception();		
			$folder = __DIR__.'/intervenant_contractsFiles/'.$idContrat; 						
			if(!is_dir($folder)) mkdir($folder, 0777, true); /** folder creation *
			else { /** delete all files in the folder before new record *
				$allFiles = glob($folder . '/*');
				if(count($allFiles) > 0) foreach($allFiles as $file) unlink($file);
			}
			/** file existance *
			if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) throw new Exception(); 
			$targetFolder = formatURL($folder, $file['name']);
			$creation = move_uploaded_file($file['tmp_name'], $targetFolder);
			return $creation;
		}catch(Exception $e){
			return null;
		}
	}
	/** format filename with good extension then return final path *
	function formatURL($folder, $giventext){
		$extension = strtolower(pathinfo($giventext, PATHINFO_EXTENSION));
        $basename = pathinfo($giventext, PATHINFO_FILENAME);
        $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $basename);
        $safeName = $safeBase . '.' . $extension;
        return $targetFolder = $folder . '/' . $safeName;
	} */
?>