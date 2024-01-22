<?php

namespace Postpay\Serializers;

use JsonSerializable;

class Decimal implements JsonSerializable
{
    /**
     * @const int Default number of decimals.
     */
    const DEFAULT_DECIMALS = 2;

    /**
     * @var int The stored value.
     */
    public $value;

    /**
     * Constructor.
     *
     * @param int $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Creates an instance using the specified format.
     *
     * @param DateTime $value
     * @param int      $decimals
     *
     * @return self
     */
    public static function fromFloat(
        $value,
        $decimals = self::DEFAULT_DECIMALS
    ) {
        return new self((int) round($value * 10 ** $decimals));
    }

    /**
     * Converts to float type.
     *
     * @param int $decimals
     *
     * @return float
     */
    public function toFloat($decimals = self::DEFAULT_DECIMALS)
    {
        return $this->value / 10 ** $decimals;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->value;
    }
}
