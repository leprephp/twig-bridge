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
        $container->set('twig', function () {
            return new \Twig_Environment(
                new \Twig_Loader_Array()
            );
        });
    }
}
