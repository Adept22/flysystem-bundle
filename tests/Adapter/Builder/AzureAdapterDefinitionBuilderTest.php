<?php

/*
 * This file is part of the flysystem-bundle project.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\League\FlysystemBundle\Adapter\Builder;

use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use League\FlysystemBundle\Adapter\Builder\AzureAdapterDefinitionBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AzureAdapterDefinitionBuilderTest extends AbstractAdapterDefinitionBuilderTest
{
    public function createBuilder()
    {
        return new AzureAdapterDefinitionBuilder($this->createDefinitionFactory());
    }

    public function provideValidOptions()
    {
        yield 'minimal' => [[
            'client' => 'my_client',
            'container' => 'container_name',
        ]];

        yield 'prefix' => [[
            'client' => 'my_client',
            'container' => 'container_name',
            'prefix' => 'prefix/path',
        ]];
    }

    /**
     * @dataProvider provideValidOptions
     */
    public function testCreateDefinition($options)
    {
        $this->assertSame(AzureBlobStorageAdapter::class, $this->createBuilder()->createDefinition($options)->getClass());
    }

    public function testOptionsBehavior()
    {
        $definition = $this->createBuilder()->createDefinition([
            'client' => 'my_client',
            'container' => 'container_name',
            'prefix' => 'prefix/path',
        ]);

        $this->assertSame(AzureBlobStorageAdapter::class, $definition->getClass());
        $this->assertInstanceOf(Reference::class, $definition->getArgument(0));
        $this->assertSame('my_client', (string) $definition->getArgument(0));
        $this->assertSame('container_name', $definition->getArgument(1));
        $this->assertSame('prefix/path', $definition->getArgument(2));
    }
}
