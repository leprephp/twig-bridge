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
    public function testBasicTwigInitialization()
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
    }
}
