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
 * Twig integration for Lepre.
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
final class TwigServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $container): void
    {
        $container->set('twig', function (Container $container) {
            $options = $container->get('twig.options');

            if (!isset($options['debug'])) {
                $options['debug'] = $container->has('debug') ? $container->get('debug') : false;
            }

            if (!isset($options['strict_variables'])) {
                $options['strict_variables'] = $options['debug'];
            }

            if (!isset($options['auto_reload'])) {
                if ($container->has('environment')) {
                    $options['auto_reload'] = $container->get('environment') === 'development';
                } else {
                    $options['auto_reload'] = $options['debug'];
                }
            }

            $twig = new \Twig_Environment(
                $container->get('twig.loader'),
                $options
            );

            if ($options['debug']) {
                $twig->addExtension(new \Twig_Extension_Debug());
            }

            return $twig;
        });

        $container->alias('twig.loader', 'twig.loader.chain');

        $container->set('twig.options', []);

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
