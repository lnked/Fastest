<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit250d3211b2454b23972b02a8b7fa7d25
{
    public static $files = array (
        '32dcc8afd4335739640db7d200c1971d' => __DIR__ . '/..' . '/symfony/polyfill-apcu/bootstrap.php',
        '253c157292f75eb38082b5acb06f3f01' => __DIR__ . '/..' . '/nikic/fast-route/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'U' => 
        array (
            'Universal\\' => 10,
        ),
        'S' => 
        array (
            'Symfony\\Component\\Finder\\' => 25,
            'Symfony\\Component\\ClassLoader\\' => 30,
        ),
        'P' => 
        array (
            'Pux\\' => 4,
        ),
        'G' => 
        array (
            'GetOptionKit\\' => 13,
        ),
        'F' => 
        array (
            'FastRoute\\' => 10,
        ),
        'C' => 
        array (
            'CodeGen\\' => 8,
            'ClassTemplate\\' => 14,
            'CLIFramework\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Universal\\' => 
        array (
            0 => __DIR__ . '/..' . '/corneltek/universal/src/Universal',
        ),
        'Symfony\\Component\\Finder\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/finder',
        ),
        'Symfony\\Component\\ClassLoader\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/class-loader',
        ),
        'Pux\\' => 
        array (
            0 => __DIR__ . '/..' . '/corneltek/pux/src/Pux',
        ),
        'GetOptionKit\\' => 
        array (
            0 => __DIR__ . '/..' . '/corneltek/getoptionkit/src',
        ),
        'FastRoute\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/fast-route/src',
        ),
        'CodeGen\\' => 
        array (
            0 => __DIR__ . '/..' . '/corneltek/codegen/src/CodeGen',
        ),
        'ClassTemplate\\' => 
        array (
            0 => __DIR__ . '/..' . '/corneltek/class-template/src/ClassTemplate',
        ),
        'CLIFramework\\' => 
        array (
            0 => __DIR__ . '/..' . '/corneltek/cliframework/src/CLIFramework',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
        'P' => 
        array (
            'Pimple' => 
            array (
                0 => __DIR__ . '/..' . '/pimple/pimple/src',
            ),
        ),
        'D' => 
        array (
            'Doctrine\\Common\\Inflector\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/inflector/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit250d3211b2454b23972b02a8b7fa7d25::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit250d3211b2454b23972b02a8b7fa7d25::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit250d3211b2454b23972b02a8b7fa7d25::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
