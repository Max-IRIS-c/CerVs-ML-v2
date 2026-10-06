<?php
    $submenu='admin';
    include('../src/header.inc.php');
    include_once "../src/class/Db.class.php";
    include_once "../src/class/Mrp.class.php";
    include_once "../inc/config.php";
    $mrp = new Mrp();
?>

<?php 		
		$nameofthefileoflanguage="de.csv";
		$loadFile=strtolower(file_get_contents($langPath.$nameofthefileoflanguage));
		$array_csv=str_getcsv($loadFile);


?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://www.jqueryscript.net/demo/Creating-A-Live-Editable-Table-with-jQuery-Tabledit/jquery.tabledit.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css">
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<br>
<table class="table table-striped table-bordered" id="example2">
  <caption></caption>  <thead>
    <tr>
      <th>Id</th>
      <th>FR</th>
      <th>DE</th>
    </tr>
  </thead>
  <tbody>
  
  <?php 
  $col=1; $i=0;
  foreach ($array_csv as $KEY=>$VALUE)
  {

	$i++;
	if ($i==1) { echo "<tr>";}
	if ($i==2) { echo "<td>".$col."</td>";}
	if (($i==3) || ($i==4)){ echo "<td>".$VALUE."</td>";}
	if ($i==4) { echo "</tr>";$i=0;$col++;}
	
	if ($col>1028) {break;} // nombre exact de ligne dans le fichier lang/de.csv
}	
	
    ?>
    
  </tbody>
</table>

<script>

$('#example2').DataTable(
{
	"dom" : "Bfrtip",
      "lengthChange": true,
      "iDisplayLength": 1000,
       "paging":   true,
	"select": true,
	"language" :
			{
				url: '../lang/<?php echo strtolower($_SESSION['langCode']) ?>.json'
			}

});

$('#example2').Tabledit({
      url: 'modif_user_from_table.php',
      columns: {
          identifier: [0, 'id'],
          editable: [
              [1, 'FR'],
              [2, 'DE']
          ]
      },
      editButton: true,
      removeButton: false,
      hideIdentifier: false,
      columns: {
        identifier: [0, 'id'],
        editable: [[2, 'DE']]
      }
});
</script>