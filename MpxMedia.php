<?php
use Exception;

class MpxMedia extends Mpx{

	protected $_guid = '';
	protected $_uploadCookie = '';
	static $_feedUrl = '';
	static $_publishProfileId = '';

	/**
    * @param string  $title
    * @param string  $description
    * @return object media object info
    * @link http://help.theplatform.com/display/vms2/Creating+Media+objects
    */
	public function createMedia($metadata) {
		// Media data endpoint
		$url = 'http://data.media.theplatform.com/media/data/Media';

		// Media url parameters
		$urlParams = array(
			"schema" => "1.2",
			"form" => "cjson",
			"token" => parent::$_token,
			"account" => parent::$_account
		);

		// Media specific data
		$mediaData = array(
			'$xmlns' => array(
		        "media" => "http://search.yahoo.com/mrss/",
		        "pl" => "http://xml.theplatform.com/data/object",
				"pl1" => parent::$_account,
		        "plmedia" => "http://xml.theplatform.com/media/data/Media"
			),
			'approved' => true
		);
		$mediaData = array_merge((array) $mediaData, (array) $metadata);
		$mediaData = $this->_modifyMetadata($mediaData);
		
		$response = $this->_callAPI('POST', $url, $urlParams, json_encode($mediaData));

		return $this->_responseCode($response);
	}

	/**
    * @param string  $mediaId
    * @param array  $content
    * @return object update response with empty payload
    * @link http://help.theplatform.com/display/vms2/Updating+Media+objects
    */
	public function updateMedia($mediaId, array $content = array()) {
		// Media data endpoint
		$url = 'http://data.media.theplatform.com/media/data/Media';

		// Media url parameters
		$urlParams = array(
			"schema" => "1.2",
			"form" => "cjson",
			"token" => parent::$_token
		);

		// Media specific data
		$mediaData = array(
			'$xmlns' => array(
		        "media" => "http://search.yahoo.com/mrss/",
		        "pl" => "http://xml.theplatform.com/data/object",
				"pl1" => parent::$_account,
		        "plmedia" => "http://xml.theplatform.com/media/data/Media"
			),
			'id' => $mediaId
		);

		$mediaData = array_merge((array) $mediaData, (array) $content);
		$mediaData = $this->_modifyMetadata($mediaData);

		$response = $this->_callAPI('POST', $url, $urlParams, json_encode($mediaData));

		return $this->_responseCode($response);
	}

	/**
    * @param string $mediaId
    * @param string $sourceUrl
    * @param array $options  mediaFileInfo types, ('assetTypes' => array('Mezzanine'))
    * @return object linkNewFileResponse with fileId
    * @link http://help.theplatform.com/display/vms2/linkNewFile+method
    */
	public function linkNewFile($mediaId, $sourceUrl, array $options = array()) {
		// File Management endpoint
		$url = 'http://fms.theplatform.com/web/FileManagement';

		// Media url parameters
		$urlParams = array(
			"schema" => "1.5",
			"form" => "json",
			"token" => parent::$_token,
			"account" => parent::$_account
		);

		$mediaData = array(
			"linkNewFile" => array(
 				"mediaId" => $mediaId,
				"sourceUrl" => $sourceUrl,
				"mediaFileInfo" => $options
					// "assetTypeIds" => array("http://data.media.theplatform.com/media/data/AssetType/_________") // Mezzanine
					// "assetTypes" => $assetTypes // Mezzanine
			)
		);
		$response = $this->_callAPI('POST', $url, $urlParams, json_encode($mediaData));
		return $this->_responseCode($response);
	}

