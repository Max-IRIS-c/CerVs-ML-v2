<?php
include("../inc/config.php");

$_FR=$_POST['FR'];
$_DE=$_POST['DE'];
$givenKey = $_POST['id'];

#$payload = @file_get_contents('php://input');
#file_put_contents("csv/payload.txt",$payload);

#$json=json_encode($_REQUEST);
#file_put_contents("csv/json.txt",$json);

//$_FR="activités";
//$_DE="ACtivatatenBOM";

//$_FR=utf8_encode($_FR);
//$_DE=utf8_encode($_DE);
try{
	if(!isset($_FR) || !isset($_DE) || !isset($givenKey) || !isset($langPath)) throw new Exception();
	$array_csv = loadCsvFileDE($langPath);
	$key_to_replace = calculCorrectIndexToReplace($givenKey);
	if($key_to_replace.count() == 0) throw new Exception();
	$array_csv[$key_to_replace["DE"]] = $_DE; // We replace the string with the new string in the array.
	//$array_csv[$key_to_replace["FR"]] = $_FR; // /!\ for change french text /!\
	$filecreated = createCsvFileWithNewValues($array_csv);
	file_put_contents("../lang/de.csv", $filecreated); // We replace the original file.
	unset ($_SESSION['traduction']); // Reload the session with the new language.
}catch(Exception $error){

}
function loadCsvFileDE($langPath){
	$nameofthefileoflanguage="de.csv";
	$dataTest = $langPath.$nameofthefileoflanguage;
	$loadFile=strtolower(file_get_contents($langPath.$nameofthefileoflanguage));
	$array_to_return = str_getcsv($loadFile);
	return $array_to_return;
}

// We multiply this KEY by 4 and we have the right Key
// because there is 4 columns
function calculCorrectIndexToReplace($key){
	if($key != 0){
		return [
			"DE" => $key*4-1,
			"FR" => $key*4-2
		];
	}  
	else throw new Exception();
}
	
function createCsvFileWithNewValues($new_array_csv){
	$line=1;
	$baseline="";
	$filecreated="";

	foreach ($new_array_csv as $KEY=>$VALUE){
		$baseline.= $VALUE.",";
		if ($line==4) {	
			if ($baseline!=""){ $filecreated.= $baseline; }
			#echo "<li>".$baseline;
			$baseline="";
			$line=1;
		}else{
			$line++;
		}
	}	
	return $filecreated;
}
?>
