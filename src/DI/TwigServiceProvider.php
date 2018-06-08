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

namespace Lepre\Bridge\Twig\DI;

use Lepre\DI\Container;
use Lepre\DI\ServiceProviderInterface;

/**
 * TwigServiceProvider
 */
class TwigServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->set('twig', function (Container $container) {
            return new \Twig_Environment(
                $container->get('twig.loader')
            );
        });

        $container->alias('twig.loader', 'twig.loader.chain');

        $container->set('twig.loader.array', function (Container $container) {
            return new \Twig_Loader_Array(
                $container->get('twig.templates')
            );
        });

        $container->set('twig.loader.filesystem', function (Container $container) {
            return new \Twig_Loader_Filesystem(
                $container->get('twig.paths')
            );
        });

        $container->set('twig.loader.chain', function (Container $container) {
            return new \Twig_Loader_Chain([
                $container->get('twig.loader.array'),
                $container->get('twig.loader.filesystem'),
            ]);
        });

        $container->set('twig.templates', function () {
            return [];
        });

        $container->set('twig.paths', function () {
            return [];
        });
    }
}