	/**
    * @param string $mediaId
    * @param string $sourceUrl
    * @param array $options  mediaFileSettings, array('serverId', 'requiredPath', 'mediaFileInfo')
    * @param array $transferInfo  transferInfo credentials, array('username' => '', 'password' => '')
    * @return object copyNewFile with fileId and taskId
    * @link http://help.theplatform.com/display/vms2/copyNewFiles+method
    */
	public function copyNewFile($mediaId, $sourceUrl, array $options = array(), array $transferInfo = array()) {
		// File Management endpoint
		$url = 'http://fms.theplatform.com/web/FileManagement';

		// Media url parameters
		$urlParams = array(
			"schema" => "1.5",
			"form" => "json",
			"token" => parent::$_token,
			"account" => parent::$_account
		);

		$mediaData = array(
			"copyNewFiles" => array(
 				"mediaId" => $mediaId,
 				"sourceFiles" => array(array(
 					"sourceUrl" => $sourceUrl
				)),
				"mediaFileSettings" => array($options)
				// "mediaFileSettings" => '{"mediaFileInfo": {"assetTypes[0]": "Mezzanine"}}'
			)
		);

		if(!empty($transferInfo)) {
			$mediaData['copyNewFiles']['sourceFiles'][0]['transferInfo'] = $transferInfo;
		}
		$response = $this->_callAPI('POST', $url, $urlParams, json_encode($mediaData));
		return $this->_responseCode($response);
	}

	/**
    * @param string $mediaId
    * @return object pushlished media object data
    * @link http://help.theplatform.com/display/pub/publish+method
    */
	public function publishMedia($mediaId) {
		// Public media endpoint
		$url = 'http://publish.theplatform.com/web/Publish';

		// Media url parameters
		$urlParams = array(
			"schema" => "1.2",
			"form" => "json",
			"token" => parent::$_token,
			"account" => parent::$_account
		);

		$mediaData = array(
			"publish" => array(
 				"mediaId" => $mediaId,
				"profileId" => self::$_publishProfileId,
			)
		);
		$response = $this->_callAPI('POST', $url, $urlParams, json_encode($mediaData));
		return $this->_responseCode($response);
	}

	/**
    * @param string $uploadUrl base upload url
    * @param string $mediaObjectId media id (createMedia generates)
    * @param string $filePath file location
    * @param string $fileSize file size in bytes
    * @param string $fileFormat file format specified at http://web.theplatform.com/descriptors/enums/format.xml
	* @return object empty if successful, errors if not
	* @link http://help.theplatform.com/display/rmp/How+to+use+RMP+to+upload+media+files+to+your+account#HowtouseRMPtouploadmediafilestoyouraccount-startUpload
	*/
	public function startUpload($uploadUrl, $mediaObjectId, $filePath, $fileSize, $fileFormat) {

		// File Management endpoint
		$domain = substr($uploadUrl, -1);
		if($domain !== '/') {
			$uploadUrl = $uploadUrl . '/';
		}
		$url = $uploadUrl . 'web/Upload/startUpload'; // http://upload.mpx.theplatform.com/web/Upload/startUpload

		$this->_guid = self::createGuid();
		$this->_getCrossDomains($uploadUrl);
		// Media url parameters
		$urlParams = array(
			"schema" => "1.1",
			"token" => parent::$_token,
			"account" => parent::$_account,
			"_guid" => $this->_guid,
			"_mediaId" => $mediaObjectId,
			"_filePath" => $filePath,
			"_fileSize" => $fileSize,
			"_mediaFileInfo.format" => $fileFormat,
			"_serverId" => parent::$_serverId
		);

		$mediaData = array(
			"getUploadUrls" => array(
 				"serverId" => parent::$_serverId
			)
		);
		$response = $this->_callAPI('PUT', $url, $urlParams, json_encode($mediaData));
		if(isset($response->headers[0]['Set-Cookie'])) {
			$cookie = explode(";", $response->headers[0]['Set-Cookie']); // remove path=/Url
			$this->_uploadCookie = $cookie[0];
		}
		echo 'Upload Cookie: ' . $this->_uploadCookie;
		echo 'Url :: ' . $url . '<br>';
		echo 'urlParams :: '; 
		var_dump($urlParams);
		// return $this->_responseCode($response);
		return $response;
	}

