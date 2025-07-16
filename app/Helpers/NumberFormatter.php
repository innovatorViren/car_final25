<?php

namespace App\Helpers;

class NumberFormatter
{
    const CURRENCY = '_';
    const CURRENCY_ACCOUNTING = "_";
    const CURRENCY_CODE = '_';
    const CURRENCY_SYMBOL = '_';
    const DECIMAL = "_";
    const DECIMAL_ALWAYS_SHOWN = '_';
    const DECIMAL_SEPARATOR_SYMBOL = '_';
    const DEFAULT_RULESET = '_';
    const DEFAULT_STYLE = '_';
    const DIGIT_SYMBOL = '_';
    const DURATION = '_';
    const EXPONENTIAL_SYMBOL = '_';
    const FORMAT_WIDTH = '_';
    const FRACTION_DIGITS = '_';
    const GROUPING_SEPARATOR_SYMBOL = '_';
    const GROUPING_SIZE = '_';
    const GROUPING_USED = '_';
    const IGNORE = '_';
    const INFINITY_SYMBOL = '_';
    const INTEGER_DIGITS = '_';
    const INTL_CURRENCY_SYMBOL = '_';
    const LENIENT_PARSE = '_';
    const MAX_FRACTION_DIGITS = '_';
    const MAX_INTEGER_DIGITS = '_';
    const MAX_SIGNIFICANT_DIGITS = '_';
    const MINUS_SIGN_SYMBOL = '_';
    const MIN_FRACTION_DIGITS = '_';
    const MIN_INTEGER_DIGITS = '_';
    const MIN_SIGNIFICANT_DIGITS = '_';
    const MONETARY_GROUPING_SEPARATOR_SYMBOL = '_';
    const MONETARY_SEPARATOR_SYMBOL = '_';
    const MULTIPLIER = '_';
    const NAN_SYMBOL = '_';
    const NEGATIVE_PREFIX = '_';
    const NEGATIVE_SUFFIX = '_';
    const ORDINAL = '_';
    const PADDING_CHARACTER = '_';
    const PADDING_POSITION = '_';
    const PAD_AFTER_PREFIX = '_';
    const PAD_AFTER_SUFFIX = '_';
    const PAD_BEFORE_PREFIX = '_';
    const PAD_BEFORE_SUFFIX = '_';
    const PAD_ESCAPE_SYMBOL = '_';
    const PARSE_INT_ONLY = '_';
    const PATTERN_DECIMAL = '_';
    const PATTERN_RULEBASED = '_';
    const PATTERN_SEPARATOR_SYMBOL = '_';
    const PERCENT = '_';
    const PERCENT_SYMBOL = '_';
    const PERMILL_SYMBOL = '_';
    const PLUS_SIGN_SYMBOL = '_';
    const POSITIVE_PREFIX = '_';
    const POSITIVE_SUFFIX = '_';
    const PUBLIC_RULESETS = '_';
    const ROUNDING_INCREMENT = '_';
    const ROUNDING_MODE = '_';
    const ROUND_CEILING = '_';
    const ROUND_DOWN = '_';
    const ROUND_FLOOR = '_';
    const ROUND_HALFDOWN = '_';
    const ROUND_HALFEVEN = '_';
    const ROUND_HALFUP = '_';
    const ROUND_UP = '_';
    const SCIENTIFIC = '_';
    const SECONDARY_GROUPING_SIZE = '_';
    const SIGNIFICANT_DIGITS_USED = '_';
    const SIGNIFICANT_DIGIT_SYMBOL = '_';
    const SPELLOUT = '_';
    const TYPE_CURRENCY = '_';
    const TYPE_DEFAULT = '_';
    const TYPE_DOUBLE = '_';
    const TYPE_INT32 = '_';
    const TYPE_INT64 = '_';
    const ZERO_DIGIT_SYMBOL = '_';
    static function create($locale, $style, $pattern = null)
    {
    }
    function format($num, $type = NumberFormatter::TYPE_DEFAULT)
    {
    }
    function formatCurrency($amount, $currency)
    {
    }
    function getAttribute($attribute)
    {
    }
    function getErrorCode()
    {
    }
    function getErrorMessage()
    {
    }
    function getLocale($type = ULOC_ACTUAL_LOCALE)
    {
    }
    function getPattern()
    {
    }
    function getSymbol($symbol)
    {
    }
    function getTextAttribute($attribute)
    {
    }
    function parse($string, $type = NumberFormatter::TYPE_DOUBLE, &$offset = null)
    {
    }
    function parseCurrency($string, &$currency, &$offset = null)
    {
    }
    function setAttribute($attribute, $value)
    {
    }
    function setPattern($pattern)
    {
    }
    function setSymbol($symbol, $value)
    {
    }
    function setTextAttribute($attribute, $value)
    {
    }
    function __construct(
        $locale,
        $style,
        $pattern = null,
        $textAttributes = null
    ) {
    }
}
