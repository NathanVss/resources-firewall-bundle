<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 07/01/16
 * Time: 01:58
 */

namespace Vss\ResourcesFirewallBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vss\ResourcesFirewallBundle\DependencyInjection\VssResourcesFirewallExtension;

class ExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function test() {
        $extension = new VssResourcesFirewallExtension();
        $extension->load([], new ContainerBuilder());
    }

}