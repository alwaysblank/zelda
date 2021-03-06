<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf8bddd86cd2b24f3178c7acba25e0d9b
{
    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zenodorus\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zenodorus\\' => 
        array (
            0 => __DIR__ . '/..' . '/zenodorus/arrays/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf8bddd86cd2b24f3178c7acba25e0d9b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf8bddd86cd2b24f3178c7acba25e0d9b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
