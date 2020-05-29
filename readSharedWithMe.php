<?php

require_once 'vendor/autoload.php';

/*if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}*/

$client = new Google_Client();

if ($credentials_file = checkServiceAccountCredentialsFile()) {
  $client->setAuthConfig($credentials_file);
} 
elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
  $client->useApplicationDefaultCredentials();
} 
else {
  print 'Wrong Credentials';
  return;
}

$client->setApplicationName("living-room-acc");
$client->setScopes([Google_Service_Drive::DRIVE_FILE, Google_Service_Drive::DRIVE_APPDATA, Google_Service_Drive::DRIVE, Google_Service_Drive::DRIVE_METADATA]);

$service = new Google_Service_Drive($client);
if (!file_exists ('root_folder')) mkdir('root_folder');
chdir('root_folder');
downloadFilesInFolder($service, NULL);

/** 
* Check service account credentials  
*/
function checkServiceAccountCredentialsFile()
{
  // service account creds
  $application_creds = __DIR__ . '/serviceAccountCredentials.json';

  return file_exists($application_creds) ? $application_creds : false;
}

/**
 * Download a full jerarchy of Google Drive Folders shared with the service account.
 *
 * @param Google_Service_Drive $service Drive API service instance.
 * @param String $folderId ID of the folder to print files from.
 */
function downloadFilesInFolder($service, $folderId) {

    try {

        $optParams = array();

        if ($folderId == NULL) {
            $optParams['q'] = 'sharedWithMe';
        }
        else {
            $optParams['q'] = '"'.$folderId.'" in parents';
        }
        $results = $service->files->listFiles($optParams);

        foreach ($results->files as $file) {

            $fileId = $file->getId();
            $fileName = $file->getName();

            if ($file['mimeType'] == 'application/vnd.google-apps.folder') {
                if (!file_exists ($fileName)) mkdir($fileName);
                $ownerDir = getcwd();
                chdir($fileName);
                downloadFilesInFolder($service, $fileId);
                chdir($ownerDir);
            }
            else {
                $content = $service->files->get($fileId, array("alt" => "media"));
                $outHandle = fopen($fileName, "w+");
                while (!$content->getBody()->eof()) {
                    $bytes = $content->getBody()->read(1024);
                    fwrite($outHandle, $bytes);
                }
                fclose($outHandle);
            }
        }
      } 
      catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
      }
  }

?>