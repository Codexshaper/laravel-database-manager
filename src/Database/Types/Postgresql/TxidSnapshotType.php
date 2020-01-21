<?php

namespace CodexShaper\DBM\Database\Types\Postgresql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TxidSnapshotType extends Type
{
    const NAME = 'txid_snapshot';

    /**
     * Register txid_snapshot type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'txid_snapshot';
    }
}
