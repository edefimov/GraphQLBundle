<?php

/*
 * This file is part of the OverblogGraphQLBundle package.
 *
 * (c) Overblog <http://github.com/overblog/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Overblog\GraphQLBundle\Tests\Resolver;

use Overblog\GraphQLBundle\Resolver\ArgResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ArgResolverTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ContainerBuilder */
    private static $container;

    /** @var  ArgResolver */
    private static $argResolver;

    public static function setUpBeforeClass()
    {
        $container = new ContainerBuilder();

        $mapping = [
            'Toto' => ['id' => 'overblog_graphql.definition.custom_toto_arg', 'alias' => 'Toto'],
            'Tata' => ['id' => 'overblog_graphql.definition.custom_tata_arg', 'alias' => 'Tata'],
        ];

        $container->setParameter('overblog_graphql.args_mapping', $mapping);

        foreach ($mapping as $alias => $options) {
            $container->setDefinition($options['id'], new Definition('stdClass'))
                ->setProperty('name', $alias);
        }

        self::$container = $container;
        self::$argResolver = new ArgResolver(self::$container);
    }

    public function testResolveKnownArg()
    {
        $arg = self::$argResolver->resolve('Toto');

        $this->assertInstanceOf('stdClass', $arg);
        $this->assertEquals('Toto', $arg->name);
    }

    /**
     * @expectedException \Overblog\GraphQLBundle\Resolver\UnresolvableException
     */
    public function testResolveUnknownArg()
    {
        self::$argResolver->resolve('Fake');
    }
}
