<?php

/**
 * ObjectFactory class
 *
 * @author  Diego Saa <dsaa@dubli.com>
 */

class ObjectFactory
{
	const SINGLETON = 'SINGLETON';
	
    /**
     * Array index for new (empty) object
     *
     * @staticvar array
     */
    const BASE_OBJECT_ID = 'BASE_OBJECT';
    
    /**
     * Name of the attributes array variable
     * 
     * @var array
     */
    const ATTRIBUTES = 'attributes'; 

    protected static $instances = array(); // arrayClassTypes('className' => arrayInstances(instances));

    /**
     * The Id
     *
     * @var Mixed
     **/
    protected $Id;

    /**
     * Constructor
     *
     * @access private
     * @param int $Id
     **/
    protected function __construct( $Id=null)
    {
        if(!is_null($Id)) {
            $this->setId($Id);
        }
    }

    public function __destruct()
    {
        unset(self::$instances[get_class($this)][$this->getId()]);
        unset($this);
    }

    /** Returns Object primary key id
     * @access public
     * @return int
     **/
    public function getId()
    {
        return $this->Id;
    }

    /**
     * Sets the Id property and instances array key of the object to the value of the given parameter
     *
     * @access public
     * @param MIXED $id
     */
    public function setId($Id)
    {
        $oldId = $this->getId();
        if($oldId !== $Id)
        {
            if(isset(self::$instances[get_class($this)][$oldId]))
            {
                if($oldId != self::BASE_OBJECT_ID)
                {
                    unset(self::$instances[get_class($this)][$oldId]);
                }
            }
            if(!empty($Id))
            {
                self::$instances[get_class($this)][$Id] = $this;
            }
            $this->Id = $Id;
        }
    }

    /**
     * The ObjectFactory method
     *
     * @access public
     * @static
     * @param int|string $Id
     * @param string $class
     * @param array $params
     * @return self|false
     */
    static public function getInstance( $Id=null, $className = null, $params = null)
    {
        if(!isset($className))
        {
            $className = get_called_class();
        }
        if(isset(self::$instances[$className]))
        {
        	if(isset($params[self::SINGLETON]))
        	{
        		if($params[self::SINGLETON])
        		{
        			if(isset(self::$instances[$className]))
        			{
        				if(isset(self::$instances[$className][self::BASE_OBJECT_ID]))
        				{
        					reset(self::$instances[$className]);
        					if(($returnObject = next(self::$instances[$className]))!= false)
        					{
        						return $returnObject;
        					}
        				}
        			}
        		}
        	}
            if(empty($Id))
            {
                $returnObject = self::getNewInstance($className);
            }
            else
            {
                if(array_key_exists($Id, self::$instances[$className]))
                {
                    $returnObject = self::$instances[$className][$Id];
                }
                else
                {
                    $returnObject = self::getNewInstance($className, $Id);
                }
            }
        }
        else
        {
            self::$instances[$className] = [];
            $returnObject = self::getNewInstance($className, $Id);
        }
        if(!empty($params[self::ATTRIBUTES]))
        {
        	$returnObject->setAttributes($params[self::ATTRIBUTES]);
        }
        //var_dump($returnObject);
        return $returnObject;
    }
    
    /**
     * Sets any variable in $params[self::ATTRIBUTES] as a property
     * @param array $attributes
     */
    protected function setAttributes($attributes)
    {
    	foreach($attributes as $attributeName => $attributeValue)
    	{
    		$this->$attributeName = $attributeValue;
    	}
    }

    /**
     * Returns Instance of Object for passed filters
     *
     * @param array $filters
     * @access public
     * @static
     * @return MIXED
     */
    static public function getInstanceBy( array $filters=null, $class = null )
    {
    	if(!isset($class))
    	{
    		$class = get_called_class();
    	}
        return self::instanceInArrayBy($class, $filters);
    }

    /**
     * Returns an instance that passes the specified filters and that has the specified class if it exists in the instances array, or false if it doesn't.
     *
     * @access public
     * @static
     * @param String $class
     * @param Array $filters
     * @return Mixed|Boolean
     */
    static public function instanceInArrayBy( $class, array $filters )
    {
        if(isset(self::$instances[$class]))
        {
            foreach(self::$instances[$class] as $instance)
            {
                foreach($filters as $filter)
                {
                    $thisInstanceOk = true;
                    $attributeName = $filter[0];
                    $attributeValue = $filter[1];
                    if(isset($filter[2]))
                    {
                        $operator = $filter[2];
                    }
                    else
                    {
                        $operator = '===';
                    }
                    if(isset($instance->$attributeName))
                    {
                    	if(!Utils::compare($instance->$attributeName, $attributeValue, $operator))
                    	{
                    		//if this instance doesn't pass the current filter, check next instance
                    		$thisInstanceOk = false;
                    		break;
                    	}                    	
                    }
                    else
                    {
                    	$thisInstanceOk = false;
                    	break;
                    }
                    	
                }
                if($thisInstanceOk)
                {
                    return $instance;
                }
            }
            $params = 
            [
            	self::ATTRIBUTES => []
            ];
            foreach($filters as $filter)
            {
            	$params[self::ATTRIBUTES][$filter[0]] = $filter[1];
            }
            return self::getInstance(null, $class, $params);
        }
    }

