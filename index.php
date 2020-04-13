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
if($mySforceConnection !=NULL) {
	echo 'Connected successfully to '.USERNAME.'<br><br>';
} 
else {
	echo 'Failed to Connect to'.USERNAME;
}
$query = "SELECT Id, FirstName, LastName, Phone,ContentDocumentIds__c,ContentVersionIds__c from Contact where ArchiveStatus__c='Pending'";
$response = $mySforceConnection->query($query);
$records = array();
$updateRecords = array();
$cdIds = array();
$i = 0;
if(sizeOf($response->records)>0){
	foreach ($response->records as $record){
	$response = $mySforceConnection->retrieve('Id, Title,VersionData','ContentVersion', explode (",", $record->fields->ContentVersionIds__c));
	$zip_name = $record->fields->LastName.'.zip';
	$zip = new ZipArchive();
		if(!empty($response)){
			if ($zip->open($zip_name, ZipArchive::CREATE) === TRUE ){
				foreach($response as $attachment) {
					$zip->addFromString($attachment->fields->Title,base64_decode($attachment->fields->VersionData));
				}
			}
			if ($zip->open($zip_name, ZipArchive::CREATE) != TRUE ) {
				exit("cannot open <$zip_name>\n");
			}
			$zip->close();
		}
	//$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('AccountName').";AccountKey=".getenv('AccountKey').";EndpointSuffix=".getenv('EndpointSuffix');
	$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('AccountName').";AccountKey=".getenv('AccountKey');
	//$connectionString = "DefaultEndpointsProtocol=https;AccountName=archivefilesstorage;AccountKey=kc2KM1p0AxCnKTQ3QNfrYe4mTNzQAqhViNB7O/ObXWFKY2y8HJvFzS0xbVL+BfE3Xi6o+DIQptQJKwgHtlVT7A==";
	$blobClient = BlobRestProxy::createBlobService($connectionString);
	$fileToUpload = $zip_name;
    // Create container options object.
    $createContainerOptions = new CreateContainerOptions();

    $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

    // Set container metadata.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");
	
    $containerName = "blockblobs".generateRandomString();
	
    try {
        // Create container.
        $blobClient->createContainer($containerName, $createContainerOptions);

        echo "Uploading BlockBlob: ".PHP_EOL;
        echo $fileToUpload;
        echo "<br />";
        
        $content = fopen($fileToUpload, "r");
        $blobClient->createBlockBlob($containerName,$fileToUpload,$content);
		$listBlobsOptions = new ListBlobsOptions();
        $listBlobsOptions->setPrefix("");
        echo "These are the blobs present in the container: ".$containerName."<br>";
		$url ='';
		do{
			$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
			foreach ($result->getBlobs() as $blob) {
                echo $blob->getName().": ".$blob->getUrl()."<br />";
				$url = $blob->getUrl();
            }
			$listBlobsOptions->setContinuationToken($result->getContinuationToken());
        } while($result->getContinuationToken());
        echo "<br />";
		$records[$i] = new SObject();
		$records[$i]->fields = array('Name' => $record->fields->LastName.'-->'.$containerName,'Zip_URL__c' =>$url,'Contact__c'=>$record->Id);
		$records[$i]->type = 'Archival_Logs__c';
		$updateRecords[$i] = new SObject();
		$updateRecords[$i]->fields = array('Id' => $record->Id,'ArchiveStatus__c' =>'Success' );
		$updateRecords[$i]->type = 'Contact';
		
			if(sizeOf($cdIds)== 0){
				$cdIds = explode (",", $record->fields->ContentDocumentIds__c);
			}
			else{
				$temp = explode (",", $record->fields->ContentDocumentIds__c);
					foreach($temp as $value){
						array_push($cdIds, $value);	
					}
			}
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
		$updateRecords[$i] = new SObject();
		$updateRecords[$i]->fields = array('Id' => $record->Id,'ArchiveStatus__c' =>'Fail' );
		$updateRecords[$i]->type = 'Contact';
    }
    catch(InvalidArgumentTypeException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
		$updateRecords[$i] = new SObject();
		$updateRecords[$i]->fields = array('Id' => $record->Id,'ArchiveStatus__c' =>'Fail' );
		$updateRecords[$i]->type = 'Contact';
    }
	$i=$i+1;
	}
	$response2 = $mySforceConnection->create($records);
	$response1 = $mySforceConnection->update($updateRecords);
	$response4 = $mySforceConnection->delete($cdIds);
 }
else {
	echo 'No records to Archive';
}

?>
</body>
</html>
