<?php

/**
 * Google Tracking (Analytics and AdWords) - DubLi Auction Platform
 *
 * @version $Id
 * @author Jens Lojek <code@code-in-design.de>
 * @copyright All rights reserved, (c) DubLi.com 2012
 * @package dubli.tracking
 *
 * @uses App
 */

class GoogleTracking
{

    /**
     * Case: Common Visitor on website
     * @var int
     */
    const AD_CASE_VISITOR = 10;

    /**
     * Case: Common Visitor Signs Up
     * @var int
     */
    const AD_CASE_VISITORSIGNUP = 11;

    /**
     * Case: Registered Visitor purchases
     * @var int
     */
    const AD_CASE_VISITORPURCHASE = 12;


    /**
     * Case: TV Commercial Campaign Visitor on vip-website
     * @var int
     */
    const AD_CASE_TVCAMPAIGN_VISIT = 20;

    /**
     * Case: TV Commercial Campaign Visitor converted (register/purchase)
     * @var int
     */
    const AD_CASE_TVCAMPAIGN_REGISTER = 21;


    /**
     * Google Analytics Profile Id
     * @var string
     */
    private static $gaProfileId;

    /**
     * Google AdService/AdWords tracking values.
     * @var array
     */
    public static $conversionAttribs = array();


    private function __construct(){}

    /**
     * Returns Google Analytics Profile Id.
     * @access public
     * @static
     * @return string
     */
    public static function getGAProfileId()
    {
        global $config;

        self::$gaProfileId =& $config['googleAnalyticsProfId'];

        return self::$gaProfileId;
    }

    /**
     * Returns the GA JA file source code.
     * "... .google-analytics.com/ga.js ..."
     *
     * @access static
     * @public
     * @return string
     */
    public static function gaJSsrcCode()
    {
        $str = "\n(function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();";

        $str = str_replace('  ', '', $str);

        return $str;
    }

    /**
     * Cleans Session values for Google Tracking to prevent duplicate tracking.
     * @return void
     */
    public static function gaCleanSession()
    {
        if( isset($_SESSION['google_trans']) ) {
            $_SESSION['google_trans']=null;
            unset($_SESSION['google_trans']);
        }
        if( isset($_SESSION['google_item']) ) {
            $_SESSION['google_item']=null;
            unset($_SESSION['google_item']);
        }
        if( isset($_SESSION['google_trackPageview']) ) {
            $_SESSION['google_trackPageview']=null;
            unset($_SESSION['google_trackPageview']);
        }
    }

    /**
     * Set Google AdServices/AdWord conversion tracking code.
     * @param int $case Value of consts AD_CASE_???
     * @static
     * @access public
     * @return void
     */
    public static function setConversionCode( $case = null )
    {
        self::setAdCode($case);
        $code = self::prepareConversionCode();

        $_SESSION['gaConversionCode'] = $code;
    }

    /**
     * Echo Google AdServices/AdWord conversion tracking code.
     * @param int $case Value of consts AD_CASE_???
     * @static
     * @access public
     * @return void
     */
    public static function getConversionCode( $case = null )
    {
        if( empty($case)
            && isset($_SESSION['gaConversionCode'])
            && !empty($_SESSION['gaConversionCode']) )
        {
            // We set the conversion code already earlier in PHP process, now we just want to put it into the website html code
            $str = $_SESSION['gaConversionCode'];

            $_SESSION['gaConversionCode'] = null;
            unset($_SESSION['gaConversionCode']);

            echo $str;
            return;
        }

        /*
         * Get Conversion Code on the fly
         */
        self::setAdCode($case);
        $str = self::prepareConversionCode();

        echo $str;
    }

