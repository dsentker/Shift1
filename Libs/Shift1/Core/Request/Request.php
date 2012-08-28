<?php
namespace Shift1\Core\Request;

class Request implements RequestInterface {

    /**
     * @var array
     */
    protected $server = array();

    /**
     * @var array
     */
    protected $files = array();

    /**
     * @var array
     */
    protected $post = array();

    /**
     * @var array
     */
    protected $get = array();

    /**
     * @var array
     */
    protected $cookie = array();

    /**
     * @var array
     */
    protected $env = array();


    /**
     * @var string
     */
    protected $appWebRoot;


    protected $cliArgs = array();

    /**
     * @param string $appWebRoot The relative uri where this app begins
     */
    public function __construct($appWebRoot = '/') {
        $this->appWebRoot = $appWebRoot;
    }

    /**
     * @static
     * @param string $appWebRootUri
     * @return Request
     */
    public static function fromGlobals($appWebRootUri) {
        $req = new static($appWebRootUri);
        $req->setServer($_SERVER);
        $req->setCookie($_COOKIE);
        $req->setEnv($_ENV);
        $req->setFiles($_FILES);
        $req->setGet($_GET);
        $req->setPost($_POST);

        if(isset($GLOBALS['argv'])) {
            $cliArgs = $GLOBALS['argv'];
            \array_shift($cliArgs);
            $req->cliArgs = $cliArgs;
        }

        return $req;
    }

    /**
     * @return bool If the Request was sent via XMLHttpRequest (jQuery and other JavaScript Frameworks)
     */
    public function isXmlHttp() {
         return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    /**
     * @return bool if the request was sended via CLI
     */
    public function isCli() {
        return (\php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR']));
    }

    /**
     * @return array
     */
    public function getCliArgs() {
        return $this->cliArgs;
    }

    /**
     * Parses $GLOBALS['argv'] for parameters and assigns them to an array.
     *
     * @return array
     * @todo cache this processing
     *
     * Supports:
     * -e
     * -e <value>
     * --long-param
     * --long-param=<value>
     * --long-param <value>
     * <value>
     *
     */
    public function parseCliArgs($repeatingParamsAsArray = true) {

        $result = array();
        $params = $this->getCliArgs();

        while (list($i, $p) = each($params)) {
            if ($p{0} == '-') {
                $pname = substr($p, 1);
                $value = true;
                if ($pname{0} == '-') {
                    // long-opt (--<param>)
                    $pname = \substr($pname, 1);
                    if (\strpos($p, '=') !== false) {
                        // value specified inline (--<param>=<value>)
                        list($pname, $value) = \explode('=', \substr($p, 2), 2);
                    }
                }
                // check if next parameter is a descriptor or a value
                $nextparm = \current($params);
                if ($value === true && $nextparm !== false && $nextparm{0} != '-') {
                    list($tmp, $value) = \each($params);
                }

                if(isset($result[$pname]) && \is_array($result[$pname])) {
                    $result[$pname][] = $value;
                } elseif(isset($result[$pname])) {
                    $result[$pname] = array($result[$pname]);
                    $result[$pname][] = $value;
                } else {
                    $result[$pname] = $value;
                }


            } else {
                // param doesn't belong to any option
                $result[] = $p;
            }
        }

        die(print_r($result));return $result;

    }

    /**
     * @return string
     */
    public function getDomain() {
        return (isset($this->server['HTTP_HOST'])) ? $this->server['HTTP_HOST'] : '';
    }

    /**
     * @param string $uri
     * @return void
     */
    public function setRequestUri($uri) {
        $this->server['REQUEST_URI'] = (string) $uri;
    }

    /**
     * @return string
     */
    public function getRequestUri() {
        return isset($this->server['REQUEST_URI']) ? $this->server['REQUEST_URI'] : '';
    }

    /**
     * @return string
     */
    public function getAppRequest() {

        if($this->isCli()) {
            $args = $this->getCliArgs();
            return isset($args[0]) ? $args[0] : null;
        } else {
            $requestString = $this->getDomain() . $this->getRequestUri();
            return \str_ireplace($this->getAppRootUri(), '', $requestString);
        }

    }

    public function getAppRootUri() {
        return $this->getDomain() . $this->appWebRoot;
    }

    public function setCookie($cookie) {
        $this->cookie = $cookie;
    }

    public function getCookie() {
        return $this->cookie;
    }

    public function setEnv($env) {
        $this->env = $env;
    }

    public function getEnv() {
        return $this->env;
    }

    public function setFiles($files) {
        $this->files = $files;
    }

    public function getFiles() {
        return $this->files;
    }

    public function setGet($get) {
        $this->get = $get;
    }

    public function getGet() {
        return $this->get;
    }

    public function setPost($post) {
        $this->post = $post;
    }

    public function getPost() {
        return $this->post;
    }

    public function setServer($server) {
        $this->server = $server;
    }

    public function getServer() {
        return $this->server;
    }

    public function getMethod() {
        return $this->server['REQUEST_METHOD'];
    }

    /**
     * @return bool
     */
    public function isGet() {
        return 'GET' == $this->getMethod();
    }

    /**
     * @return bool
     */
    public function isPost() {
        return 'POST' == $this->getMethod();
    }

    /**
     * @return bool
     */
    public function isPut() {
        return 'PUT' == $this->getMethod();
    }

    /**
     * @return bool
     */
    public function isDelete() {
        return 'DELETE' == $this->getMethod();
    }

    /**
     * @return bool
     */
    public function isHead() {
        return 'HEAD' == $this->getMethod();
    }

    public function getQueryString() {
        return $this->server['QUERY_STRING'];
    }

    public function getQuery() {
        return $this->getGet();
    }

    public function getUserAgent() {
        return (isset($this->server['HTTP_USER_AGENT']))
               ? $this->server['HTTP_USER_AGENT']
               : null;
    }

    public function setUserAgent($agent) {
        $this->server['HTTP_USER_AGENT'] = (string) $agent;
    }

}