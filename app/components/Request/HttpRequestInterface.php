<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 23/09/2018 08:45
 */

namespace Phact\Request;


use Phact\Exceptions\InvalidConfigException;
use Phact\Helpers\Collection;

/**
 * Http request management
 *
 * Interface HttpRequestInterface
 * @package Phact\Request
 */
interface HttpRequestInterface
{
    /**
     * Get CSRF Token
     * @return null|string
     */
    public function getCsrfToken();

    /**
     * Get request method (POST,GET,PUT, etc)
     * @return string
     */
    public function getMethod();

    /**
     * Returns whether this is a GET request.
     * @return boolean whether this is a GET request.
     */
    public function getIsGet();

    /**
     * Returns whether this is an OPTIONS request.
     * @return boolean whether this is a OPTIONS request.
     */
    public function getIsOptions();

    /**
     * Returns whether this is a HEAD request.
     * @return boolean whether this is a HEAD request.
     */
    public function getIsHead();

    /**
     * Returns whether this is a POST request.
     * @return boolean whether this is a POST request.
     */
    public function getIsPost();

    /**
     * Returns whether this is a DELETE request.
     * @return boolean whether this is a DELETE request.
     */
    public function getIsDelete();

    /**
     * Returns whether this is a PUT request.
     * @return boolean whether this is a PUT request.
     */
    public function getIsPut();

    /**
     * Returns whether this is a PATCH request.
     * @return boolean whether this is a PATCH request.
     */
    public function getIsPatch();

    /**
     * Returns whether this is an AJAX (XMLHttpRequest) request.
     * @return boolean whether this is an AJAX (XMLHttpRequest) request.
     */
    public function getIsAjax();

    /**
     * Returns whether this is a PJAX request
     * @return boolean whether this is a PJAX request
     */
    public function getIsPjax();

    /**
     * Returns the schema and host part of the current request URL.
     * The returned URL does not have an ending slash.
     * By default this is determined based on the user request information.
     *
     * @return string schema and hostname part (with port number if needed) of the request URL (e.g. `http://www.yiiframework.com`, `http://127.0.0.1:8004`),
     * null if can't be obtained from `$_SERVER` and wasn't set.
     */
    public function getHostInfo();

    /**
     * Sets the fully-qualified host information, (e.g. `http://www.yiiframework.com`, `http://127.0.0.1:8004`)
     * @param $info
     */
    public function setHostInfo($info);

    /**
     * Returns the relative URL of the entry script.
     * The implementation of this method referenced Zend_Controller_Request_Http in Zend Framework.
     * @return string the relative URL of the entry script.
     * @throws InvalidConfigException if unable to determine the entry script URL
     */
    public function getScriptUrl();

    /**
     * Sets the relative URL for the application entry script.
     * This setter is provided in case the entry script URL cannot be determined
     * on certain Web servers.
     * @param string $value the relative URL for the application entry script.
     */
    public function setScriptUrl($value);

    /**
     * Returns the entry script file path.
     * The default implementation will simply return `$_SERVER['SCRIPT_FILENAME']`.
     * @return string the entry script file path
     * @throws InvalidConfigException
     */
    public function getScriptFile();

    /**
     * Returns the currently requested absolute URL.
     * This is a shortcut to the concatenation of [[hostInfo]] and [[url]].
     * @return string the currently requested absolute URL.
     * @throws InvalidConfigException
     */
    public function getAbsoluteUrl();

    /**
     * Returns the currently requested relative URL.
     * This refers to the portion of the URL that is after the [[hostInfo]] part.
     * It includes the [[queryString]] part if any.
     * @return string the currently requested relative URL. Note that the URI returned is URL-encoded.
     * @throws InvalidConfigException if the URL cannot be determined due to unusual server configuration
     */
    public function getUrl();

    /**
     * Returns part of the request URL that is before the question mark.
     * @return string part of the request URL that is before the question mark
     * @throws InvalidConfigException
     */
    public function getPath();

    /**
     * Returns part of the request URL that is after the question mark.
     * @return string part of the request URL that is after the question mark
     */
    public function getQueryString();

    /**
     * Returns array representation part of the request URL that is after the question mark.
     * @return array part of the request URL that is after the question mark
     */
    public function getQueryArray();

    /**
     * Return if the request is sent via secure channel (https).
     * @return boolean if the request is sent via secure channel (https)
     */
    public function getIsSecureConnection();

    /**
     * Returns the server name.
     * @return string server name, null if not available
     */
    public function getServerName();

    /**
     * Returns the server port number.
     * @return integer|null server port number, null if not available
     */
    public function getServerPort();

    /**
     * Returns the URL referrer.
     * @return string|null URL referrer, null if not available
     */
    public function getReferrer();

    /**
     * Returns the user agent.
     * @return string|null user agent, null if not available
     */
    public function getUserAgent();

    /**
     * Returns the user IP address.
     * @return string|null user IP address, null if not available
     */
    public function getUserIP();

    /**
     * Returns the user host name.
     * @return string|null user host name, null if not available
     */
    public function getUserHost();

    /**
     * @return string|null the username sent via HTTP authentication, null if the username is not given
     */
    public function getAuthUser();

    /**
     * @return string|null the password sent via HTTP authentication, null if the password is not given
     */
    public function getAuthPassword();

    /**
     * Returns the port to use for insecure requests.
     * Defaults to 80, or the port specified by the server if the current
     * request is insecure.
     * @return integer port number for insecure requests.
     * @see setPort()
     */
    public function getPort();

    /**
     * Sets the port to use for insecure requests.
     * This setter is provided in case a custom port is necessary for certain
     * server configurations.
     * @param integer $value port number.
     */
    public function setPort($value);

    /**
     * Returns the port to use for secure requests.
     * Defaults to 443, or the port specified by the server if the current
     * request is secure.
     * @return integer port number for secure requests.
     * @see setSecurePort()
     */
    public function getSecurePort();

    /**
     * Sets the port to use for secure requests.
     * This setter is provided in case a custom port is necessary for certain
     * server configurations.
     * @param integer $value port number.
     */
    public function setSecurePort($value);

    /**
     * Returns request content-type
     * The Content-Type header field indicates the MIME type of the data
     * contained in [[getRawBody()]] or, in the case of the HEAD method, the
     * media type that would have been sent had the request been a GET.
     * For the MIME-types the user expects in response, see [[acceptableContentTypes]].
     * @return string request content-type. Null is returned if this information is not available.
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17
     * HTTP 1.1 header field definitions
     */
    public function getContentType();

    /**
     * Redirects the browser to the specified URL.
     * @param string $url URL to be redirected to. Note that when URL is not
     * absolute (not starting with "/") it will be relative to current request URL.
     * @param array $data Data for create url
     * @param integer $statusCode the HTTP status code. Defaults to 302. See {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html}
     * for details about HTTP status code.
     * @throws \Exception
     */
    public function redirect($url, $data = [], $statusCode = 302);

    /**
     * Redirect browser to the current page.
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function refresh();

    /**
     * @return Collection
     */
    public function getGet();

    /**
     * @return Collection
     */
    public function getPost();

    /**
     * @return CookieCollection
     */
    public function getCookie();
}