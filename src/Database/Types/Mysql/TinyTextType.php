<?php

namespace CodexShaper\DBM\Database\Types\Mysql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TinyTextType extends Type
{
    const NAME = 'tinytext';

    /**
     * Register tinytext type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'tinytext';
    }
}
