<?php

/**
 * Utilities_Format
 * @version $Id
 * @author Jens Lojek <code@code-in-design.de>
 * @copyright All rights reserved, (c) DubLi.com 2010
 * @package dubli.classes.UTils
 */

class Utils_Format extends Utils
{
    /**
     *
     * Key for the minimum length property in the field format configuration array
     * @var int
     */
    const _MIN_LENGTH = 'minLength';

    /**
     *
     * Key for the name property in a field format configuration array
     * @var int
     */
    const _NAME = 'name';

    /**
     *
     * Key for the field name property in a field format configuration array
     * @var int
     */
    const _FIELD_NAME = 'fieldName';

    /**
     *
     * Key for the obligatory property in a field format configuration array
     * @var string
     */
    const _OBLIGATORY = 'obligatory';

    /**
     *
     * Key for the not set type error in a field format configuration array
     * @var String
     */
    const _ERROR_NOT_SET = 'errorInputNotSet';

    /**
     *
     * Key for the "too short" error in a field format configuration array
     * @var String
     */
    const _ERROR_TOO_SHORT = 'errorInputTooShort';

    /**
     *
     * Key for the "invalid" error in a field format configuration array
     * @var String
     */
    const _ERROR_INVALID = 'errorInvalid';

    /**
     *
     * Key for the "not secure enough password" error in a field format configuration array
     * @var String
     */
    const _ERROR_INSECURE_PASSWORD = 'passNotSecure';

    /**
     *
     * Key for the "expired" error in a field format configuration array
     * @var String
     */
    const _ERROR_EXPIRED = 'expired';

    /**
     *
     * Key for the "not secure enough password" error in a field format configuration array
     * @var String
     */
    const _ERROR_MISMATCHING_PASSWORD_CONFIRMATION = 'mismatchingPassConfirm';

    /**
     * Configuration for usernames field input format
     * @var array
     */
    public static $FORMAT_USERNAMES = array( self::_OBLIGATORY => true,
    self::_MIN_LENGTH => 2,
    self::_NAME => 'username',
    self::_FIELD_NAME => 'f_username',
    self::_ERROR_TOO_SHORT => 'Please choose a username with a minimum of 5 characters.',
    self::_ERROR_INVALID => 'Your username contains invalid characters.',
    self::_ERROR_NOT_SET => 'Please, enter a username');

    /**
     * Configuration for first names field input format
     * @var array
     */
    public static $FORMAT_FIRST_NAMES = array( self::_OBLIGATORY => true,
    self::_MIN_LENGTH => 2,
    self::_NAME => 'first name',
    self::_FIELD_NAME => 'f_firstname',
    self::_ERROR_INVALID => 'Your name contains invalid characters.',
    self::_ERROR_TOO_SHORT => 'Please type in your complete name. Your name must be longer than 2 characters',
    self::_ERROR_NOT_SET => 'Please, enter your name' );

    /**
     * Configuration for last names field input format
     * @var array
     */
    public static $FORMAT_LAST_NAME = array(  self::_OBLIGATORY => true,
    self::_MIN_LENGTH => 2,
    self::_NAME => 'last name',
    self::_FIELD_NAME => 'f_lastname',
    self::_ERROR_INVALID => 'Your last name contains invalid characters.',
    self::_ERROR_TOO_SHORT => 'Please type in your complete surname. Your name must be longer than 2 characters.',
    self::_ERROR_NOT_SET => 'Please, enter your last name' );

    /**
     * Configuration for countries field input format
     * @var array
     */
    public static $FORMAT_COUNTRIES = array( self::_FIELD_NAME => 'f_countries_id');

    /**
     * Configuration for favourite language field input format
     * @var array
     */
    public static $FORMAT_FAV_LANGUAGE = array(	self::_FIELD_NAME => 'f_favlanguage');

    /**
     * Configuration for day field input format
     * @var array
     */
    public static $FORMAT_DATE_DAY = array(	    self::_FIELD_NAME => 'f_dob_day');

    /**
     * Configuration for month field input format
     * @var array
     */
    public static $FORMAT_DATE_MONTH = array(self::_FIELD_NAME => 'f_dob_month');

    /**
     * Configuration for year field input format
     * @var array
     */
    public static $FORMAT_DATE_YEAR = array(self::_FIELD_NAME => 'f_dob_year');

