<?php

namespace CodexShaper\DBM\Database\Platforms;

use Illuminate\Support\Collection;

abstract class Platform
{
    abstract public static function getTypes(Collection $typeMapping);

    abstract public static function registerCustomTypeOptions();

    /**
     * Get platform with namespace.
     *
     * @param string $platformName
     *
     * @return string
     */
    public static function getPlatform($platformName)
    {
        $platform = __NAMESPACE__.'\\'.ucfirst($platformName);

        if (! class_exists($platform)) {
            throw new \Exception("Platform {$platformName} doesn't exist");
        }

        return $platform;
    }

    /**
     * Get platform types.
     *
     * @param string $platformName
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getPlatformTypes($platformName, Collection $typeMapping)
    {
        $platform = static::getPlatform($platformName);

        return $platform::getTypes($typeMapping);
    }

    /**
     * Register platform custom type options.
     *
     * @param string $platformName
     *
     * @return void
     */
    public static function registerPlatformCustomTypeOptions($platformName)
    {
        $platform = static::getPlatform($platformName);

        return $platform::registerCustomTypeOptions();
    }
}
