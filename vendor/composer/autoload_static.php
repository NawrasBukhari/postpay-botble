<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3654996067521afccbe440c5b22d897e
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Postpay\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Postpay\\' => 
        array (
            0 => __DIR__ . '/..' . '/postpay/postpay-php/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3654996067521afccbe440c5b22d897e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3654996067521afccbe440c5b22d897e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3654996067521afccbe440c5b22d897e::$classMap;

        }, null, ClassLoader::class);
    }
}