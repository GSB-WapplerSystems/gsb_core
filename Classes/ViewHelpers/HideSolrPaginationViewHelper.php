<?php

namespace ITZBund\GsbTemplate\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class HideSolrPaginationViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('hidePagination', 'bool', 'Hide Pagination checkbox', true);
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        if ($arguments['hidePagination'] == 1) {
            return [
                'insertAbove' => false,
                'insertBelow' => true,
            ];
        }
    }
}
