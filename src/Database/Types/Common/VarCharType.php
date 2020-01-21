<?php

namespace CodexShaper\DBM\Database\Types\Common;

use Doctrine\DBAL\Types\StringType as DoctrineStringType;

class VarCharType extends DoctrineStringType
{
    const NAME = 'varchar';

    /**
     * Register varchar type.
     *
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }
}
