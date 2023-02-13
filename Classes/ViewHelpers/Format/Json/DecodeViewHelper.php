<?php
declare(strict_types=1);

namespace Gsb\GsbTemplate\ViewHelpers\Format\Json;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class DecodeViewHelper
 * @package Gsb\GsbTemplate\ViewHelpers\Format\Json
 */
class DecodeViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('value', 'string', 'The JSON string to decode', true);
        $this->registerArgument('as', 'string', 'If specified, a template variable with this name containing the decoded JSON will be inserted instead of returning it.', false, null);
        $this->registerArgument('objectDecodeType', 'string', 'If specified, the JSON string will be decoded as an object of this class.', false, null);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $value = $renderChildrenClosure();
        $as = $arguments['as'];
        $objectDecodeType = $arguments['objectDecodeType'];

        $decodedValue = json_decode($value, true, 512, $objectDecodeType);

        if ($as !== null) {
            $templateVariableContainer = $renderingContext->getVariableProvider();
            $templateVariableContainer->add($as, $decodedValue);
            $output = $renderChildrenClosure();
            $templateVariableContainer->remove($as);
            return $output;
        }

        return $decodedValue;
    }
}
