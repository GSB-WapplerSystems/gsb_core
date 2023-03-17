<?php
namespace ITZBund\GsbTemplate\Tests\Fixtures;

use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;

class DummyViewHelperNode extends ViewHelperNode
{
    public function __construct(ViewHelperInterface $viewHelper)
    {
        $this->uninitializedViewHelper = $viewHelper;
    }
}
