<?php

namespace CodexShaper\DBM\Database\Types\Common;

use Doctrine\DBAL\Types\FloatType as DoctrineFloatType;

class DoubleType extends DoctrineFloatType
{
    const NAME = 'double';

    /**
     * Register double type.
     *
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }
}
