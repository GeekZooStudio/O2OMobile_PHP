<?php
/**
 * SimpleRestClient
 *
 * @copyright 2009
 * @author University of Washington
 * @version 1.0 9/24/2009
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and
 * to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions
 * of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
class SimpleRestClient
{
    // Provide default CURL options
    protected $_default_opts = array(
    CURLOPT_RETURNTRANSFER => true,  // return result instead of echoing
    CURLOPT_SSL_VERIFYPEER => false, // stop cURL from verifying the peer's certificate
    CURLOPT_FOLLOWLOCATION => true,  // follow redirects, Location: headers
    CURLOPT_MAXREDIRS      => 10     // but dont redirect more than 10 times
    );

    protected $_response=null;

    // hash to store any CURLOPT_ option values
    protected $_options;

    // container for full CURL getinfo hash
    protected $_info=null;

    // variable to hold the CURL handle
    private $_c=null;

    /**
    * Instantiate a SimpleRestClient object.
    *
    * @param string $cert_file path to SSL public identity file
    * @param string $key_file path to SSL private key file
    * @param string $key_file passphrase to access $key_file
    * @param string $user_agent client identifier sent to server with HTTP headers
    * @param array $options hash of CURLOPT_ options and values
    */
    public function __construct($cert_file=null,$key_file=null, $password=null, $user_agent="PhpRestClient", $options=null)
    {
        // make sure we can use curl
    if (!function_exists('curl_init')) {
        throw new Exception('Trying to use CURL, but module not installed.');
    }

    // load default options then add the ones passed as argument
    $this->_options = $this->_default_opts;
    if (is_array($options)) {
        foreach ($options as $curlopt => $value) {
            $this->_options[$curlopt] = $value;
        }
    }

    // Use the mutator methods to take advantage of any processing or error checking
        $this->setCertFile($cert_file);
        $this->setKeyFile($key_file, $password);
        $this->_options[CURLOPT_USERAGENT] = $user_agent;

    //  initialize the _info container
    $this->_info = array();
    }

    /**
    * Set a CURL option
    *
    * @param int $curlopt index of option expressed as CURLOPT_ constant
    * @param mixed $value what to set this option to
    */
    public function setOption($curlopt, $value)
    {
    $this->_options[$curlopt] = $value;
    }

    /**
    * Set the local file system location of the SSL public certificate file that
    * cURL should pass to the server to identify itself.
    *
    * @param string $cert_file path to SSL public identity file
    */
    public function setCertFile($cert_file)
    {
    if (!is_null($cert_file))
    {
        if (!file_exists($cert_file)) {
                throw new Exception('Cert file: '. $cert_file .' does not exist!');
        }
        if (!is_readable($cert_file)) {
            throw new Exception('Cert file: '. $cert_file .' is not readable!');
        }
        //  Put this in _options hash
        $this->_options[CURLOPT_SSLCERT] = $cert_file;
    }
    }

    /**
    * Set the local file system location of the private key file that cURL should
    * use to decrypt responses from the server.
    *
    * @param string $key_file path to SSL private key file
    * @param string $password passphrase to access $key_file
    */
    public function setKeyFile($key_file, $password = null)
    {
    if (!is_null($key_file))
    {
        if (!file_exists($key_file)) {
        throw new Exception('SSL Key file: '. $key_file .' does not exist!');
        }
        if (!is_readable($key_file)) {
        throw new Exception('SSL Key file: '. $key_file .' is not readable!');
        }
        //  set the private key in _options hash
        $this->_options[CURLOPT_SSLKEY] = $key_file;
        //  optionally store a pass phrase for key
        if (!is_null($password)) {
        $this->_options[CURLOPT_SSLCERTPASSWD] = $password;
        }
    }
    }

    /**
    * Set the client software identifier sent to server with HTTP request headers
    *
    * @param string $user_agent client identifier
    */
    public function setUserAgent($user_agent)
    {
        $this->_options[CURLOPT_USERAGENT] = $user_agent;
    }

    /**
    * Retrieve the response from the server captured after calling makeWebRequest()
    *
    * @return string
    */
    public function getWebResponse()
    {
        return $this->_response;
    }

    /**
    * Make an HTTP GET request to the URL provided. Capture the results for future
    * use.
    *
    * @param string $url absolute URL to make an HTTP request to
    */
    public function getWebRequest($url) //  removed $public argument, implied by existence of SSLCERT and SSLKEY in _options
    {
        $_c = curl_init($url); // $url is the resource we're fetching

    //  set the options
    foreach ($this->_options as $curlopt => $value) {
        curl_setopt($_c, $curlopt, $value);
    }

        $_raw_data = curl_exec($_c);
    $_raw_data = $this->fix_iis_data($_raw_data);
    if (curl_errno($_c) != 0) {
       throw new Exception('Aborting. cURL error: ' . curl_error($_c));
    }
        $this->_response=str_replace("xmlns=","a=",$_raw_data); //Getting rid of xmlns so that clients can use SimpleXML and XPath without problems otherwise SimpleXML does not recognize the document as an XML document

        //  Store all cURL metadata about this request
    $this->_info = curl_getinfo($_c);
    curl_close($_c);
    }
    /**
    * Make an HTTP POST request to the URL provided. Capture the results for future
    * use.
    * @param array $data can be either a string or an array
    */
    public function postWebRequest($url, $data)
    {
    // Serialize the data into a query string
    if (is_array($data)) {
      $post_data = '';
      $need_amp = false;
      foreach ($data as $varname => $val) {
        if ($need_amp) $post_data .= '&';
        $val = urlencode($val);
        $post_data .= "{$varname}={$val}";
        $need_amp = true;
      }
    } elseif (is_string($data)) {
      $post_data = $data;
    } else {
      $post_data = '';
    }

    //Do the POST and get back the results
        $_c = curl_init($url); // $url is the resource we're fetching
    //  set the options
    foreach ($this->_options as $curlopt => $value) {
        curl_setopt($_c, $curlopt, $value);
    }
    curl_setopt($_c,CURLOPT_POST, 1);
    curl_setopt($_c,CURLOPT_POSTFIELDS, $post_data);
        $_raw_data = curl_exec($_c);
    $_raw_data = $this->fix_iis_data($_raw_data);
    if (curl_errno($_c) != 0) {
       throw new Exception('Aborting. cURL error: ' . curl_error($_c));
    }
        $this->_response=str_replace("xmlns=","a=",$_raw_data); //Getting rid of xmlns so that clients can use SimpleXML and XPath without problems otherwise SimpleXML does not recognize the document as an XML document
        //  Store all cURL metadata about this request
    $this->_info = curl_getinfo($_c);
    curl_close($_c);
    }
    /**
    * Get stats and info about the last run HTTP request. Data available here
    * is from Curl's curl_getinfo function. Either returns the full assoc
    * array of data or the specified item.
    *
    * @param string $item index to a specific info item
    * @return mixed
    */
    public function getInfo($item = 0)
    {
    if ($item === 0) { return $this->_info; }

    if (array_key_exists($item, $this->_info)) {
        return $this->_info[$item];
    } else {
        return null;
    }
    }

    /**
    * Get the HTTP status code returned by the last execution of makeWebRequest()
    *
    * @return int
    */
    public function getStatusCode()
    {
        return $this->getInfo('http_code');
    }
    /**
    * Apply a couple of heuristics to fix data coming from an IIS server
    */
    private function fix_iis_data($data)
    {
      // IIS has an annoying habit of prepending binary garbage to the beginning
      // of their XML payloads.  Nuke it.
      $beg = strpos($data, '<?xml');
      if ($beg) {
    $data = substr($data, $beg, strlen($data) - strlen($beg));
      }

      // IIS often inserts invalid character references.
      $data = str_replace('&#x0;', '', $data);

      return($data);
    }
}