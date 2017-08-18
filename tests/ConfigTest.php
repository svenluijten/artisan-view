<?php

namespace Sven\ArtisanView\Tests;

use Sven\ArtisanView\Config;

class ConfigTest extends TestCase
{
    /** @test */
    public function it_sets_the_name()
    {
        $config = (new Config)->setName('index');

        $this->assertEquals('index', $config->getName());

        $config->setName('new-name');

        $this->assertEquals('new-name', $config->getName());
    }

    /** @test */
    public function it_sets_the_correct_extension()
    {
        $config = (new Config)->setName('index');

        $config->setExtension('blade.php');

        $this->assertEquals('.blade.php', $config->getExtension());
    }

    /** @test */
    public function it_configures_a_restful_resource()
    {
        $config = (new Config)->setName('products')->setResource(true);

        $this->assertTrue($config->isResource());
        $this->assertCount(4, $config->getVerbs());
    }

    /** @test */
    public function it_sets_the_verbs_for_a_restful_resource()
    {
        $config = (new Config)->setName('index');

        $config->setResource(true)->setVerbs('index', 'create', 'edit');

        $this->assertEquals(['index', 'create', 'edit'], $config->getVerbs());
    }
}
