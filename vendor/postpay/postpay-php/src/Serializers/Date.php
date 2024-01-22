<?php

namespace Postpay\Serializers;

use Datetime;
use JsonSerializable;

class Date implements JsonSerializable
{
    /**
     * @const string Default datetime format.
     */
    const DEFAULT_DATETIME_FORMAT = DateTime::ISO8601;

    /**
     * @const string Default date format.
     */
    const DEFAULT_DATE_FORMAT = 'Y-m-d';

    /**
     * @var string The stored value.
     */
    public $value;

    /**
     * Constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Creates an instance using the specified datetime format.
     *
     * @param DateTime $value
     * @param string   $format
     *
     * @return self
     */
    public static function fromDateTime(
        $value,
        $format = self::DEFAULT_DATETIME_FORMAT
    ) {
        return new self($value->format($format));
    }

    /**
     * Creates an instance using the specified date format.
     *
     * @param DateTime $value
     * @param string   $format
     *
     * @return self
     */
    public static function fromDate(
        $value,
        $format = self::DEFAULT_DATE_FORMAT
    ) {
        return self::fromDateTime($value, $format);
    }

    /**
     * Converts to DateTime type.
     *
     * @param string $format
     *
     * @return DateTime
     */
    public function toDateTime($format = self::DEFAULT_DATETIME_FORMAT)
    {
        return DateTime::createFromFormat($format, $this->value);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->value;
    }
}
