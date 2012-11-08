<?php

/**
 * Merchant - DubLi Merchant/Partner Setup
 *
 * @author Jens Lojek <code@code-in-design.de>
 * @copyright All rights reserved, (c) DubLi.com 2011
 * @package dubli.classes
 * @since 2011-10-18
 *
 * @uses App
 * @uses Utils
 * @uses MySQL
 * @uses Partner
 * @uses Affiliation
 */

class Merchant
{

    private function __construct(){}

    /**
     * Initializing/Bootstrap of getting merchant of current session and set all merchant related env data.
     *
     * @param int $merchantId   Def:null; Optional
     * @return bool
     */
    static public function boot( $merchantId = null )
    {
        $GLOBALS['VARDUMP'][] = __METHOD__;

        global $Fusebox, $_GET;

        // decide by API Call (Prio:1)
        if( !empty($merchantId) ) {
            $GLOBALS['VARDUMP'][] = 'decide by :api';
            $merchantId = self::lookupMerchantId( $merchantId );
            if( $merchantId !== false ) return self::init($merchantId);
        }

        // decide by URL-Param (Prio:2)
        if( isset($_GET['mID']) ) {
            $GLOBALS['VARDUMP'][] = 'decide by :mID';
            $merchantId = self::lookupMerchantId( (int)$_GET['mID'] );
            if( $merchantId !== false ) return self::init($merchantId);
        }

        //decide by HOST (Prio:3)
        $GLOBALS['VARDUMP'][] = 'decide by :host';
        $merchantId = self::lookupMerchantId($_SERVER['HTTP_HOST'], 'host');
        if( $merchantId !== false ) return self::init($merchantId);

        //decide by Reset
        $GLOBALS['VARDUMP'][] = 'decide by :reset';
        $merchantId = self::lookupMerchantId( '0', 'reset' );
        if( $merchantId !== false ) return self::init($merchantId);

        // decide by COOKIE (Prio:5)
        if( isset($_SESSION['mID']) ) {
            $GLOBALS['VARDUMP'][] = 'decide by :Session';
            $merchantId = self::lookupMerchantId($_SESSION['mID']);
            if( $merchantId !== false ) return self::init($merchantId);
        }

        // decide by SESSION/COOKIE (Prio:5)
//         if( !Affiliation::isEmpty('PPmerchantId') ) {
//             $GLOBALS['VARDUMP'][] = 'decide by :Session/COOKIE[PPmerchantId]';
//             $merchantId = self::lookupMerchantId( Affiliation::get('PPmerchantId') );
//             if( $merchantId !== false ) return self::init($merchantId);
//         }

        // DEFAULT merchant (Prio: __last__)
        $GLOBALS['VARDUMP'][] = 'decide by :default';
        return self::init(App::$DEFAULT_MERCHANTID);
    }

    /**
     * Initialize Merchant/Partner data/settings
     *
     * @param int $merchantId
     * @return bool
     */
    static public function init( $merchantId )
    {
        $Partner = Partner::getInstance((int)$merchantId);
        $Partner->getPartnerSettings();
        App::$merchant = $Partner;
        App::$MERCHANTID = $Partner->getId();

        return self::setConfigEnvValues();
    }



    /**
     * Lookup in database to find merchant/partner by requested filter params.
     * Returns the PartnerID in case it found it, otherwise boolean false.
     *
     * @param string $key
     * @param string $field Def:partner_id; Optional
     * @return int|FALSE
     */
    static public function lookupMerchantId( $key, $field='partner_id' )
    {
        $GLOBALS['VARDUMP'][] = __METHOD__ . '('.$key.', '.$field.')';

        if( is_null($key) || empty($key) ) return false;
        $field = trim($field);

        // exclude search by host for main domain
        $domain = 'n/a';
        if( $field == 'host' ) {
            $domain = Utils_URI::getDomain($key);
            $GLOBALS['VARDUMP'][] = 'Host Value = ' . $domain;
        }

        $stmt = "SELECT `partner_id`
            FROM `".App::$DBTABLES['partner']."` p
            LEFT JOIN `".App::$DBTABLES['partner__settings']."` ps USING(`partner_id`)
            WHERE ";

        switch( $field )
        {
            case 'id':
            case 'partner_id':
            default:
                $stmt .= " p.`partner_id` = ".(int)$key." ";
            break;
            case 'host':
                $stmt .= " ps.`domainname` = '".$domain."' ";
            break;
            case 'reset':
                return App::$DEFAULT_MERCHANTID;
            break;
        }

        if( App::$DAEMONCALL != true && (!defined('ADMCALL') || ADMCALL != true) ) $stmt .= " AND p.`is_active` = 1 LIMIT 1";

        $res = MySQL::fetchAssoc($stmt, MySQL::$MYSQL_CON_R);

        if( false !== $res && is_array($res) && !empty($res[0]) && !empty($res[0]['partner_id']) ) {
            return (int)$res[0]['partner_id'];
        }

        return false;
    }

}