    /**
     * Prepare ga conversion tracking code (JS+HTML) and store it into session.
     * @access static
     * @private
     * @return string
     */
    private static function prepareConversionCode()
    {
        if( empty(self::$conversionAttribs) )
        {
            // On some cases, we do not track, therefore we set the attribs empty
            return  '';
        }

        $proto = ( App::$RequestMode == 'SSL' ) ? 'https://' : 'http://';

        $str = "\n<!-- Google Code for Visitors Remarketing List -->
            <script type=\"text/javascript\">
            /* <![CDATA[ */";

        $str .= "
            var google_conversion_id = " . self::$conversionAttribs['conversion_id'] . ";
            var google_conversion_language = '" . self::$conversionAttribs['conversion_lang'] . "';
            var google_conversion_format = '" . self::$conversionAttribs['conversion_format'] . "';
            var google_conversion_color = 'ffffff';
            var google_conversion_label = '" . self::$conversionAttribs['conversion_label'] . "';
            var google_conversion_value = " . self::$conversionAttribs['conversion_value'] . ";
            ";

        $str .= "/* ]]> */
            </script>
            <script type=\"text/javascript\" src=\"".$proto."www.googleadservices.com/pagead/conversion.js\"></script>
            <noscript>
            <div style=\"display:inline;\">
            <img height=\"1\" width=\"1\" style=\"border-style:none;\" alt=\"\" src=\"".$proto."www.googleadservices.com/pagead/conversion/".self::$conversionAttribs['conversion_id']."/?label=".self::$conversionAttribs['conversion_label']."&amp;guid=ON&amp;script=0\"/>
            </div>
            </noscript>\n";

        $str = str_replace('  ', '', $str);

        return $str;
    }

    /**
     * Sets Google AdService conversion values (JS values) for individual tracked cases.
     *
     * @param int $case Value of consts AD_CASE_???
     * @static
     * @return void
     */
    private static function setAdCode( $case = null )
    {
        if( App::$LANGCODE == 'es' )
        {
            // "es" only
            switch( $case )
            {
                case self::AD_CASE_VISITOR:
                default:
                    self::$conversionAttribs = array(
                        'conversion_id' => '1008432699',
                        'conversion_lang' => 'es',
                        'conversion_format' => '3',
                        'conversion_label' => 'b1laCOWu3AIQu-zt4AM',
                        'conversion_value' => '0',
                    );

                    // Default visitor tracking not necessary in logged in area
                    if( isset($_SESSION['users_id']) && (int)$_SESSION['users_id'] > 0 ) {
                        self::$conversionAttribs = array();
                    }
                break;

                case self::AD_CASE_VISITORSIGNUP:
                    self::$conversionAttribs = array(
                        'conversion_id' => '1008432699',
                        'conversion_lang' => 'es',
                        'conversion_format' => '2',
                        'conversion_label' => 'TjbiCP2r3AIQu-zt4AM',
                        'conversion_value' => '0',
                    );
                break;
                case self::AD_CASE_VISITORPURCHASE:
                    self::$conversionAttribs = array(
                        'conversion_id' => '1008432699',
                        'conversion_lang' => 'es',
                        'conversion_format' => '2',
                        'conversion_label' => 'Dc6nCPWs3AIQu-zt4AM',
                        'conversion_value' => '0',
                    );
                break;
                case self::AD_CASE_TVCAMPAIGN_VISIT:
                    self::$conversionAttribs = array(
                        'conversion_id' => '1008432699',
                        'conversion_lang' => 'es',
                        'conversion_format' => '3',
                        'conversion_label' => 'fMSoCN2v3AIQu-zt4AM',
                        'conversion_value' => '0',
                    );
                break;
                case self::AD_CASE_TVCAMPAIGN_REGISTER:
                    self::$conversionAttribs = array(
                        'conversion_id' => '1008432699',
                        'conversion_lang' => 'es',
                        'conversion_format' => '2',
                        'conversion_label' => 'vMdnCO2t3AIQu-zt4AM',
                        'conversion_value' => '0',
                    );
                break;
            }

        } else {
            // "en" default
            switch( $case )
            {
                case self::AD_CASE_VISITOR:
                default:
                    self::$conversionAttribs = array(
                        'conversion_id' => '1008432699',
                        'conversion_lang' => 'en',
                        'conversion_format' => '3',
                        'conversion_label' => 'CZOfCM2y2gIQu-zt4AM',
                        'conversion_value' => '0',
                    );

                    // Default visitor tracking not necessary in logged in area
                    if( isset($_SESSION['users_id']) && (int)$_SESSION['users_id'] > 0 ) {
                        self::$conversionAttribs = array();
                    }
                break;

                case self::AD_CASE_VISITORSIGNUP:
                    self::$conversionAttribs = array(
                        'conversion_id' => '1008432699',
                        'conversion_lang' => 'en',
                        'conversion_format' => '2',
                        'conversion_label' => 'IOKbCJ2S2wIQu-zt4AM',
                        'conversion_value' => '0',
                    );
                break;
                case self::AD_CASE_VISITORPURCHASE:
                    self::$conversionAttribs = array(
                        'conversion_id' => '1008432699',
                        'conversion_lang' => 'en',
                        'conversion_format' => '2',
                        'conversion_label' => 'KvwqCJWT2wIQu-zt4AM',
                        'conversion_value' => '0',
                    );
                break;
                case self::AD_CASE_TVCAMPAIGN_VISIT:
                    self::$conversionAttribs = array(
                        'conversion_id' => '1008432699',
                        'conversion_lang' => 'en',
                        'conversion_format' => '3',
                        'conversion_label' => '55qrCNWx2gIQu-zt4AM',
                        'conversion_value' => '0',
                    );
                break;
                case self::AD_CASE_TVCAMPAIGN_REGISTER:
                    self::$conversionAttribs = array(
                        'conversion_id' => '1008432699',
                        'conversion_lang' => 'en',
                        'conversion_format' => '2',
                        'conversion_label' => 'SMGlCI2U2wIQu-zt4AM',
                        'conversion_value' => '0',
                    );
                break;
            }

        }
    }

