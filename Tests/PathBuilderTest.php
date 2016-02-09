<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 05/12/15
 * Time: 12:24
 */

namespace Vss\ResourcesFirewallBundle\Tests;

use Vss\ResourcesFirewallBundle\Model\PathBuilder;

class PathBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $rootDir = "/path/to/symfony/app";

    public function testResourcesPath() {
        $builder = new PathBuilder('/front');
        $this->assertEquals($builder->getResourcesPath($this->rootDir, 'app.js'), "/path/to/symfony/front/app.js");

    }
    public function testResourcesPathException() {
        $builder = new PathBuilder('/front');
        try {
            $builder->getResourcesPath("/path/to/symfony/something", "app.js");
        } catch(\InvalidArgumentException $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    public function testResourceRole() {
        $builder = new PathBuilder('/front');
        $this->assertEquals($builder->getRoleFromPath('/js/app.js'), null);
        $this->assertEquals($builder->getRoleFromPath('/app.js'), null);
        $this->assertEquals($builder->getRoleFromPath('app.js'), null);
        $this->assertEquals($builder->getRoleFromPath('app._admin.js'), 'ROLE_ADMIN');
        $this->assertEquals($builder->getRoleFromPath('/js/app.min.js'), null);
        $this->assertEquals($builder->getRoleFromPath('/js/app._admin.js'), 'ROLE_ADMIN');
        $this->assertEquals($builder->getRoleFromPath('/js/app._admin.min.js'), null);
        $this->assertEquals($builder->getRoleFromPath('/js/app.min._admin.js'), 'ROLE_ADMIN');
    }

    public function testResourceRoleException() {
        $builder = new PathBuilder('/front');
        try {
            $builder->getRoleFromPath('');
        } catch(\InvalidArgumentException $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    public function testMime() {
        $builder = new PathBuilder('/front');
        $this->assertEquals($builder->getMime('app.js'), 'application/javascript');
        $this->assertEquals($builder->getMime('app.css'), 'text/css');
    }
}