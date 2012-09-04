<?php
namespace Shift1\Core\Request;

/**
 * @TODO
 *
curlRequest
- url
- user agent
- followlocation, maxredirs
- timeout
- cookiefile
- addParam(key, val)
- addFile(key, file)
- setHeader(Header $header)
- binary
- setFetchMode(BODY_ONLY, HEADER_ONLY, BOTH)

curlResponse
- body
- header
- status
- errno
- error


 */

class CurlRequest {

    const USERCLIENT_MOZILLA = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';

    const TIMEUNIT_S  = 1;
    const TIMEUNIT_MS = 2;
    const TIMEUNIT_M  = 3;

    protected $curlOpts = array();
    protected $handle;

    public function __construct() {
        /** @todo throw exception if curl is not installed  */
        $this->handle = \curl_init();
        $this->setOption(\CURLOPT_RETURNTRANSFER, true);
    }


    /**
     * @param int $key
     * @param string $val
     * @return bool
     */
    public function setOption($key, $val) {
        return \curl_setopt($this->handle, $key, $val);
    }

    /**
     * @param array $options
     * @return bool
     */
    public function setOptionArray(array $options) {
        return \curl_setopt_array($this->handle, $options);
    }

    /**
     * @param $uri
     * @return bool
     */
    public function setUrl($uri) {
        return $this->setOption(\CURLOPT_URL, $uri);
    }

    /**
     * @param $agent
     * @return bool
     */
    public function setUserAgent($agent) {
        return $this->setOption(\CURLOPT_USERAGENT, $agent);
    }

    /**
     * @param bool $state
     * @param int $maxRedirs
     * @return bool
     */
    public function setDoFollowRedirs($state, $maxRedirs) {
        return $this->setOptionArray(array(
            \CURLOPT_FOLLOWLOCATION => (bool) $state,
            \CURLOPT_MAXREDIRS => (int) $maxRedirs,
        ));
    }

    /**
     * @param int $timeout
     * @param int $unit
     * @return bool
     */
    public function setTimeout($timeout, $unit = self::TIMEUNIT_S) {
        switch($unit) {
            case self::TIMEUNIT_S:
                $key = \CURLOPT_TIMEOUT;
                break;
            case self::TIMEUNIT_MS:
                $key = \CURLOPT_TIMEOUT_MS;
                break;
            case self::TIMEUNIT_M:
                $key = \CURLOPT_TIMEOUT;
                $timeout = $timeout * 60;
                break;
            default:
                /** @todo throw exception here */
        }
        return $this->setOption($key, $timeout);
    }

    /**
     * @param string $file
     * @return bool
     */
    public function setCookieFile($file) {
        $resJar = $this->setOption(\CURLOPT_COOKIEJAR, $file);  // for reading
        $resFile = $this->setOption(\CURLOPT_COOKIEFILE, $file); // for writing
        return $resJar && $resFile;
    }

    /**
     * @param string|array $cookies
     * @return bool
     */
    public function setCookies($cookies) {
        if(\is_array($cookies)) {
            $cookieStr = '';
            foreach($cookies as $key => $value) {
                $cookieStr .= \sprintf(' %s=%s;', $key, $value);
            }
            $cookies = \trim(\rtrim($cookieStr, ';'));
        } elseif(!\is_string($cookies)) {
            /** @todo throw exception here */
        }
        return $this->setOption(\CURLOPT_COOKIE, $cookies);
    }

}