	/**
    * @param string $uploadUrl base upload url
    * @param string $guid guid from upload
	* @return object Upload urls for $_serverIds
	* @link http://help.theplatform.com/display/rmp/How+to+use+RMP+to+upload+media+files+to+your+account#HowtouseRMPtouploadmediafilestoyouraccount-uploadFragment
	*/
	public function uploadFileFragments($uploadUrl, $guid, $offset = 0, $size, $tmpFile) {
		// File Management endpoint
		$domain = substr($uploadUrl, -1);
		if($domain !== '/') {
			$uploadUrl = $uploadUrl . '/';
		}
		$url = $uploadUrl . 'web/Upload/uploadFragment'; // http://upload.mpx.theplatform.com/web/Upload/uploadFragment

		// Media url parameters
		$urlParams = array(
			"schema" => "1.1",
			"token" => parent::$_token,
			"account" => parent::$_account,
			"_guid" => $this->_guid, // upload guid
			"_offset" => $offset, // fragment byte offset
			"_size" => $size, // fragment size in bytes
		);
		echo 'uploadFile url::';
		var_dump($url);
		echo 'uploadFile Fragment urlParams::';
		var_dump($urlParams);
		
		$url = sprintf("%s?%s", $url, http_build_query($urlParams));

        // -----------------------------------------------------------------------------------

		$fileHandle = fopen($tmpFile, "rb");
		$fileContents = stream_get_contents($fileHandle);
		fclose($fileHandle);

		$params = array(
			'http' => array(
				'method' => 'PUT',
				'header'=>"Content-Type: application/x-www-form-urlencoded\r\nCookie: ".$this->_uploadCookie . "\r\nContent-Length: ".$size."\r\n",
				// 'header'=>"Cookie: ".$this->_uploadCookie . "\r\nContent-Length: ".$size."\r\n",
				'content' => $fileContents
			)
		);
		$ctx = stream_context_create($params);
		$fp = fopen($url, 'rb', false, $ctx);

		$response = stream_get_contents($fp);

        // ----------------------------------------------------------------------------------- 
		// $response = $this->_callAPI(
		// 	'PUT', 
		// 	$url, 
		// 	$urlParams, 
		// 	false, 
		// 	array(
		// 		CURLOPT_COOKIE => $this->_uploadCookie, 
		// 		CURLOPT_HTTPHEADER => array('Content-Length: '.$size)
		// 	)
		// );
		return $response;
		// return $this->_responseCode($response);
	}

