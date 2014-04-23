<?php
namespace Alexweb\AwResize\ViewHelpers;

class AddPublicResourcesViewHelper extends  \TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper {

    public function render() {
        $doc = $this->getDocInstance();
        $pageRenderer = $doc->getPageRenderer();
        $extRelPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath("aw_resize");

        $pageRenderer->addCssFile($extRelPath . "Resources/Public/css/styles_aw_resize.css");

        $pageRenderer->loadJquery();
        $pageRenderer->addJsFile($extRelPath . "Resources/Public/js/windowHandler.js");
        $pageRenderer->addJsFile($extRelPath . "Resources/Public/js/app.js");

        $output = $this->renderChildren();
        $output = $doc->startPage("aw_resize") . $output;
        $output .= $doc->endPage();

        return $output;
    }
}
