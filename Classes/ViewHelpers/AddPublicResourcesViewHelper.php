<?php
namespace Alexweb\AwResize\ViewHelpers;

class AddPublicResourcesViewHelper extends  \TYPO3\CMS\Fluid\ViewHelpers\Be\ContainerViewHelper {

    public function render() {
        $doc = $this->getDocInstance();
        $pageRenderer = $doc->getPageRenderer();

        $pageRenderer->addCssFile('/typo3conf/ext/aw_resize/Resources/Public/css/styles.css');
        $pageRenderer->addJsFile("/typo3/contrib/jquery/jquery-1.9.1.min.js");
        $pageRenderer->addJsFile("/typo3conf/ext/aw_resize/Resources/Public/js/windowHandler.js");

        $output = $this->renderChildren();
        $output = $doc->startPage("aw_resize") . $output;
        $output .= $doc->endPage();

        return $output;
    }
}