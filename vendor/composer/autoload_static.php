<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit52e5a5072993ae0b2612d52f8754d9a9
{
    public static $files = array (
        'c7359326b6707d98bdc176bf9ddeaebf' => __DIR__ . '/..' . '/catfan/medoo/medoo.php',
    );

    public static $prefixLengthsPsr4 = array (
        'N' => 
        array (
            'NoahBuscher\\Macaw\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'NoahBuscher\\Macaw\\' => 
        array (
            0 => __DIR__ . '/..' . '/noahbuscher/macaw',
        ),
    );

    public static $prefixesPsr0 = array (
        'W' => 
        array (
            'Whoops' => 
            array (
                0 => __DIR__ . '/..' . '/filp/whoops/src',
            ),
        ),
    );

    public static $classMap = array (
        'Whoops\\Module' => __DIR__ . '/..' . '/filp/whoops/src/deprecated/Zend/Module.php',
        'Whoops\\Provider\\Zend\\ExceptionStrategy' => __DIR__ . '/..' . '/filp/whoops/src/deprecated/Zend/ExceptionStrategy.php',
        'Whoops\\Provider\\Zend\\RouteNotFoundStrategy' => __DIR__ . '/..' . '/filp/whoops/src/deprecated/Zend/RouteNotFoundStrategy.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit52e5a5072993ae0b2612d52f8754d9a9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit52e5a5072993ae0b2612d52f8754d9a9::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit52e5a5072993ae0b2612d52f8754d9a9::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit52e5a5072993ae0b2612d52f8754d9a9::$classMap;

        }, null, ClassLoader::class);
    }
}
