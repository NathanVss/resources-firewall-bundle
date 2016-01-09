<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 05/12/15
 * Time: 02:57
 */

namespace Vss\Bundle\ResourcesFirewallBundle\Model;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
/**
 * Class Firewall
 * @package Vss\Bundle\ResourcesFirewallBundle\Model
 */
class Firewall
{
    private $kernel;
    private $securityContext;

    public function __construct(Kernel $kernel, $securityContext) {
        $this->kernel = $kernel;
        $this->securityContext = $securityContext;
    }

    /**
     * @param $filename
     * @return array
     * @throws AccessDeniedException
     * @throws Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    public function getResource($filename) {
        $builder = new PathBuilder();
        $fullPath = $builder->getResourcesPath($this->kernel->getRootDir(), $filename);
        $role = $builder->getRoleFromPath($fullPath);
        if ($role != null && false === $this->securityContext->isGranted($role)) {
            throw new AccessDeniedException();
        }
        if (!file_exists($fullPath)) {
            throw new FileNotFoundException(sprintf("The file %s was not found", $filename));
        }
        $contentType = \Defr\MimeType::get($filename);
        return ['contentType' => $contentType, 'content' => file_get_contents($fullPath)];
    }
}