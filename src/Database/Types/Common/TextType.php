<?php

namespace CodexShaper\DBM\Database\Types\Common;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TextType extends Type
{
    const NAME = 'text';

    /**
     * Register text type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'text';
    }
}
