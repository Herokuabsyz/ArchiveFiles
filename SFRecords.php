<!DOCTYPE html>
<html>
<head>
	<title>SF Record Page</title>
	<link rel="stylesheet" type="test/css">
</head>
<style>
table{
  width: 100%;
  padding: 12px 16px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
  box-sizing: border-box;
  font-weight: bold;
  color: #000;
}
th {
  width: 100%;
  padding: 10px 14px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 18px;
  box-sizing: border-box;
  font-weight: bold;
  color: #000;
}
td{
  width: 100%;
  padding: 10px 14px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
  box-sizing: border-box;
  color: #000;
}
tr{
  width: 100%;
  background-color: #ffffff;
  color: #000000;
  padding: 10px 16px;
  margin: 8px 0;
  border: none;
  font-size: 14px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
}

.button {
  background-color: #ffffff;
  border: none;
  color: #800000;
  padding: 15px 22px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  font-weight: bold;
}
.button:hover {background-color: #99ff33}

.button:active {
  background-color: #99ff33;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
</style>
<body style="background:#66ff99; font-family: Arial;">
<h1 style="color: #808080"><center>Salesforce Archival</center></h1>
	<div id="frm" style="border: solid gray 1px; width: 40%; border-radius: 5px; margin: 50px auto; padding: 50px; background: #000000; opacity: 0.5; filter: alpha(opacity=50);">
	

	<?php
		define("USERNAME", "manishbattu@dev.com");
		define("PASSWORD", "manomani@2169");
		define("SECURITY_TOKEN", "oqwAHteZvrpluSMW2LsRiDps");

		require_once ('soapclient/SforcePartnerClient.php');
		global $mySforceConnection ;
		global $id;
		global $contdocId;
		global $query2;
		global $queryResult1;
		global $body;

		$mySforceConnection = new SforcePartnerClient();
		$mySforceConnection->createConnection("PartnerWSDL.xml");
		$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);

		$query = "SELECT Id, FirstName, LastName, Phone from Contact limit 5";
		$response = $mySforceConnection->query($query);

		foreach ($response->records as $record)
		{
			$id =$record->Id;
			/*echo '<tr>
			<td>'.$record->Id.'</td>&nbsp;&nbsp;&nbsp;
			<td>'.$record->fields->FirstName.'</td>&nbsp;&nbsp;&nbsp;
			<td>'.$record->fields->LastName.'</td>&nbsp;&nbsp;&nbsp;
			<td>'.$record->fields->Phone.'</td>&nbsp;&nbsp;&nbsp;
			</tr><br>';*/ ?>
	
	<form  action = "zippedRecords.php">
		<table>
			<tr>
				<th>Record ID</th>
				<th>Name</th>
				<th>Phone</th>
			</tr>
			<?php echo '<tr>
							<td>'.$record->Id.'</td>&nbsp;&nbsp;&nbsp;
							<td>'.$record->fields->LastName.'</td>&nbsp;&nbsp;&nbsp;
							<td>'.$record->fields->Phone.'</td>&nbsp;&nbsp;&nbsp;
						</tr> <br>';?>
			</table>
			<table>
				        <tr>
							<th>Title</th>
							<th>ID</th>
						</tr>
						<?php
						$query1 = "SELECT ContentDocumentId FROM ContentDocumentLink WHERE LinkedEntityId ='" .$id ."'";
						$queryResult = $mySforceConnection->query($query1);
						if (!empty($queryResult->records)) {
						foreach($queryResult->records as $contentDocumentId) {
						$contdocId = $contentDocumentId->fields->ContentDocumentId;
		
						$query2 = "SELECT Id,Title,VersionData,CreatedDate FROM ContentVersion WHERE ContentDocumentId ='" .$contdocId ."'";
						$queryResult1 = $mySforceConnection->query($query2);

						if (!empty($queryResult1->records)) 
						{ 
							// if condition to check for the records
							foreach($queryResult1->records as $attachment) 
							{
								// to print the attachment name
								echo $attachment->fields->Title.'&nbsp;&nbsp;&nbsp;'.$attachment->Id.'</br></br>';
								$body = $attachment->fields->VersionData;
								
								echo '<tr>
										<td>'.$attachment->fields->Title.'</td>&nbsp;&nbsp;&nbsp;
										<td>'.$attachment->Id.'</td>&nbsp;&nbsp;&nbsp;
									  </tr> <br>';
									
							    // to zip attachments
								$zip_name = $record->fields->LastName.'.zip';
								$path = 'C:/xampp/htdocs/heroku/login1'.$zip_name;
								$zip = new ZipArchive();
								if ($zip->open($path, ZipArchive::CREATE) === TRUE )
								{
									$zip->addFromString($record->Id,base64_decode($body));
									//echo '<br>Zip file created '.$record->fields->LastName;	
								}

								}
									$zip->close();
		    
								}
							}
						}
		}
				  ?>
		</table>
	    <p>
			<input type="button" class="button" value="Zip Now!" onClick="zipfile()">
		</p>
		
	</form>
	</div>
</body>
</html>

			
						