<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#conditiontype
 */
enum ConditionType: string
{
    case CONDITION_TYPE_UNSPECIFIED = 'CONDITION_TYPE_UNSPECIFIED';
    case NUMBER_GREATER = 'NUMBER_GREATER';
    case NUMBER_GREATER_THAN_EQ = 'NUMBER_GREATER_THAN_EQ';
    case NUMBER_LESS = 'NUMBER_LESS';
    case NUMBER_LESS_THAN_EQ = 'NUMBER_LESS_THAN_EQ';
    case NUMBER_EQ = 'NUMBER_EQ';
    case NUMBER_NOT_EQ = 'NUMBER_NOT_EQ';
    case NUMBER_BETWEEN = 'NUMBER_BETWEEN';
    case NUMBER_NOT_BETWEEN = 'NUMBER_NOT_BETWEEN';
    case TEXT_CONTAINS = 'TEXT_CONTAINS';
    case TEXT_NOT_CONTAINS = 'TEXT_NOT_CONTAINS';
    case TEXT_STARTS_WITH = 'TEXT_STARTS_WITH';
    case TEXT_ENDS_WITH = 'TEXT_ENDS_WITH';
    case TEXT_EQ = 'TEXT_EQ';
    case TEXT_IS_EMAIL = 'TEXT_IS_EMAIL';
    case TEXT_IS_URL = 'TEXT_IS_URL';
    case DATE_EQ = 'DATE_EQ';
    case DATE_BEFORE = 'DATE_BEFORE';
    case DATE_AFTER = 'DATE_AFTER';
    case DATE_ON_OR_BEFORE = 'DATE_ON_OR_BEFORE';
    case DATE_ON_OR_AFTER = 'DATE_ON_OR_AFTER';
    case DATE_BETWEEN = 'DATE_BETWEEN';
    case DATE_NOT_BETWEEN = 'DATE_NOT_BETWEEN';
    case DATE_IS_VALID = 'DATE_IS_VALID';
    case ONE_OF_RANGE = 'ONE_OF_RANGE';
    case ONE_OF_LIST = 'ONE_OF_LIST';
    case BLANK = 'BLANK';
    case NOT_BLANK = 'NOT_BLANK';
    case CUSTOM_FORMULA = 'CUSTOM_FORMULA';
    case BOOLEAN = 'BOOLEAN';
    case TEXT_NOT_EQ = 'TEXT_NOT_EQ';
    case DATE_NOT_EQ = 'DATE_NOT_EQ';
}
