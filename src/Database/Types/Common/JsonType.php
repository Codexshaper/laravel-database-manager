<?php

namespace CodexShaper\DBM\Database\Types\Common;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class JsonType extends Type
{
    const NAME = 'json';

    /**
     * Register json type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'json';
    }
}
