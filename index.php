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
	echo 'after zip';
//$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('AccountName').";AccountKey=".getenv('AccountKey').";EndpointSuffix=".getenv('EndpointSuffix');
$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('AccountName').";AccountKey=".getenv('AccountKey');
//$connectionString = "DefaultEndpointsProtocol=https;AccountName=azureabsyz;AccountKey=1nqwRoip8tEOkLZSk3KoSj2NoazUXM2YrQJstNQE6w7bRQJkiVt1X5MsZzWyAuzqsUziC5vuN0eWWDEd4Mj5aw==;EndpointSuffix=core.windows.net";
//DefaultEndpointsProtocol=https;AccountName=archivefilesstorage;AccountKey=kc2KM1p0AxCnKTQ3QNfrYe4mTNzQAqhViNB7O/ObXWFKY2y8HJvFzS0xbVL+BfE3Xi6o+DIQptQJKwgHtlVT7A==;EndpointSuffix=core.windows.net
// Create blob client.
	echo 'afer connect';
	# Setup a specific instance of an Azure::Storage::Client
    $connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('account_name').";AccountKey=".getenv('account_key');
    
    // Create blob client.
    $blobClient = BlobRestProxy::createBlobService($connectionString);
    
    # Create the BlobService that represents the Blob service for the storage account
    $createContainerOptions = new CreateContainerOptions();
    
    $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
    
    // Set container metadata.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");

    $containerName = "blockblobs".generateRandomString();

    try    {
        // Create container.
	$blobClient->createContainer($containerName, $createContainerOptions);
	
	$myfile = fopen("HelloWorld.txt", "w") or die("Unable to open file!");
    fclose($myfile);

    # Upload file as a block blob
    echo "Uploading BlockBlob: ".PHP_EOL;
    echo $fileToUpload;
    echo "<br />";
    
    $content = fopen($fileToUpload, "r");

    //Upload blob
    $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
	}
		
/*$blobClient = BlobRestProxy::createBlobService($connectionString);
echo 'blobclient';
$fileToUpload = $zip_name;
echo 'filetoupload';
    // Create container options object.
    $createContainerOptions = new CreateContainerOptions();

    $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

    // Set container metadata.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");
echo 'container';
      $containerName = "blockblobs".generateRandomString();
	 echo $containerName;
	//$containerName = $zip_name;
    try {
        // Create container.
        $blobClient->createContainer($containerName, $createContainerOptions);

        echo "Uploading BlockBlob: ".PHP_EOL;
        echo $fileToUpload;
        echo "<br />";
        
        $content = fopen($fileToUpload, "r");
        $blobClient->createBlockBlob($containerName,$fileToUpload,$content);
		$listBlobsOptions = new ListBlobsOptions();
        $listBlobsOptions->setPrefix("HelloWorld");
        echo "These are the blobs present in the container: ".$containerName."<br>";
        
    }*/
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
    catch(InvalidArgumentTypeException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
	$i=$i+1;
}
$response2 = $mySforceConnection->create($records);
 
?>


</body>
</html>