    /**
     * Configuration for gtc field input format
     * @var array
     */
    public static $FORMAT_GTC = array(self::_FIELD_NAME => 'f_gtc',
    self::_ERROR_NOT_SET => 'You have to read and accept our General Terms & Conditions before you can register.');

    /**
     * Configuration for newsletter field input format
     * @var array
     */
    public static $FORMAT_NEWSLETTER = array(self::_FIELD_NAME => 'f_newsletter');

    /**
     * Configuration for affiliate ID input format
     * @var array
     */
    public static $FORMAT_AFFILIATE_ID = array( self::_FIELD_NAME => 'affiliate_id',
    self::_NAME => 'affiliateId');

    /**
     * Configuration for timezone input format
     * @var array
     */
    public static $FORMAT_EDITED_TIMEZONE = array(self::_FIELD_NAME =>    'edited_user_timezone');

    /**
     * Configuration for coucher field input format
     * @var array
     */
    public static $FORMAT_VOUCHER = array(self::_FIELD_NAME     =>    'f_voucher',
    self::_ERROR_EXPIRED => 'Sorry, the coupon code you entered, is expired. Please, insert a valid code or leave the field blank.');

    /**
     * Configuration for gender field input format
     * @var array
     */
    public static $FORMAT_GENDER = array( self::_FIELD_NAME     =>    'f_gender',
    self::_ERROR_NOT_SET => 'Please tell us your gender');

    public static $FORMAT_EMAIL_CONFIRMATION = array(self::_OBLIGATORY => true,
    self::_FIELD_NAME    =>    'f_email_confirm',
    self::_ERROR_NOT_SET => 'Please, enter your e-mail confirmation ("Confirm email" field)');

    public static $FORMAT_EMAIL = array( self::_OBLIGATORY => true,
    self::_FIELD_NAME    =>    'f_email',
    self::_ERROR_INVALID => 'Sorry, your email address cannot be validated. Please check and correct it.',
    self::_ERROR_NOT_SET => 'Please, enter your e-mail');

    public static $FORMAT_PASSWORD = array( self::_OBLIGATORY => true,
    self::_FIELD_NAME    =>    'f_password',
    self::_MIN_LENGTH    =>    5,
    self::_ERROR_INSECURE_PASSWORD => 'Your password is not secure enough. Please use a minimum of 5 characters. (Alphanumeric)',
    self::_ERROR_NOT_SET => 'Please, enter your password',
    self::_ERROR_TOO_SHORT => 'Your password is not secure enough. Please use a minimum of 5 characters. (Alphanumeric)');

    public static $FORMAT_PASSWORD_CONFIRMATION = array( self::_OBLIGATORY => true,
    self::_FIELD_NAME => 'f_password_confirm',
    self::_ERROR_MISMATCHING_PASSWORD_CONFIRMATION => 'Your password confirmation isn\'t correct. Please retype your password',
    self::_ERROR_NOT_SET => 'Please, enter a password confirmation');

    /**
     * Gets the formatting configuration for the field of the specified name
     * @param STRING $fieldName
     * @return Array
     */
    public static function getFormatForField($fieldName)
    {
        foreach(get_class_vars(__CLASS__ ) as $formatConfigVarName => $properties)
        {
            foreach($properties as $propertyKey => $propertyValue)
            {
                if($propertyKey == $fieldName)
                {
                    return $properties;
                }
            }
        }
    }

    /**
     * Gets the specified formatting configuration property
     * @param STRING $propertyName
     * @param STRING $fieldName
     * @return MIXED
     */
    public static function getFormatPropertyForField($fieldName, $propertyName)
    {
        $format = self::getFormatForField($fieldName);
        if(isset($format[$propertyName]))
        {
            return $format[$propertyName];
        }
        else
        {
            return false;
        }
    }

    /**
     *
     * Returns the errors for not set obligatory fields
     * @param $attributes ARRAY
     * @return ARRAY
     */
    public static function getErrorsForNotSetObligatoryFields($attributes)
    {
        $errorMessages = array();
        $obligatoryFields = array();
        foreach(get_class_vars(__CLASS__) as $fieldFormat => $properties)
        {
            if(isset($properties[self::_OBLIGATORY]))
            {
                $obligatoryFields[] = $properties[self::_FIELD_NAME];
            }
        }
        foreach($obligatoryFields as $obligatoryFieldName => $value)
        {
            $format = self::getFormatForField($obligatoryFieldName);
            if(!isset($attributes[$obligatoryFieldName]))
            {
                $errorMessages[] = $format[self::_ERROR_NOT_SET];
            }
        }
        return $errorMessages;
    }

