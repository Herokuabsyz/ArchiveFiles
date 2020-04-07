<?php
$localhost = "localhost";   #localhost
$dbusername = "root";       #username of phpmyadmin
$dbpassword = "";           #password of phpmyadmin
$dbname = "fileuploader";       #database name

#Connection string
$conn = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);

$sql = "SELECT * FROM uploadfiles";
$records = mysql_query($sql);

?>

<html>
<head>
   <title> Files </title>
</head>
<body>
  <table width = "600" border ="1" cellpadding ="1" cellspacing ="1">
	<tr>
		<th>Files</th>
	</tr>
  
	<?php
		while($files = mysql_fetch_assoc($records))
		{
			echo "<tr>";
			echo "<td>".$files['file']."</td>";
			echo "</tr>";
		}
	?>
  </table>	
</body>
</html>