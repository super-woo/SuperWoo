<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitaf96d97d3289cad25c2c755344486969
{
    public static $files = array (
        '4c66dd42ee4ffa679c26f2771a096a1e' => __DIR__ . '/../..' . '/includes/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'springdevs\\WooAdvanceCoupon\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'springdevs\\WooAdvanceCoupon\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitaf96d97d3289cad25c2c755344486969::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitaf96d97d3289cad25c2c755344486969::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
