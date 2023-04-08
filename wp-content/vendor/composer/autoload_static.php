<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit692a5101df0e0aeb06dcf773fd8df248
{
    public static $files = array (
        'a4a119a56e50fbb293281d9a48007e0e' => __DIR__ . '/..' . '/symfony/polyfill-php80/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'v' => 
        array (
            'voku\\helper\\' => 12,
        ),
        'W' => 
        array (
            'WPGraphqlGutenberg\\' => 19,
            'WPGraphQL\\Upload\\' => 17,
            'WPGraphQL\\JWT_Authentication\\' => 29,
            'WPGraphQLGravityForms\\' => 22,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Php80\\' => 23,
            'Symfony\\Component\\CssSelector\\' => 30,
        ),
        'O' => 
        array (
            'Opis\\JsonSchema\\' => 16,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'voku\\helper\\' => 
        array (
            0 => __DIR__ . '/..' . '/voku/simple_html_dom/src/voku/helper',
        ),
        'WPGraphqlGutenberg\\' => 
        array (
            0 => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src',
        ),
        'WPGraphQL\\Upload\\' => 
        array (
            0 => __DIR__ . '/../..' . '/plugins/wp-graphql-upload/src',
        ),
        'WPGraphQL\\JWT_Authentication\\' => 
        array (
            0 => __DIR__ . '/../..' . '/plugins/wp-graphql-jwt-authentication/src',
        ),
        'WPGraphQLGravityForms\\' => 
        array (
            0 => __DIR__ . '/../..' . '/plugins/wp-graphql-gravity-forms/src',
        ),
        'Symfony\\Polyfill\\Php80\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php80',
        ),
        'Symfony\\Component\\CssSelector\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/css-selector',
        ),
        'Opis\\JsonSchema\\' => 
        array (
            0 => __DIR__ . '/..' . '/opis/json-schema/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static $classMap = array (
        'Attribute' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Attribute.php',
        'PhpToken' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/PhpToken.php',
        'Stringable' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        'UnhandledMatchError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
        'ValueError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
        'WPGraphQLGutenberg\\Admin\\Editor' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Admin/Editor.php',
        'WPGraphQLGutenberg\\Admin\\Settings' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Admin/Settings.php',
        'WPGraphQLGutenberg\\Blocks\\Block' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Blocks/Block.php',
        'WPGraphQLGutenberg\\Blocks\\Registry' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Blocks/Registry.php',
        'WPGraphQLGutenberg\\Blocks\\RegistryNotSourcedException' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Blocks/Registry.php',
        'WPGraphQLGutenberg\\Blocks\\Utils' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Blocks/Utils.php',
        'WPGraphQLGutenberg\\PostTypes\\BlockEditorPreview' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/PostTypes/BlockEditorPreview.php',
        'WPGraphQLGutenberg\\PostTypes\\ReusableBlock' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/PostTypes/ReusableBlock.php',
        'WPGraphQLGutenberg\\Rest\\Rest' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Rest/Rest.php',
        'WPGraphQLGutenberg\\Schema\\Schema' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Schema/Schema.php',
        'WPGraphQLGutenberg\\Schema\\Types\\BlockTypes' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Schema/Types/BlockTypes.php',
        'WPGraphQLGutenberg\\Schema\\Types\\Connection\\BlockEditorContentNodeConnection' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Schema/Types/Connection/BlockEditorContentNodeConnection.php',
        'WPGraphQLGutenberg\\Schema\\Types\\Connection\\Blocks\\CoreImageBlockToMediaItemConnection' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Schema/Types/Connection/Blocks/CoreImageBlockToMediaItemConnection.php',
        'WPGraphQLGutenberg\\Schema\\Types\\InterfaceType\\Block' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Schema/Types/InterfaceType/Block.php',
        'WPGraphQLGutenberg\\Schema\\Types\\InterfaceType\\BlockEditorContentNode' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Schema/Types/InterfaceType/BlockEditorContentNode.php',
        'WPGraphQLGutenberg\\Schema\\Types\\Object\\ReusableBlock' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Schema/Types/Object/ReusableBlock.php',
        'WPGraphQLGutenberg\\Schema\\Types\\Scalar\\Scalar' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Schema/Types/Scalar/Scalar.php',
        'WPGraphQLGutenberg\\Schema\\Utils' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Schema/Utils.php',
        'WPGraphQLGutenberg\\Server\\Server' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Server/Server.php',
        'WPGraphQLGutenberg\\Server\\ServerException' => __DIR__ . '/../..' . '/plugins/wp-graphql-gutenberg/src/Server/Server.php',
        'WPGraphQL\\JWT_Authentication\\Auth' => __DIR__ . '/../..' . '/plugins/wp-graphql-jwt-authentication/src/Auth.php',
        'WPGraphQL\\JWT_Authentication\\Login' => __DIR__ . '/../..' . '/plugins/wp-graphql-jwt-authentication/src/Login.php',
        'WPGraphQL\\JWT_Authentication\\ManageTokens' => __DIR__ . '/../..' . '/plugins/wp-graphql-jwt-authentication/src/ManageTokens.php',
        'WPGraphQL\\JWT_Authentication\\RefreshToken' => __DIR__ . '/../..' . '/plugins/wp-graphql-jwt-authentication/src/RefreshToken.php',
        'WPGraphQL\\Upload\\Request\\BodyParser' => __DIR__ . '/../..' . '/plugins/wp-graphql-upload/src/Request/BodyParser.php',
        'WPGraphQL\\Upload\\Type\\Upload' => __DIR__ . '/../..' . '/plugins/wp-graphql-upload/src/Type/Upload.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit692a5101df0e0aeb06dcf773fd8df248::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit692a5101df0e0aeb06dcf773fd8df248::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit692a5101df0e0aeb06dcf773fd8df248::$classMap;

        }, null, ClassLoader::class);
    }
}
