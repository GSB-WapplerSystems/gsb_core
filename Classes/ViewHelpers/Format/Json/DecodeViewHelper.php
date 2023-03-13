<?php

declare(strict_types=1);

namespace ITZBund\GsbTemplate\ViewHelpers\Format\Json;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class DecodeViewHelper
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

    public function initializeArguments(): void
    {
        $this->registerArgument('json', 'string', 'The JSON string to decode', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        if (empty($arguments)) {
            return null;
        }
        $json = $arguments['json'];

        if (empty($json)) {
            return null;
        }

        $decodedValue = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('The provided argument is invalid JSON.', (int)1358440054);
        }

        return $decodedValue;
    }
}