    /**
     * Returns true if the passed instance exists in the instances array
     *
     * @access public
     * @static
     * @param Mixed $instance
     * @return Boolean
     */
    static public function instanceInArray($instance)
    {
        if(array_key_exists(get_class($instance), self::$instances))
        {
            if(array_key_exists($instance->getId(), self::$instances[get_class($instance)]))
            {
                return self::$instances[get_class($instance)][$instance->getId()] === $instance;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Creates a new instance of the specified class, and with the specified id
     *
     * @access protected
     * @static
     * @param STRING $class
     * @param String|Int $Id
     * @return Mixed
     */
    protected static function createInstance($class, $Id = null)
    {
    	$instance = new $class($Id);
        self::putInstanceInArray($instance);
        return $instance;
    }

    /**
     * Puts an object in the instances array
     *
     * @access protected
     * @static
     * @param Mixed $instance
     */
    protected static function putInstanceInArray($instance)
    {
        self::$instances[get_class($instance)][$instance->getId()] = $instance;
    }

    /**
     * Creates the base object for a specified class
     * @access protected
     * @static
     * @param String $class
     * @return Mixed
     */
    protected static function createBaseObject($class)
    {
        return self::createInstance($class, self::BASE_OBJECT_ID);
    }

    /**
     * Determines if the base object for the specified class is already instantiated.
     * @access protected
     * @static
     * @param String $class
     * @return Bool
     */
    protected static function baseObjectExistsInArray($class)
    {
        return (isset(self::$instances[$class][self::BASE_OBJECT_ID]));
    }

    /**
     * Creates a new instance of the specified class,
     * adds it to the instances array
     * and returns the instanciated object.
     * @access protected
     * @static
     * @param STRING $class
     * @param String|Int $Id
     * @return Mixed
     */
    protected static function getNewInstance($class, $Id=null)
    {
        if(!self::baseObjectExistsInArray($class))
        {
            self::createBaseObject($class);
        }
        $obj = clone self::$instances[$class][self::BASE_OBJECT_ID];
        if(!empty($Id))
        {
            if($Id != self::BASE_OBJECT_ID)
            {
                $Id = trim($Id);
                $obj->setId($Id);
            }
            return $obj;
        }
        return $obj;
    }

    /**
     * Set property value
     * @access public
     * @param STRING $name
     * @param MIXED $value
     * @return Subscription
     */
    public function setProperty($name, $value)
    {
        if(isset($this->$name))
        {
            if( $this->$name !== $value )
            {
                $this->$name = $value;
                $this->isModified = true;
            }
        }
        else
        {
            $this->$name = $value;
        }
    }

    /**
     * Gets the property of the specified name
     *
     * @access public
     * @param STRING $propertyName
     * @static
     * @return Mixed
     **/
    public static function getStaticPropertyValue($propertyName)
    {
        $class = get_called_class();
        $vars = get_class_vars($class);
        return $vars[$propertyName];
    }

    /**
     * Get property from object
     * @access public
     * @param STRING $name
     * @return MIXED
     */
    public function get($name)
    {
        switch($name)
        {
            default:
                return $this->$name;
                break;
        }
    }

    /**
     * Check if property of $varName is empty or not
     * @access public
     * @param string $varName
     * @return bool
     */
    public function propertyIsEmpty($varName)
    {
        return (empty($this->$varName));
    }


    /**
     * @access public
     * @param string $varName
     * @return bool
     */
    public function __isset( $varName )
    {
        return (isset($this->$varName));
    }

    /**
     * Returns the instances array
     * @access public
     * @return ARRAY
     * @static
     */
    public static function getInstancesArray()
    {
        return self::$instances;
    }

    /**
     * Clone method
     * @access protected
     */
    protected function __clone()
    {
        foreach($this as $propertyName => $propertyValue)
        {
            if(is_object($propertyValue))
            {
                $this->$propertyName = clone $propertyValue;
            }
        }
        $this->setId(uniqid('I', true));
        return $this;
    }
}
?>