    public static function passwordConfirmationMatch($password, $confirmation)
    {
        return $password == $confirmation;
    }

    /**
     * convert a form input value to float value
     * @param MIXED $no
     * @return FLOAT
     */
    public static function htmlnumber2float( $no )
    {
        $no = str_replace(',', '.', $no);
        if( substr_count($no, '.') > 1 )
            $no = str_replace('.', '', substr($no, 0, strrpos($no, '.'))) . '.' . substr($no, strrpos($no, '.')+1);

        return (float)$no;
    }

    /**
     * replace all \r\n from str, format to specialchars
     * @param STRING $string
     * @return STRING
     */
    static public function del_rn( $string='' )
    {
        $string = htmlspecialchars($string);
        $string = str_replace("\r", "", $string);
        $string = str_replace("\n", "", $string);
        $string = trim($string);

        return $string;
    }

    /**
     * replace all \r\n from str
     * @param STRING $string
     * @return STRING
     */
    static public function cleanRn( $string='' )
    {
        $string = str_replace("\r\n", "", $string);
        $string = str_replace("\r", "", $string);
        $string = str_replace("\n", "", $string);
        $string = trim($string);

        return $string;
    }

    /**
     * Prepare a users input string (GET,POST).
     * Escaping slashes and truncate whitespaces
     * @param MIXED $string	(ARRAY or STRING)
     * @return MIXED
     */
    static public function prepareInput( $string )
    {
        if (is_string($string))
        {
            return trim(stripslashes($string));
        }
        elseif (is_array($string))
        {
            reset($string);
            while ((list($key, $value) = each($string))!= false)
            {
                $string[$key] = self::prepareInput($value);
            }
            return $string;
        }
        else
        {
            return $string;
        }
    }

    /**
     * roundPrecision function fixes PHP behavior of rounding without taking into consideration decimal places after the requested number of places...
     * @param float $number
     * @param int $decimalPlaces    Def:2
     * @param int $precision    Def:4
     */
    public static function roundPrecision($number, $decimalPlaces = 2, $precision = 4)
    {
        $decimalPlaces =(int)$decimalPlaces;
        $precision = (int)$precision;
        $number = (float)$number;

        for($p = $decimalPlaces+$precision; $p >= $decimalPlaces; $p--) {
            $number = round($number,$p);
        }

         return $number;
    }

    /**
     * Format the display price equal to old displayPrice() Function
     *
     * @access public
     * @static
     * @param float $itemPrice
     * @param string $targetCurrency default: null
     * @param bool $rounded default: true
     * @param bool $asFloat default: false
     * @param int $auctionSpecialType default: null
     * @return string
     */
    public static function displayPrice($itemPrice, $targetCurrency = null, $rounded = true, $asFloat = false, $auctionSpecialType = null)
    {
        global $config;

        if( (int)$itemPrice == 99999999 || $auctionSpecialType == 3)
        {
            return '???';
        }

        $convertedPrice = $itemPrice = number_format((float)$itemPrice, 4, '.', '');

        if( null === $targetCurrency )
        {
            $targetCurrency = App::$CURRENCY;
        }

        // readout currency list
        $currencies =& $_SESSION['currencies'];

        //calc price to target currency
        if( $targetCurrency != App::$CURRENCY )
        {
            $itemPrice = $itemPrice * $currencies[$targetCurrency]['value'];
        }

        if( $rounded )
        {
            $itemPrice = round($itemPrice, $currencies[$targetCurrency]['decimal_places']);
        }

        if( $asFloat )
        {
            $convertedPrice = $itemPrice;
        }
        else
        {
            $formatedPrice = number_format($itemPrice, $currencies[$targetCurrency]['decimal_places'], $currencies[$targetCurrency]['decimal_point'], $currencies[$targetCurrency]['thousands_point']);
            $convertedPrice = $currencies[$targetCurrency]['symbol_left'] . $formatedPrice .' '. $currencies[$targetCurrency]['symbol_right'];
        }

        return trim($convertedPrice);
    }

