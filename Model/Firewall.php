<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 05/12/15
 * Time: 02:57
 */

namespace Vss\ResourcesFirewallBundle\Model;

use Defr\PhpMimeType\MimeType;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Firewall
 * @package Vss\ResourcesFirewallBundle\Model
 */
class Firewall
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var
     */
    private $securityContext;

    private $resourcesDir;

    /**
     * Firewall constructor.
     * @param Kernel $kernel
     * @param $securityContext
     */
    public function __construct(Kernel $kernel, $securityContext) {
        $this->kernel = $kernel;
        $this->securityContext = $securityContext;
        $this->resourcesDir = '/resources';
    }

    /**
     * @param $resourcesDir
     */
    public function setResourcesDir($resourcesDir) {
        $this->resourcesDir = $resourcesDir;
    }

    /**
     * @param $filename
     * @return Response
     */
    public function getResourceResponse($filename) {
        try {
            $resource = $this->getResource($filename);
        } catch(\InvalidArgumentException $e) {
            // Unusefull in this context
//            throw new BadRequestHttpException("Format not accepted for %s", $path);
        } catch(FileNotFoundException $e) {
            throw new NotFoundHttpException(sprintf("The file %s was not found", $filename));
        }
        $response = new Response($resource['content']);
        $response->headers->set('Content-Type', $resource['contentType']);
        return $response;
    }

    /**
     * @param $filename
     * @return array
     */
    public function getResource($filename) {
        $builder = new PathBuilder($this->resourcesDir);
        $fullPath = $builder->getResourcesPath($this->kernel->getRootDir(), $filename);
        $role = $builder->getRoleFromPath($fullPath);
        if ($role != null && false === $this->securityContext->isGranted($role)) {
            throw new AccessDeniedException();
        }
        if (!file_exists($fullPath)) {
            throw new FileNotFoundException(sprintf("The file %s was not found", $filename));
        }
        $contentType = MimeType::get($filename);
        return ['contentType' => $contentType, 'content' => file_get_contents($fullPath)];
    }
}
