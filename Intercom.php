<?php
/**
 * Intercom is a customer relationship management and messaging tool for web app owners
 * 
 * This library provides connectivity with the Intercom API (https://api.intercom.io)
 * 
 * Basic usage:
 * 
 * 1. Configure Intercom with your access credentials
 * <code>
 * <?php
 * $intercom = new Intercom('dummy-app-id', 'dummy-api-key');
 * ?>
 * </code>
 * 
 * 2. Make requests to the API
 * <code>
 * <?php
 * $intercom = new Intercom('dummy-app-id', 'dummy-api-key');
 * $users = $intercom->getAllUsers();
 * var_dump($users);
 * ?>
 * </code>
 * 
 * @author    Bruno Pedro <bruno.pedro@cloudwork.com>
 * @copyright Copyright 2013 Nubera eBusiness S.L. All rights reserved.
 * @link      http://www.nubera.com/
 * @license   http://opensource.org/licenses/MIT
 **/


/**
 * Intercom.io API 
 */
class Intercom
{
    /**
     * The Intercom API endpoint
     */
    private $apiEndpoint = 'https://api.intercom.io/v1/';

    /**
     * The Intercom application ID
     */
    private $appId = null;

    /**
     * The Intercom API key
     */
    private $apiKey = null;

    /**
     * Last HTTP error obtained from curl_errno() and curl_error()
     */
    private $lastError = null;

    /**
     * Whether we are in debug mode. This is set by the constructor
     */
    private $debug = false;

    /**
     * The constructor
     *
     * @param  string $appId  The Intercom application ID
     * @param  string $apiKey The Intercom API key
     * @param  string $debug  Optional debug flag
     * @return void
     **/
    public function __construct($appId, $apiKey, $debug = false)
    {
        $this->appId = $appId;
        $this->apiKey = $apiKey;
        $this->debug = $debug;
    }

