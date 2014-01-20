<?php
namespace Alexweb\AwResize\Controller;

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
use Alexweb\AwResize\Domain\Repository\ResizerRepository;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
/**
 *
 *
 * @package aw_resize
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ResizerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * resizerRepository
	 *
	 * @var \Alexweb\AwResize\Domain\Repository\ResizerRepository
	 * @inject
	 */
	protected $resizerRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction()
    {
        $breadCrumb = "";
        $GeneralUtility = new GeneralUtility();
        $this->resizerRepository = new ResizerRepository();

        $sessionData = $GLOBALS['BE_USER']->getSessionData('tx_awresize');

        $Post = $GeneralUtility->_POST();

        if(isset($Post["getFiles"]))
        {
            //update the session variable only when there is a post
            if(isset($Post["relPath"]))
                $sessionData["resizer"]["relPath"] .= $Post["relPath"];

            if(isset($Post["rootRelPath"]))
                $sessionData["resizer"]["relPath"] = $Post["rootRelPath"];

            $GLOBALS['BE_USER']->setAndSaveSessionData('tx_awresize', $sessionData);
        }

        if(isset($sessionData["resizer"]["relPath"]))
            $breadCrumb = $sessionData["resizer"]["relPath"];

        $this->resizerRepository->setRelPath();

        $files = $this->resizerRepository->getFiles();
        $folders = $this->resizerRepository->getFolders();

		$this->view->assign('folders', $folders);
		$this->view->assign('files', $files);
		$this->view->assign('breadCrumb', $breadCrumb);
		$this->view->assign('fileMountPath', $this->resizerRepository->getFileMountPath());

        if(isset($Post["doResize"]))
        {
            $this->resizerRepository->resizeFiles($Post);
            // un-setting POST array in order to avoid a vicious loop after reloading the list
            unset($_POST);
            $this->listAction();
        }
    }
}
?>