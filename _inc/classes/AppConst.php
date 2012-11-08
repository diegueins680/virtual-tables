<?php
final class AppConst
{
	const MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';
	
	const ENV_LOCAL = 'local';
	const ENV_DEVELOPMENT = 'development';
	const ENV_STAGING = 'staging';
	const ENV_PRODUCTION = 'production';
	
	const DB_HOST = 'host';
	const DB_USER = 'user';
	const DB_PASS = 'pass';
	const DB_NAME = 'dbname';
	const DB_DRIVER = 'db_driver';
	const DB_ATTRIBUTES = 'db_attributes';
	const DB_OPTIONS = 'db_options';
	
	const TABLE_TABLE = 'table';
	const TABLE_OBJECT = 'object';
	const TABLE_ATTRIBUTE_TYPE = 'attribute_type';
	const TABLE_INT_ATTRIBUTE = 'int_attribute';
	const TABLE_DECIMAL_ATTRIBUTE = 'decimal_attribute';
	const TABLE_VARCHAR_ATTRIBUTE = 'varchar_attribute';
	const TABLE_DATETIME_ATTRIBUTE = 'datetime_attribute';
	const TABLE_OBJECT_ATTRIBUTE = 'object_attribute';
	const TABLE_BOOL_ATTRIBUTE = 'bool_attribute';
	const TABLE_RELATION_OBJECT_IS_TYPE = 'object_is_type';
	
	const TYPE_FIELD_FORMAT = 'field_format';
	const TYPE_LEAD_LIST_FORMAT = 'lead_list_field_format';
	const TYPE_LEAD_LIST = 'lead_list';
	const TYPE_LEAD_CSV = 'lead_csv';
	const TYPE_LEAD = 'lead';
	const TYPE_PERSON = 'person';
	const TYPE_DBCONNECTION_CONFIG = 'db_connection_config';
	const TYPE_FILE_FORMAT = 'file_format';
	const TYPE_LL_REQUIREMENT = 'll_requirement';
	const TYPE_LL_REQ_ATTRIB = 'll_requirement_attrib';
	const TYPE_VENDOR = 'vendor';
	
	const ICUDE_LEADS_MASTER = 'ht_leads_master';
	
	const CTI_UPLOADS = 'uploads';
	const CTI_CALL_LIST = 'call_list';
	
	const SCHEMA_ICUDE = 'icude';
	const SCHEMA_CTI = 'cti';
	
	/**
	 * Name of connection with Read only privileges
	 * @var string
	 */
	const MYSQL_R = 'MYSQL_R';

	/**
	 * Name of connection with Read/Write privileges
	 * @var string
	 */
	const MYSQL_RW = 'MYSQL_RW';
	
	/**
	 * Name of the property holding the primary key for database objects
	 * @var string
	 */
	const OBJECT_ID = 'object_id';
}
?>