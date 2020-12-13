<?php
class TwitterAPIExchange
{
    private $oauth_access_token;
    private $oauth_access_token_secret;
    private $consumer_key;
    private $consumer_secret;
    private $postfields;
    private $getfield;
    protected $oauth;
    public $url;
    public $requestMethod;
    protected $httpStatusCode;
    public function __construct(array $settings)
    {
        if (!function_exists('curl_init'))
        {
            throw new RuntimeException('TwitterAPIExchange requires cURL extension to be loaded, see: http://curl.haxx.se/docs/install.html');
        }
        if (!isset($settings['oauth_access_token'])
            || !isset($settings['oauth_access_token_secret'])
            || !isset($settings['consumer_key'])
            || !isset($settings['consumer_secret']))
        {
            throw new InvalidArgumentException('Incomplete settings passed to TwitterAPIExchange');
        }
        $this->oauth_access_token = $settings['oauth_access_token'];
        $this->oauth_access_token_secret = $settings['oauth_access_token_secret'];
        $this->consumer_key = $settings['consumer_key'];
        $this->consumer_secret = $settings['consumer_secret'];
    }
    public function setPostfields(array $array)
    {
        if (!is_null($this->getGetfield()))
        {
            throw new Exception('You can only choose get OR post fields (post fields include put).');
        }
if (isset($array['status']) && substr($array['status'], 0, 1) === '@')
        {
            $array['status'] = sprintf("\0%s", $array['status']);
        }
foreach ($array as $key => &$value)
        {
            if (is_bool($value))
            {
                $value = ($value === true) ? 'true' : 'false';
            }
        }
        $this->postfields = $array;
        if (isset($this->oauth['oauth_signature']))
        {
            $this->buildOauth($this->url, $this->requestMethod);
        }
        return $this;
    }
    public function setGetfield($string)
    {
        if (!is_null($this->getPostfields()))
        {
            throw new Exception('You can only choose get OR post / post fields.');
        }
        $getfields = preg_replace('/^\?/', '', explode('&', $string));
        $params = array();
        foreach ($getfields as $field)
        {
            if ($field !== '')
            {
                list($key, $value) = explode('=', $field);
                $params[$key] = $value;
            }
        }
        $this->getfield = '?' . http_build_query($params, '', '&');
        return $this;
    }
    public function getGetfield()
    {
        return $this->getfield;
    }
    public function getPostfields()
    {
        return $this->postfields;
    }
    public function buildOauth($url, $requestMethod)
    {
        if (!in_array(strtolower($requestMethod), array('post', 'get', 'put', 'delete')))
        {
            throw new Exception('Request method must be either POST, GET or PUT or DELETE');
        }
        $consumer_key              = $this->consumer_key;
        $consumer_secret           = $this->consumer_secret;
        $oauth_access_token        = $this->oauth_access_token;
        $oauth_access_token_secret = $this->oauth_access_token_secret;
        $oauth = array(
            'oauth_consumer_key' => $consumer_key,
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $oauth_access_token,
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0'
        );
        $getfield = $this->getGetfield();
        if (!is_null($getfield))
        {
            $getfields = str_replace('?', '', explode('&', $getfield));
            foreach ($getfields as $g)
            {
                $split = explode('=', $g);
                if (isset($split[1]))
                {
                    $oauth[$split[0]] = urldecode($split[1]);
                }
            }
        }
        $postfields = $this->getPostfields();
        if (!is_null($postfields)) {
            foreach ($postfields as $key => $value) {
                $oauth[$key] = $value;
            }
        }
        $base_info = $this->buildBaseString($url, $requestMethod, $oauth);
        $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;
        $this->url           = $url;
        $this->requestMethod = $requestMethod;
        $this->oauth         = $oauth;
        return $this;
    }
    public function performRequest($return = true, $curlOptions = array())
    {
        if (!is_bool($return))
        {
            throw new Exception('performRequest parameter must be true or false');
        }
        $header =  array($this->buildAuthorizationHeader($this->oauth), 'Expect:');
        $getfield = $this->getGetfield();
        $postfields = $this->getPostfields();
        if (in_array(strtolower($this->requestMethod), array('put', 'delete')))
        {
            $curlOptions[CURLOPT_CUSTOMREQUEST] = $this->requestMethod;
        }
        $options = $curlOptions + array(
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_HEADER => false,
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        );
        if (!is_null($postfields))
        {
            $options[CURLOPT_POSTFIELDS] = http_build_query($postfields, '', '&');
        }
        else
        {
            if ($getfield !== '')
            {
                $options[CURLOPT_URL] .= $getfield;
            }
        }
        $feed = curl_init();
        curl_setopt_array($feed, $options);
        $json = curl_exec($feed);
        $this->httpStatusCode = curl_getinfo($feed, CURLINFO_HTTP_CODE);
        if (($error = curl_error($feed)) !== '')
        {
            curl_close($feed);
throw new \Exception($error);
        }
        curl_close($feed);
        return $json;
    }
    private function buildBaseString($baseURI, $method, $params)
    {
        $return = array();
        ksort($params);
        foreach($params as $key => $value)
        {
            $return[] = rawurlencode($key) . '=' . rawurlencode($value);
        }
        return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $return));
    }
    private function buildAuthorizationHeader(array $oauth)
    {
        $return = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key => $value)
        {
            if (in_array($key, array('oauth_consumer_key', 'oauth_nonce', 'oauth_signature',
                'oauth_signature_method', 'oauth_timestamp', 'oauth_token', 'oauth_version'))) {
                $values[] = "$key=\"" . rawurlencode($value) . "\"";
            }
        }
        $return .= implode(', ', $values);
        return $return;
    }
    public function request($url, $method = 'get', $data = null, $curlOptions = array())
    {
        if (strtolower($method) === 'get')
        {
            $this->setGetfield($data);
        }
        else
        {
            $this->setPostfields($data);
        }
        return $this->buildOauth($url, $method)->performRequest(true, $curlOptions);
    }
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}