    /**
     * Make an HTTP call using curl.
     * 
     * @param  string $url       The URL to call
     * @param  string $method    The HTTP method to use, by default GET
     * @param  string $post_data The data to send on an HTTP POST (optional)
     * @return object
     **/
    private function httpCall($url, $method = 'GET', $post_data = null)
    {
        $headers = array();
        if ($post_data) {
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Expect:';
        }

        $ch = curl_init($url);

        if ($this->debug) {
            curl_setopt($ch, CURLOPT_VERBOSE, true);
        }

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_POST, true);
        } elseif ($method == 'PUT') {
            $putFile = tmpfile();
            fwrite($putFile, $post_data);
            fseek($putFile, 0);
            curl_setopt($ch, CURLOPT_INFILE, $putFile);
            curl_setopt($ch, CURLOPT_INFILESIZE, strlen($post_data));
            curl_setopt($ch, CURLOPT_PUT, true);
        } elseif ($method != 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 4096);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
        curl_setopt($ch, CURLOPT_USERPWD, $this->appId . ':' . $this->apiKey);

        $response = curl_exec($ch);

        // Set HTTP error, if any
        $this->lastError = array('code' => curl_errno($ch), 'message' => curl_error($ch));

        return json_decode($response);
    }

    /**
     * Get all users from your Intercom account.
     * 
     * @param  integer $page    The results page number
     * @param  integer $perPage The number of results to return on each page
     * @return object
     **/
    public function getAllUsers($page = 1, $perPage = null)
    {
        $path = 'users/?page=' . $page;

        if (!empty($perPage)) {
            $path .= '&per_page=' . $perPage;
        }

        return $this->httpCall($this->apiEndpoint . $path);
    }

    /**
     * Get a specific user from your Intercom account.
     * 
     * @param  string $id The ID of the user to retrieve
     * @return object
     **/
    public function getUser($id)
    {
        $path = 'users/';
        if (preg_match('/@/', $id)) {
            $path .= '?email=';
        } else {
            $path .= '?user_id=';
        }
        $path .= urlencode($id);
        return $this->httpCall($this->apiEndpoint . $path);
    }

    /**
     * Create a user on your Intercom account.
     * 
     * @param  string $id                The ID of the user to be created
     * @param  string $email             The user's email address (optional)
     * @param  string $name              The user's name (optional)
     * @param  array  $customData        Any custom data to be aggregate to the user's record (optional)
     * @param  long   $createdAt         UNIX timestamp describing the date and time when the user was created (optional)
     * @param  string $lastSeenIp        The last IP address where the user was last seen (optional)
     * @param  string $lastSeenUserAgent The last user agent of the user's browser (optional)
     * @param  long   $lastRequestAt     UNIX timestamp of the user's last request (optional)
     * @param  string $method            HTTP method, to be used by updateUser()
     * @return object
     **/
    public function createUser($id,
                               $email = null,
                               $name = null,
                               $customData = array(),
                               $createdAt = null,
                               $lastSeenIp = null,
                               $lastSeenUserAgent = null,
                               $lastRequestAt = null,
                               $method = 'POST')
    {
        $data = array();

        $data['user_id'] = $id;

        if (!empty($email)) {
            $data['email'] = $email;
        }

        if (!empty($name)) {
            $data['name'] = $name;
        }

        if (empty($createdAt)) {
            $createdAt = time();
        }
        $data['created_at'] = $createdAt;

        if (!empty($lastSeenIp)) {
            $data['last_seen_ip'] = $lastSeenIp;
        }

        if (!empty($lastSeenUserAgent)) {
            $data['last_seen_user_agent'] = $lastSeenUserAgent;
        }

        if (!empty($lastRequestAt)) {
            $data['last_request_at'] = $lastRequestAt;
        }

        if (!empty($customData)) {
            $data['custom_data'] = $customData;
        }

        $path = 'users';
        return $this->httpCall($this->apiEndpoint . $path, $method, json_encode($data));
    }

    /**
     * Update an existing user on your Intercom account.
     * 
     * @param  string $id                The ID of the user to be updated
     * @param  string $email             The user's email address (optional)
     * @param  string $name              The user's name (optional)
     * @param  array  $customData        Any custom data to be aggregate to the user's record (optional)
     * @param  long   $createdAt         UNIX timestamp describing the date and time when the user was created (optional)
     * @param  string $lastSeenIp        The last IP address where the user was last seen (optional)
     * @param  string $lastSeenUserAgent The last user agent of the user's browser (optional)
     * @param  long   $lastRequestAt     UNIX timestamp of the user's last request (optional)
     * @return object
     **/
    public function updateUser($id,
                               $email = null,
                               $name = null,
                               $customData = array(),
                               $createdAt = null,
                               $lastSeenIp = null,
                               $lastSeenUserAgent = null,
                               $lastRequestAt = null)
    {
        return $this->createUser($id, $email, $name, $customData, $createdAt, $lastSeenIp, $lastSeenUserAgent, $lastRequestAt, 'PUT');
    }

    /**
     * Delete an existing user from your Intercom account
     * 
     * @param  string $id The ID of the user to be deleted
     * @return object
     **/
    public function deleteUser($id)
    {
        $path = 'users/';
        if (preg_match('/@/', $id)) {
            $path .= '?email=';
        } else {
            $path .= '?user_id=';
        }
        $path .= urlencode($id);
        return $this->httpCall($this->apiEndpoint . $path, 'DELETE');
    }

    /**
     * Create an impression associated with a user on your Intercom account
     * 
     * @param  string $userId     The ID of the user
     * @param  string $email      The email of the user (optional)
     * @param  string $userIp     The IP address of the user (optional)
     * @param  string $userAgent  The user agent of the user (optional)
     * @param  string $currentUrl The URL the user is visiting (optional)
     * @return object
     **/
    public function createImpression($userId, $email = null, $userIp = null, $userAgent = null, $currentUrl = null)
    {
        $data = array();

        $data['user_id'] = $userId;

        if (!empty($email)) {
            $data['email'] = $email;
        }

        if (!empty($userIp)) {
            $data['user_ip'] = $userIp;
        }

        if (!empty($userAgent)) {
            $data['user_agent'] = $userAgent;
        }

        if (!empty($currentUrl)) {
            $data['current_url'] = $currentUrl;
        }
        $path = 'users/impressions';

        return $this->httpCall($this->apiEndpoint . $path, 'POST', json_encode($data));
    }

    /**
     * Get the last error from curl.
     * 
     * @return array Array with 'code' and 'message' indexes
     */
    public function getLastError()
    {
        return $this->lastError;
    }
}
?>