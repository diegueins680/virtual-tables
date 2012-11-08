<?php

/**
 * DataBaseAccesor class
 *
 * @author  Diego Saa <Dsaa@dubli.com>
 */

final class QueryParameter
{
    const PARAM_CONSTANT = 'constant';

    const ARITHMETIC_OPERATOR = 'operator';
    const ARITHMETIC_PARAMETER = 'arithmeticParameter';

    const FIELD_NAME = 'fieldName';

    const TYPE = 'type';

    const STATEMENT = 'statement';
    const ALIAS = 'alias';

    const LIMIT_FROM = 'limitFrom';
    const LIMIT_TO = 'limitTo';

    public $additionalFields;
    public $aggregateFunctionParameter;
    public $filters;
    public $baseTable;
    public $groupByFields;
    public $jointFields;
    public $operationType;
    public $operationResultName;
    public $having;
    public $orderBy;
    public $limit;

    public $arithmeticOperatioOnResult;

    public $db_connection;

    public $functionName;
    public $repositoryName;
    
    public $queryType;

    static $infixFunctions = array(DataBaseAccessor::FUNCTION_OR, DataBaseAccessor::FUNCTION_AND, '+', '-', '*', '/');
}
