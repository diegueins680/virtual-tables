<?php
/**
 * Utilities_URI
 * @version $Id
 * @author Jens Lojek <code@code-in-design.de>
 * @copyright All rights reserved, (c) DubLi.com 2010,2011
 * @package dubli.classes.Utils
 */

class Utils_URI extends Utils
{

    /**
     * Syntax validation of an URL string
     * @param string $url
     * @return bool
     */
    static public function isUrl( $url='' )
    {
        $url = trim($url);

        if( preg_match('/(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/', $url) )
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns hostname part of current A_Engine-Hostname or from given string/url.
     * @param string $url
     * @return string
     */
    static public function getHostName( $url='' )
    {
        $url = trim($url);
        $hostname = empty($url) ? App::$Engines[App::$ENGINE][1] : $url;

        if( strpos($hostname, 'http') !== false ) $hostname = parse_url($hostname, PHP_URL_HOST);
        $hostname = trim($hostname, '/');

        return $hostname;
    }

    /**
     * Greps the base domain name out of a Host/url.
     * "http://www.dubli.com/" would become "dubli.com"
     *
     * @access static private
     * @param string $url   Optional; Def: from _SERVER[HTTP_HOST]
     * @return string
     */
    static public function getDomain( $url=null )
    {
        $url = empty($url) ? $_SERVER['HTTP_HOST'] : trim($url);
        $domain = parse_url($url, PHP_URL_HOST);
        if( empty($domain) ) {
            // requested $url is not a full url its probably only a hostname like www.dubli.com
            $domain = basename($url);
        }

        $domain = (substr_count($domain,'.') > 1 ) ? substr($domain, strpos($domain, '.')) : $domain;
        $domain = trim($domain, '.');

        return $domain;
    }

    /**
     * Returns cookie domain. By default a TLD.
     * Calculates the root domain of current Host, optional full domain.
     *
     * @param bool $fullDomain  Def:false, return full domain
     * @return string
     */
    public static function getCookieDomain( $fullDomain = false )
    {
        $cookieDomain = '';

        if( true === $fullDomain ) {
            $cookieDomain .= '.' . $_SERVER['HTTP_HOST'];
        } else {
            $cookieDomain = self::getDomain($_SERVER['HTTP_HOST']);
        }

        return $cookieDomain;
    }

    /**
     * creating a canonical url from an existing url
     * @param string $url
     * @return string
     */
    static public function canonical( $url='' )
    {
        return substr($url, 0, strpos($url, '?'));
    }

}
