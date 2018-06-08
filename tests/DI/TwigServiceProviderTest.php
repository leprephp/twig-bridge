<?php

/*
 * This file is part of the Lepre package.
 *
 * (c) Daniele De Nobili <danieledenobili@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Lepre\Bridge\Twig\Tests\DI;

use Lepre\Bridge\Twig\DI\TwigServiceProvider;
use Lepre\DI\Container;
use PHPUnit\Framework\TestCase;

class TwigServiceProviderTest extends TestCase
{
    public function testRegister()
    {
        $container = new Container();
        $container->register(new TwigServiceProvider());

        /** @var \Twig_Environment $twig */
        $twig = $container->get('twig');

        $this->assertInstanceOf(\Twig_Environment::class, $twig);

        // default options
        $this->assertFalse($twig->isDebug());
        $this->assertEquals('UTF-8', $twig->getCharset());
        $this->assertFalse($twig->isStrictVariables());
        $this->assertFalse($twig->getCache());
        $this->assertFalse($twig->isAutoReload());

        // others services
        $this->assertInstanceOf(\Twig_LoaderInterface::class, $container->get('twig.loader'));
        $this->assertInstanceOf(\Twig_Loader_Array::class, $container->get('twig.loader.array'));
        $this->assertInstanceOf(\Twig_Loader_Filesystem::class, $container->get('twig.loader.filesystem'));
        $this->assertInstanceOf(\Twig_Loader_Chain::class, $container->get('twig.loader.chain'));
    }

    public function testRenderTemplates()
    {
        $container = new Container();
        $container->register(new TwigServiceProvider());

        $container->extend('twig.templates', function (array $templates) {
            $templates['hello'] = 'Hello {{ name }}!';

            return $templates;
        });

        $this->assertEquals(
            'Hello John!',
            $container->get('twig')->render('hello', ['name' => 'John'])
        );
    }

    public function testRenderFiles()
    {
        $container = new Container();
        $container->register(new TwigServiceProvider());

        $container->extend('twig.paths', function (array $paths) {
            $paths[] = __DIR__ . '/../Fixtures/theme';

            return $paths;
        });

        $this->assertEquals(
            'Hello John!',
            $container->get('twig')->render('hello.twig', ['name' => 'John'])
        );
    }

    public function testRenderWithNamespace()
    {
        $container = new Container();
        $container->register(new TwigServiceProvider());

        $container->extend('twig.loader.filesystem', function (\Twig_Loader_Filesystem $loader) {
            $loader->addPath(
                __DIR__ . '/../Fixtures/theme',
                'theme'
            );

            return $loader;
        });

        $this->assertEquals(
            'Hello John!',
            $container->get('twig')->render('@theme/hello.twig', ['name' => 'John'])
        );
    }
}
