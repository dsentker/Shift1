<?php
namespace Shift1\Core\Request;

/**
 * @TODO
 * Create an String2Array Finder to access
 * array params from a string, eg. server.http_user_agent
 * to get rid of the getter/setter amount
 */
abstract class AbstractRequest implements RequestInterface {

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

    /**
     * @param string $appWebRoot The relative uri where this app begins
     */
    public function __construct($appWebRoot = '/') {
        $this->appWebRoot = $appWebRoot;
    }

    /**
     * @return void
     */
    public function __clone() {

    }

    /**
     * @static
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
        return $req;
    }

    /**
     * @return bool If the Request was sent via XMLHttpRequest
     */
    public function isXmlHttp() {
         return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    /**
     * @return string
     */
    public function getDomain() {
        return $this->server['HTTP_HOST'];
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
        return $this->server['REQUEST_URI'];
    }

    /**
     * @return string
     */
    public function getAppRequestUri() {
        $requestString = $this->getDomain() . $this->getRequestUri();
        return \str_ireplace($this->getAppRootUri(), '', $requestString);
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

    public function isGET() {
        return $this->getMethod() == 'GET';
    }

    public function isPOST() {
        return $this->getMethod() == 'POST';
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