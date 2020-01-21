<?php

namespace CodexShaper\DBM\Database\Types\Postgresql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TimeStampTzType extends Type
{
    const NAME = 'timestamptz';

    /**
     * Register timestamptz type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'timestamp(0) with time zone';
    }
}
