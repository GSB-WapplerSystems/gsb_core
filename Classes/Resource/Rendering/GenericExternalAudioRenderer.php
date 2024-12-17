<?php

declare(strict_types=1);

namespace ITZBund\GsbCore\Resource\Rendering;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\Rendering\AudioTagRenderer;

class GenericExternalAudioRenderer extends AudioTagRenderer
{
    public function canRender(FileInterface $file)
    {
        return $file->getMimeType() === 'audio/generic' || $file->getExtension() === 'externalaudio';
    }
}
