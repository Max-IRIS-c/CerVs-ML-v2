<?php
if (isset($_GET['conId']))
	{	
		$adding="&conId=".$_GET['conId'];	
	}
	else
	{
	
		$adding="";
	}
if (isset($_GET['Id']))
{
		$adding.="&Id=".$_GET['Id'];
}
?>
<div align=right>
	<a href="?lang=fr<?php echo $adding ?>">
		<img src="../assets/img/fr.svg" height=15>
	</a></a> 
	<a href="?lang=de<?php echo $adding ?>">
		<img src="../assets/img/de.svg" height=15>
	</a>
</div>