	/**
    * @param string $guid guid from upload
	* @return object Upload urls for $_serverIds
	* @link http://help.theplatform.com/display/rmp/How+to+use+RMP+to+upload+media+files+to+your+account#HowtouseRMPtouploadmediafilestoyouraccount-uploadFragment
	*/
	public function uploadFinishFragments($uploadUrl, $guid) {
		// File Management endpoint
		$domain = substr($uploadUrl, -1);
		if($domain !== '/') {
			$uploadUrl = $uploadUrl . '/';
		}
		$url = $uploadUrl . 'web/Upload/finishUpload'; // http://upload.mpx.theplatform.com/web/Upload/finishUpload

		// Media url parameters
		$urlParams = array(
			"schema" => "1.1",
			"token" => parent::$_token,
			"account" => parent::$_account,
			"_guid" => $this->_guid
		);

		$mediaData = array('finished');

		// $response = $this->_callAPI('POST', $url, $urlParams, json_encode($mediaData), array(CURLOPT_COOKIE => $this->_uploadCookie, CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded')));
		$curl = curl_init();
		$url = sprintf("%s?%s", $url, http_build_query($urlParams));
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $mediaData);
		curl_setopt($curl, CURLOPT_COOKIE, $this->_uploadCookie);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            // 'Content-Length: ' . strlen(json_encode($mediaData))
            // 'Content-Length: 12'
            )
        );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

 		$response = curl_exec($curl);
 		// echo 'finish fragments custom curl call';
 		// var_dump(curl_getinfo($curl, CURLINFO_HEADER_OUT));
		return $response;
		// return $this->_responseCode($response);
	}

	/**
    * @return object Upload urls for $_serverIds
    * @link http://help.theplatform.com/display/vms2/getUploadUrls+method
    */
	public function getUploadUrls() {
		// File Management endpoint
		$url = 'http://fms.theplatform.com/web/FileManagement';

		// Media url parameters
		$urlParams = array(
			"schema" => "1.4",
			"form" => "json",
			"token" => parent::$_token,
			"account" => parent::$_account
		);

		$mediaData = array(
			"getUploadUrls" => array(
 				"serverId" => parent::$_serverId
			)
		);
		$response = $this->_callAPI('POST', $url, $urlParams, json_encode($mediaData));
		return $this->_responseCode($response);
	}

	public function getUploadStatus($uploadUrl, $guid = null) {
		$domain = substr($uploadUrl, -1);
		if($domain !== '/') {
			$uploadUrl = $uploadUrl . '/';
		}
		$url = $uploadUrl . 'data/UploadStatus'; // http://upload.mpx.theplatform.com/data/UploadStatus

		// Media url parameters
		$urlParams = array(
			"schema" => "1.0",
			"token" => parent::$_token,
			"byGuid" => $this->_guid
		);
		$response = $this->_callAPI('GET', $url, $urlParams);
		return $this->_responseCode($response);
	}

	/**
    * @return object media servers linked to account
    * @link http://help.theplatform.com/display/vms2/Retrieving+Server+objects
    */
	public function getMediaServers() {
       // Media server end point
		$url = 'http://data.media.theplatform.com/media/data/Server';

		// Media url parameters
		$urlParams = array(
			"schema" => "1.0",
			"form" => "json",
			"token" => parent::$_token,
			"account" => parent::$_account
		);
		$response = $this->_callAPI('GET', $url, $urlParams);
		return $this->_responseCode($response);
	}

	/**
    * @param array $content 	media params, filters and formats
	* @return object All media in the account
	* @link http://help.theplatform.com/display/vms2/Retrieving+Media+objects
	*/
	public function getAccountMedia(array $content = array(), $id = '') {

		// Media server end point
		$url = 'http://data.media.theplatform.com/media/data/Media';
		if(!empty($id)) {
			$url .= '/feed/' . $id;
		}

		// Media url parameters
		$urlParams = array(
			"schema" => "1.2",
			"form" => "cjson",
			"token" => parent::$_token,
			"account" => parent::$_account
		);

		$urlParams = array_merge((array) $urlParams, (array) $content);
		$response = $this->_callAPI('GET', $url, $urlParams);
		$responseData = $this->_responseCode($response);
		return $this->_cleanseData($responseData);
	}

	/**
    * @param array $content 	filters and formats
	* @return object Media publish information
	* @link http://help.theplatform.com/display/vms2/Retrieving+Media+objects
	*/
	public function getProfileResult(array $resultFilters = array()) {

		// Media server end point
		$url = 'http://data.workflow.theplatform.com/workflow/data/ProfileResult';

		// Media url parameters
		$urlParams = array(
			"schema" => "1.0",
			"form" => "cjson",
			"token" => parent::$_token,
			"account" => parent::$_account
		);

		$urlParams = array_merge((array) $urlParams, (array) $resultFilters);
		$response = $this->_callAPI('GET', $url, $urlParams);
		return $this->_responseCode($response);
	}

	/**
    * @param array $urlParams 	feed params, filters and formats
	* @return object Media feed with public urls
	* @link http://help.theplatform.com/display/fs3/Requesting+feed+content
	*/
	public function getFeedMedia(array $urlParams = array()) {
		$response = $this->_callAPI('GET', self::$_feedUrl, $urlParams);
		$responseData = $this->_responseCode($response);
		return $this->_cleanseData($responseData);
	}

	/**
    * @param array $searchCriteria 	media params, filters and formats
    * @param array $resultFilters 	filters and formats for profile results
	* @return object Media with published results
	*/
	public function getAccountMediaWithResults(array $searchCriteria = array(), array $resultFilters = array()) {
		$media = $this->getAccountMedia($searchCriteria);
		$results = $this->getProfileResult($resultFilters);
		if(isset($media->body->entries) && isset($results->body->entries)) {
			$count = 0;
			$mediaEntries = &$media->body->entries;
			$resultEntries = $results->body->entries;

			foreach($mediaEntries as $key => &$entry) {
				$resultKey = $this->_recursiveValueSearch($entry->id, $resultEntries);
				if($resultKey !== false) {
					$entry->status = $resultEntries[$resultKey]->status;
					$entry->statusInfo = $resultEntries[$resultKey]->statusInfo;

					if(isset($resultFilters['byStatus'])) {
						if(!in_array($resultEntries[$resultKey]->status, explode("|", $resultFilters['byStatus']))) {
							// Media workflow status doesn't match search criteria
							$count++;
							unset($entry[$key]);
						}
					}

				} else { // Media was never published
					$entry->status = '';
					$entry->statusInfo = '';
					if(isset($resultFilters['byStatus'])) {
						$count++;
						unset($mediaEntries[$key]);
					}
				}
			}
			$mediaEntries = array_values($mediaEntries);
			unset($entry); // Clearing $entry reference to $mediaEntries
			unset($mediaEntries); // Clearing $mediaEntries reference to $media array
			if(isset($media->body->entryCount)) {
				$media->body->entryCount = $media->body->entryCount - $count;
				$media->body->totalResults = $media->body->totalResults - $count;
			}
		}
		return $media;
	}

	/**
	* @param string $mediaId
	* @return error Returns nothing if string, throws exception if $mediaId isn't a string
    */
	public function setMediaServer($serverId) {
		if(is_string($serverId)) {
			parent::$_serverId = $serverId;
		} else {
			throw new Exception('Server id must be a string');
		}
	}

	static public function setPublishProfile($profileId) {
		if(is_string($profileId)) {
			self::$_publishProfileId = $profileId;
		}
	}

	static public function setFeedUrl($feedUrl, $secured = false) {
		if(is_string($feedUrl)) {
			if($secured) {
				self::$_feedUrl = preg_replace("/^http:/i", "https:", $feedUrl);
			} else {
				self::$_feedUrl = $feedUrl;
			}
		}
	}

	protected function _modifyMetadata(array $metadata = array()) {
		$customFields = $this->_getCustomFields();

		foreach($metadata as $key => &$value) {
			if(!empty($value)) {
				if(array_key_exists($key, $customFields)) {
					$custom = $customFields[$key];
					if($custom['dataType'] == 'String') {
						if($custom['dataStructure'] == 'List') {
							// Overwrite master value
							$value = $this->_modifyStringList($metadata[$key]);
						}
					}
				}
			} else {
				// Commenting this out so you can make metadata empty
				// unset($metadata[$key]);
			}
		}
		unset($value); // Kill reference to $value
		return $metadata;
	}

	protected function _getCustomFields() {
		// Media server end point
		$url = 'http://data.media.theplatform.com/media/data/Media/Field';

		// Media url parameters
		$urlParams = array(
			"schema" => "1.6",
			"form" => "json",
			"token" => parent::$_token,
			"account" => parent::$_account
		);
		$response = $this->_callAPI('GET', $url, $urlParams);
		$customFields = array();
		if(isset($response->body->entryCount)) {
			if($response->body->entryCount >= 1) {
				foreach($response->body->entries as $customField) {
					$casted = (array) $customField;
					// var_dump($casted);
					$customFields['pl1$'.$casted['plfield$fieldName']] = array(
						'id' => $casted['id'],
						'title' => $casted['title'],
						'dataType' => $casted['plfield$dataType'],
						'dataStructure' => $casted['plfield$dataStructure'],
						'namespace' => $casted['plfield$namespace']
					);
				}
			}
		}
		return $customFields;	
	}

	/**
	* @param string $string comma separated string
	* @param string $valueToCheck if value already exists in array, if not add
	* @return bool If page exists returns true otherwise false
    */
	protected function _modifyStringList($string, $valueToCheck = null) {
		$arrayList = array();
		$cleanedString = trim(str_replace(" ", "", $string), ",");
		if(strpos($cleanedString, ",") !== false ) { // If string contains a comma
			$arrayList = explode(",", $cleanedString);
			// If $valueToCheck is not in the array, add it
			if(!empty($valueToCheck)) {
				if(!in_array($valueToCheck, $arrayList)) {
					$arrayList[] = $valueToCheck;
				}
			}
		} else {
			if(!empty($cleanedString)) {
				$arrayList[] = $cleanedString;
			}
		}
		return $arrayList;
	}

	/**
	* @param string $uploadUrl defined in getUploadUrls()
	* @return bool If page exists returns true otherwise false
    */
	protected function _getCrossDomains($uploadUrl = '') {
		if(!$uploadUrl || !is_string($uploadUrl) || ! preg_match('/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $uploadUrl)){
		    return false;
		}
		$domain = substr($uploadUrl, -1);
		$crossDomainExt = 'crossdomain.xml';
		if($domain !== '/') {
			$uploadUrl = $uploadUrl . '/';
		}
		$location = $uploadUrl . $crossDomainExt;
		$file_headers = @get_headers($location);
		if(strpos($file_headers[0], '404 Not Found')) {
		    return false;
		} else {
		    return true;
		}
	}
	
}

?>