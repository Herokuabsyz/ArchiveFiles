<html>
<body>
<?php	

define("USERNAME", "manishbattu@dev.com");
define("PASSWORD", "manish@2169");
define("SECURITY_TOKEN", "ZDDkpDrdhhb7XjmgKXc8H3Nu");
require_once ('soapclient/SforcePartnerClient.php');
require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$mySforceConnection = new SforcePartnerClient();
$mySforceConnection->createConnection("PartnerWSDL.xml");
$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);

set_time_limit(0);
if($mySforceConnection !=NULL)
{
echo 'Connected successfully to '.USERNAME.'<br><br>';
}
else
{
echo 'Failed to Connect to'.USERNAME;
}
$query = "SELECT Id, FirstName, LastName, Phone,ContentDocumentIds__c from Contact where ArchiveStatus__c='Pending'";
$response = $mySforceConnection->query($query);
foreach ($response->records as $rec)
{
	echo $rec->fields->LastName."<br>";
}
$records = array();
$cvids = array();
$i = 0;
//$myAcctName = getenv('AccountName');
//$myAcctKey = getenv('AccountKey');
//$myEndpointSuffix = getenv('EndpointSuffix');
foreach ($response->records as $record)
{
echo $record->fields->LastName;
echo $i;
echo $record->fields->ContentDocumentIds__c."<br>";
$response = $mySforceConnection->retrieve('Id, Title,VersionData','ContentVersion', explode (",", $record->fields->ContentDocumentIds__c));
$zip_name = $record->fields->LastName.'.zip';
$zip = new ZipArchive();
if(!empty($response)){
if ($zip->open($zip_name, ZipArchive::CREATE) === TRUE )
{
	foreach($response as $attachment) {
		
		$zip->addFromString($attachment->fields->Title,base64_decode($attachment->fields->VersionData));
	}
}
if ($zip->open($zip_name, ZipArchive::CREATE) != TRUE )
{
	exit("cannot open <$zip_name>\n");
}
$zip->close();
}
}
 $response2 = $mySforceConnection->create($records);
?>


</body>
</html>