    /**
     * Format given FLOAT value into users localized price format for the selected target currency
     * If target currency is left out the User's default currency is assumed
     * @param float $itemPrice
     * @param string $targetCurrency
     * @param int $decimalPlaces
     */
    public static function formatPrice($itemPrice, $targetCurrency=null, $decimalPlaces=null)
    {
        global $config;

        if( null === $targetCurrency ) {
            $targetCurrency = App::$CURRENCY;
        }
        // readout currency list
        $currencies =& $_SESSION['currencies'];

        $formatedPrice = number_format((float)$itemPrice, $decimalPlaces!==null?(int)$decimalPlaces:$currencies[$targetCurrency]['decimal_places'], $currencies[$targetCurrency]['decimal_point'], $currencies[$targetCurrency]['thousands_point']);
        $convertedPrice = $currencies[$targetCurrency]['symbol_left'] . $formatedPrice .' '. $currencies[$targetCurrency]['symbol_right'];

        return trim($convertedPrice);
    }

    /**
     * Format given FLOAT value into users localized price format.
     * Without Currency Symbols/Strings
     *
     * @access public
     * @static
     * @param float $itemPrice
     * @param string $targetCurrency default: null
     * @param bool $rounded default: true
     * @param bool $asFloat default: false
     * @param int $auctionSpecialType default: null
     * @return string
     */
    public static function displayPriceDigits($itemPrice, $rounded = true, $asFloat = false)
    {
        global $config;

        $convertedPrice = $itemPrice = number_format((float)$itemPrice, 4, '.', '');

        $trgtCurrency = $config['app_currency'];
        // readout currency list
        $currencies =& $_SESSION['currencies'];

        if( $rounded )
        {
            $itemPrice = round($itemPrice, $currencies[$trgtCurrency]['decimal_places']);
        }

        if( $asFloat )
        {
            $convertedPrice = $itemPrice;
        }
        else
        {
            $convertedPrice = number_format($itemPrice, $currencies[$trgtCurrency]['decimal_places'], $currencies[$trgtCurrency]['decimal_point'], $currencies[$trgtCurrency]['thousands_point']);
        }

        return trim($convertedPrice);
    }

    /**
     * Returns true if $string is valid UTF-8 and false otherwise.
     *
     * @since        1.14
     * @param [mixed] $string     string to be tested
     * @return BOOLEAN
     */
    static public function is_utf8($string)
    {
        // From http://w3.org/International/questions/qa-forms-utf-8.html
        return preg_match('%^(?:
        	[\x09\x0A\x0D\x20-\x7E]            # ASCII
        	| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        	|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        	| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        	|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        	|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        	| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        	|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        	)*$%xs', $string);
    }

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
     * Removing all Tilde chars from data array
     *
     * @access public
     * @static
     * @param object $data
     * @return array
     */
    public static function &removeTilde($data)
    {
        foreach($data as $key => &$value)
        {
            if(strpos($value, '~') !== false)
            {
                $value = str_replace('~', '', $value);
            }

            if($value == null || $value == 'NULL')
            {
                $value = '';
            }
        }
        return $data;
    }

    /**
     * Replacing ISO chars with UTF8 chars
     *
     * @access public
     * @static
     * @param string $string
     * @return string
     */
    public static function replaceISO($string)
    {
        $searchList = array('�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�');

        foreach($searchList as &$char){
            $string = str_replace($char, utf8_encode($char), $string);
        }

        return $string;
    }

    /**
     * Generate a SEOptimized URI Title (String)
     * @param string $string
     * @return string
     */
    static public function seoString( $string='' )
    {
        $repl_search = array( '?', 'ß' , 'Ö' ,'Ä' , 'Ü' , 'ö' , 'ä' , 'ü', 'ó', 'ò', 'ô', 'á', 'é', 'è', 'ê', 'û', 'í', 'ì', ' ', ".", "'", '"', '#', '&', '/', ',',';', ')','(', '-', '<', '>');
        $repl_replace = array( '', 'ss', 'Oe','Ae', 'Ue', 'oe', 'ae', 'ue','o', 'o', 'o', 'a', 'e', 'e', 'e', 'ue','i', 'i', '_', "" ,  "", '' , '' , '_', '_', '_','_', '' , '', '_', '' , '');
        $string = str_replace($repl_search, $repl_replace, $string);

        //$string = preg_replace('/[^a-zA-Z0-9\-\_]+/', '', $string); //truncate all other chars we dont want in a SEO string
        $string = preg_replace('/[^a-zA-Z0-9\-\_\p{L}]+/u', '', $string); // accept unicode (cyrillic and others)

        while (strstr($string, '__')) $string = str_replace('__', '_', $string);
        $string = str_replace('_', '-', $string);
        $string = trim($string, '-');
        $string = urlencode($string);

        return $string;
    }
}
