<?php
class Mpx {
    static protected $_token;
    protected static $_serverId = '';
    protected static $_account = '';

    static public function createGuid($namespace = '') {     
        static $guid = '';
        $uid = uniqid("", true);
        $data = $namespace;
        $data .= $_SERVER['REQUEST_TIME'];
        $data .= $_SERVER['HTTP_USER_AGENT'];
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid = substr($hash,  0,  8) . 
                '-' .
                substr($hash,  8,  4) .
                '-' .
                substr($hash, 12,  4) .
                '-' .
                substr($hash, 16,  4) .
                '-' .
                substr($hash, 20, 12);
        return $guid;
    }


    static public function setAccountId($accountId) {
        if(is_string($accountId)) {
            self::$_account = $accountId;
        }
    }

    static public function setServerId($serverId) {
        if(is_string($serverId)) {
            self::$_serverId = $serverId;
        }
    }

    /**
    * @param    string $method
    * @param    string $url        API endpoint
    * @param    array  $urlParams  extends to end of url "?format=json"
    * @param    string $data json  encoded data
    * @param    array  $options    extra curl options
    * @return   object class with headers and body
    */
    protected function _callAPI($method, $url, $urlParams = false, $data = false, $options = array()) {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, true);
                if ($urlParams) {
                    $url = sprintf("%s?%s", $url, http_build_query($urlParams));
                } else {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $urlParams);
                }
                break;
            case "PUT":
                if ($urlParams) {
                    $url = sprintf("%s?%s", $url, http_build_query($urlParams));
                }
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            case "GET":
                // Build url parameters
                if ($urlParams) {
                    $url = sprintf("%s?%s", $url, http_build_query($urlParams));
                }
                break;
        }
        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($data))                                                                       
            );
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        foreach($options as $key => $value) {
            curl_setopt($curl, $key, $value);
        }
        $response = curl_exec($curl);
        $responseArray = array();
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        $responseArray['headers'] = $this->_getHeadersFromCurlResponse($header);
        if(!empty($body)) { // If the response is not empty json decode
            $responseArray['body'] = json_decode($body);
        } else {
            $responseArray['body'] = $body;
        }
        return (object) $responseArray;
    }

    /**
    * @param object $response reply from MPX Api
    * @return object error code with cause or successful mpx response code
    */
    protected function _responseCode($response) {
        if(isset($response->body->responseCode) && $response->body->responseCode !== 200) {
            $response->body->success = false;
        } else {
            $response->body->success = true;
        }
        return $response;
    }

    protected function _getHeadersFromCurlResponse($headerContent) {
        $headers = array();

        // Split the string on every "double" new line.
        $arrRequests = explode("\r\n\r\n", $headerContent);

        // Loop of response headers. The "count() -1" is to 
        //avoid an empty row for the extra line break before the body of the response.
        for($index = 0; $index < count($arrRequests) -1; $index++) {
            foreach(explode("\r\n", $arrRequests[$index]) as $i => $line) {
                if($i === 0) {
                    $headers[$index]['http_code'] = $line;
                } else {
                    list ($key, $value) = explode(': ', $line);
                    $headers[$index][$key] = $value;
                }
            }
        }

        return $headers;
    }

    protected function _cleanseData($response) {
        $cleanseFields = array('title', 'description', 'keywords', 'author');
        $customFields = $this->_getCustomFields(); // Defined in MpxMedia;
        foreach($customFields as $key => $value) {
            $cleanseFields[] = $key;
        }
        if(isset($response->body->entries)) {
            foreach ($response->body->entries as $key => &$value) {
                foreach($cleanseFields as $cleanseKey) {
                    if(isset($value->$cleanseKey)) {
                        if(is_array($value->$cleanseKey)) {
                            $value->$cleanseKey = array_map('htmlspecialchars', $value->$cleanseKey);
                        } else {
                            $value->$cleanseKey = htmlspecialchars($value->$cleanseKey);    
                        }   
                    }
                }
            }
        }
        return $response;
    }

    protected function _recursiveValueSearch($needle,$haystack) {
        foreach($haystack as $key => $value) {
            $current_key = $key;
            if($needle === $value OR 
                (is_object($value) && $this->_recursiveValueSearch($needle, $value) !== false) OR
                (is_array($value) && $this->_recursiveValueSearch($needle, $value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }
}