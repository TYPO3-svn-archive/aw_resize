<?php
namespace Alexweb\AwResize\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 alex <websurfer992@gmail.com>, alex-web.gr
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package aw_resize
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ResizerRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

    /**
     * @var string
     * the relative path to the image folder to be scanned will be moved in the frontend in the next version
     */

    private $staticPath = "fileadmin";
    private $fileMount = null;
    private $relPath = null;

    public function setRelPath()
    {
        $requestUri = $_SERVER["REQUEST_URI"];
        $parts = explode("typo3",$requestUri);

        if(isset($parts[0]))
            $this->staticPath = $parts[0] . "fileadmin";

        if($GLOBALS["BE_USER"]->isAdmin())
        {
            $this->fileMount = "/";
        }
        else
        {
            $FileMount = $this->getFileMount();

            if(!empty($FileMount))
                $this->fileMount = $FileMount["path"];
            else
                $this->fileMount = "/user_upload/dummy/";
        }

        $sessionData = $GLOBALS['BE_USER']->getSessionData('tx_awresize');

        if(isset($sessionData["resizer"]["relPath"]))
            $this->relPath = $this->staticPath . $this->fileMount . $sessionData["resizer"]["relPath"];
        else
            $this->relPath = $this->staticPath . $this->fileMount;
    }

    public function getFolders()
    {
        $folders = array();
        $protocol = "http://";

        if($_SERVER["HTTPS"])
            $protocol = "https://";

        $webDir = $protocol . $_SERVER["HTTP_HOST"] . $this->relPath;
        $dir = $_SERVER["DOCUMENT_ROOT"] . $this->relPath;

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($folder = readdir($dh)) !== false)
                {
                    if( $folder != "." &&
                        $folder != ".." &&
                        $folder != "_processed_" &&
                        $folder != "_migrated_" &&
                        $folder != "_migrated" &&
                        $folder != "_temp_"
                    )
                    {
                        $pathInfo = pathinfo($webDir . $folder);

                        if(!isset($pathInfo["extension"]))
                        {
                            $uniqId = uniqid("rand", true);
                            $pathInfo = pathinfo($webDir . $folder);

                            $folders[$uniqId]["uniqId"] = $uniqId;
                            $folders[$uniqId]["basename"] = $pathInfo["basename"];
                        }
                    }
                }
                closedir($dh);
            }
        }

        return $folders;
    }

    public function getFiles()
    {
        $files = array();
        $protocol = "http://";

        if($_SERVER["HTTPS"])
            $protocol = "https://";

        $webDir = $protocol . $_SERVER["HTTP_HOST"] . $this->relPath;
        $dir = $_SERVER["DOCUMENT_ROOT"] . $this->relPath;

        //var_dump($dir);
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false)
                {
                    if($file != "." && $file != "..")
                    {
                        $getImageSize = getimagesize($webDir . $file);

                        if(
                            $getImageSize["mime"] == "image/jpeg" ||
                            $getImageSize["mime"] == "image/png"
                        )
                        {
                            $uniqId = uniqid("rand", true);
                            $pathInfo = pathinfo($webDir . $file);
                            $get_headers = get_headers($webDir . $file, 1);

                            $files[$uniqId]["uniqId"] = $uniqId;
                            $files[$uniqId]["dirname"] = $pathInfo["dirname"];
                            $files[$uniqId]["basename"] = $pathInfo["basename"];
                            $files[$uniqId]["filename"] = $pathInfo["filename"];
                            $files[$uniqId]["extension"] = $pathInfo["extension"];
                            $files[$uniqId]["url"] = $pathInfo["dirname"] . "/" . $pathInfo["basename"];
                            $files[$uniqId]["width"] = $getImageSize[0];
                            $files[$uniqId]["height"] = $getImageSize[1];
                            $files[$uniqId]["mime"] = $getImageSize["mime"];
                            $files[$uniqId]["size"] = $get_headers["Content-Length"];
                            $files[$uniqId]["modified"] = $get_headers["Last-Modified"];
                        }
                    }
                }
                closedir($dh);
            }
        }

        return $files;
    }

    public function resizeFiles($post)
    {
        foreach($post["files"] as $file)
        {
            $this->resizeFile($post, $file);
        }

        return true;
    }

    public function resizeFile($post, $filename)
    {
        $width = $post["width"];
        $height = $post["height"];

        $pathInfo = pathinfo($filename);
        $getImageSize = getimagesize($filename);

        $width_orig = $getImageSize[0];
        $height_orig = $getImageSize[1];
        $mime = $getImageSize["mime"];

        $ratio_orig = $width_orig / $height_orig;

        if ($width / $height > $ratio_orig) {
            $width = $height * $ratio_orig;
        } else {
            $height = $width / $ratio_orig;
        }

        $width = floor($width);
        $height = floor($height);

        $image_p = imagecreatetruecolor($width, $height);

        //TODO support more file types
        switch($mime)
        {
            case "image/jpeg":
                $image = imagecreatefromjpeg($filename);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                imagejpeg($image_p, ".." . $this->relPath . $pathInfo["filename"] . "__" . $width . "x" . $height . "." . $pathInfo["extension"]);
            break;

            case "image/png":
                $image = imagecreatefrompng($filename);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                imagepng($image_p, ".." . $this->relPath . $pathInfo["filename"] . "__" . $width . "x" . $height . "." . $pathInfo["extension"]);
            break;
        }
    }

    public function getFileMount()
    {
        //$GLOBALS['TYPO3_DB']->debugOutput = true;

        $Model = null;
        $table = "sys_filemounts";
        $userGroup = $GLOBALS["BE_USER"]->userGroups[$GLOBALS["BE_USER"]->firstMainGroup];

        $query = $GLOBALS['TYPO3_DB']->exec_SELECTquery("*", $table, "uid=" . $userGroup["file_mountpoints"]);

        if($query)
            $Model = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($query);

        return $Model;
    }

}
?>