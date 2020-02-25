<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitba25b7b4d9a98bdfa2652cd25c123cda
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Wpo\\Util\\' => 9,
            'Wpo\\User\\' => 9,
            'Wpo\\Pages\\' => 10,
            'Wpo\\Firebase\\JWT\\' => 17,
            'Wpo\\Aad\\' => 8,
            'Wpo\\API\\' => 8,
            'Wpo\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Wpo\\Util\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Wpo/Util',
        ),
        'Wpo\\User\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Wpo/User',
        ),
        'Wpo\\Pages\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Wpo/Pages',
        ),
        'Wpo\\Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Wpo/Firebase/JWT',
        ),
        'Wpo\\Aad\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Wpo/Aad',
        ),
        'Wpo\\API\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Wpo/API',
        ),
        'Wpo\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Wpo',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitba25b7b4d9a98bdfa2652cd25c123cda::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitba25b7b4d9a98bdfa2652cd25c123cda::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
