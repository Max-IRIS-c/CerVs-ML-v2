


<?php
include("../inc/config.php");

$_FR=$_POST['FR'];
$_DE=$_POST['DE'];

#$payload = @file_get_contents('php://input');
#file_put_contents("csv/payload.txt",$payload);

#$json=json_encode($_REQUEST);
#file_put_contents("csv/json.txt",$json);

//$_FR="activités";
//$_DE="ACtivatatenBOM";

//$_FR=utf8_encode($_FR);
//$_DE=utf8_encode($_DE);


// get the files DE

	$nameofthefileoflanguage="de.csv";
	$testPath = $langPath.$nameofthefileoflanguage;
	$loadFile=strtolower(file_get_contents($langPath.$nameofthefileoflanguage));
	$array_csv=str_getcsv($loadFile);

// Who was modified ?

		$key=$_POST['id'];
		
		// We multiply this KEY by 4 and we have the right Key
		
		$key_to_replace=$key*4-1;
		
	#	echo "<li>".$key."<br>";
	#file_put_contents("csv/key.txt",$key.",".$_FR);
				
// we replace the $key after

if ($key!=0)	
	{
		// We replace the string with the new string in the array.
			$array_csv[$key_to_replace]=$_DE;		
	}
	


// we are reproducing a file....de.csv now

$line=1;$baseline="";$filecreated="";

 foreach ($array_csv as $KEY=>$VALUE)
  {

	$baseline.=$VALUE.",";
	
	if ($line==4) {
	
	if ($baseline!="")
	{
	$filecreated.=$baseline;
	}
	#echo "<li>".$baseline;
	
	$baseline="";
	
	$line=1;
	
	}
	else
	{

	$line++;
	}
}	

// We replace the original file.

file_put_contents("../lang/de.csv",$filecreated);

// Reload the session with the new language.

unset ($_SESSION['traduction']);

?>