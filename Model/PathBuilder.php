<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 05/12/15
 * Time: 12:23
 */

namespace Vss\ResourcesFirewallBundle\Model;

/**
 * Class PathBuilder
 * @package Vss\ResourcesFirewallBundle\Model
 */
class PathBuilder
{

    /**
     * @var string
     */
    private $resourcesDir;

    /**
     * PathBuilder constructor.
     * @param $resourcesDir
     */
    public function __construct($resourcesDir) {
        $this->resourcesDir = $resourcesDir;
    }

    /**
     * @param $rootDir
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getResourcesPath($rootDir, $path) {
        $root = '';
        if (preg_match("/(.+)\/app/", $rootDir, $matches)) {
            $root = $matches[1];
        } else {
            throw new \InvalidArgumentException(sprintf("The root dir %s doesn't match the excected regex.", $root));
        }
        $frontPath = $root . $this->resourcesDir . '/' . $path;
        return $frontPath;
    }

    /**
     * @param $file
     * @return string
     */
    public function getMime($file) {
        return \Defr\MimeType::get($file);
    }

    /**
     * @param $path
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public function getRoleFromPath($path) {
        // let's say $path = "/js/app._admin.js
        $fileArray = null;
        $file = null;

        if (preg_match("/\/?(.+)$/", $path, $matches)) {
            // $file = app._admin.js
            $file = $matches[1];
            $fileArray = explode('.', $file);
        } else {
            throw new \InvalidArgumentException();
        }

        // if the file is with name.ext no role verification is needed
        if (count($fileArray) >= 3) {
            // $lastlast = _admin
            $lastlast = $fileArray[count($fileArray) - 2];
            // if there is a _ in [0] then it is a role requirement
            $isRole = $lastlast[0] == '_' ? true : false;
            if ($isRole) {

                // $role = ROLE_ADMIN
                $role = "ROLE" . strtoupper($lastlast);
                return $role;
            }
        }
        return null;
    }
}