    /**
     * Return optional tracking JS code for e-Commerce tracking.
     * @access private
     * @static
     * @return string
     */
    private static function getEcommerce()
    {
        $str = "\n";

        if( empty($_SESSION['google_trans']) || empty($_SESSION['google_item']) ) {
            return $str;
        }

        // eCommerce Transaction
        $str .= "_gaq.push(['_addTrans',";
        $str .= "'" . $_SESSION['google_trans'][0] . "',"; // Order ID
        $str .= "'" . $_SESSION['google_trans'][1] . "',"; // Affiliation
        $str .= "'" . $_SESSION['google_trans'][2] . "',"; // Total
        $str .= "'" . $_SESSION['google_trans'][3] . "',"; // Tax
        $str .= "'" . $_SESSION['google_trans'][4] . "',"; // Shipping
        $str .= "'" . $_SESSION['google_trans'][5] . "',"; // City
        $str .= "'" . $_SESSION['google_trans'][6] . "',"; // State
        $str .= "'" . $_SESSION['google_trans'][7] . "'"; // Country
        $str .= "]);\n";

        if( is_array($_SESSION['google_item']) ) {
            // Transaction Items
            foreach($_SESSION['google_item'] as $item)
            {
                $str .= "_gaq.push(['_addItem', '".$item[0]."', '".$item[1]."', '".$item[2]."', '".$item[3]."', '".$item[4]."', '".$item[5]."']);\n";
            }
        } else {
            $str .= "_gaq.push(['_addItem',";
            $str .= "'" . $_SESSION['google_item'][0] . "',";
            $str .= "'" . $_SESSION['google_item'][1] . "',";
            $str .= "'" . $_SESSION['google_item'][2] . "',";
            $str .= "'" . $_SESSION['google_item'][3] . "',";
            $str .= "'" . $_SESSION['google_item'][4] . "',";
            $str .= "'" . $_SESSION['google_item'][5] . "'";
            $str .= "]);\n";
        }

        $str .= "_gaq.push(['_trackTrans']);\n";

        return $str;
    }


}
