<?php

namespace CodexShaper\DBM\Database\Types\Mysql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class BlobType extends Type
{
    const NAME = 'blob';

    /**
     * Register blob type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'blob';
    }
}
