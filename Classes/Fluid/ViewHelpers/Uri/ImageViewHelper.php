<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Patrick Schriner
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * This is a 90% copy of the core 12.4.22 file
 * Due to that file being final we have to copy everything here, because
 * composer patches have their own issues.
 */

namespace ITZBund\GsbCore\Fluid\ViewHelpers\Uri;

use Psr\Http\Message\RequestInterface;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;

/**
 * Resizes a given image (if required) and returns its relative path.
 *
 * Note
 * ----
 *
 * Adapted version, that does not throw an exception when an image is not found
 *
 * This ViewHelper should only be used for images within FAL storages,
 * or where graphical operations shall be performed.
 *
 * Note that when the contents of a non-FAL image are changed,
 * an image may not show updated processed contents unless either the
 * FAL record is updated/removed, or the temporary processed images are
 * cleared.
 *
 * Also note that image operations (cropping, scaling, converting) on
 * non-FAL files may be changed in future TYPO3 versions, since those operations
 * are coupled with FAL metadata. Each non-FAL image operation creates a
 * "fake" FAL record, which may lead to problems.
 *
 * For extension resource files, use :ref:`<f:uri.resource> <typo3-fluid-uri-resource>`
 * instead.
 *
 * External URLs are not processed and just returned as is.
 *
 * Examples
 * ========
 *
 * Default
 * -------
 *
 * ::
 *
 *    <f:uri.image src="EXT:myext/Resources/Public/typo3_logo.png" />
 *
 * Results in the following output within TYPO3 frontend:
 *
 * ``typo3conf/ext/myext/Resources/Public/typo3_logo.png``
 *
 * and the following output inside TYPO3 backend:
 *
 * ``../typo3conf/ext/myext/Resources/Public/typo3_logo.png``
 *
 * Image Object
 * ------------
 *
 * ::
 *
 *    <f:uri.image image="{imageObject}" />
 *
 * Results in the following output within TYPO3 frontend:
 *
 * ``fileadmin/images/image.png``
 *
 * and the following output inside TYPO3 backend:
 *
 * ``fileadmin/images/image.png``
 *
 * Inline notation
 * ---------------
 *
 * ::
 *
 *    {f:uri.image(src: 'EXT:myext/Resources/Public/typo3_logo.png', minWidth: 30, maxWidth: 40)}
 *
 * ``typo3temp/assets/images/[b4c0e7ed5c].png``
 *
 * Depending on your TYPO3s encryption key.
 *
 * Non existing image
 * ------------------
 *
 * ::
 *
 *    <f:uri.image src="NonExistingImage.png" />
 *
 * ``Could not get image resource for "NonExistingImage.png".``
 *
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class ImageViewHelper extends AbstractViewHelper
{
    protected ImageService $imageService;

    public function __construct()
    {
        $this->imageService = GeneralUtility::makeInstance(ImageService::class);
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('src', 'string', 'src', false, '');
        $this->registerArgument('treatIdAsReference', 'bool', 'given src argument is a sys_file_reference record', false, false);
        $this->registerArgument('image', 'object', 'image');
        $this->registerArgument('crop', 'string|bool|array', 'overrule cropping of image (setting to FALSE disables the cropping set in FileReference)');
        $this->registerArgument('cropVariant', 'string', 'select a cropping variant, in case multiple croppings have been specified or stored in FileReference', false, 'default');
        $this->registerArgument('fileExtension', 'string', 'Custom file extension to use');

        $this->registerArgument('width', 'string', 'width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.');
        $this->registerArgument('height', 'string', 'height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.');
        $this->registerArgument('minWidth', 'int', 'minimum width of the image');
        $this->registerArgument('minHeight', 'int', 'minimum height of the image');
        $this->registerArgument('maxWidth', 'int', 'maximum width of the image');
        $this->registerArgument('maxHeight', 'int', 'maximum height of the image');
        $this->registerArgument('absolute', 'bool', 'Force absolute URL', false, false);
    }

    public function render(): string
    {
        $result = '';
        try {
            $result = $this->originalRender($this->arguments, $this->renderChildren());
        } catch (\Exception $e) {
            /* @var \TYPO3\CMS\Core\Http\ServerRequest $request */
            $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
            $logger = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
            $logger->error('Exception caught', ['uri' => $request ? $request->getUri() : '###no-request###', 'exception' => $e]);
        }
        return $result;
    }

    /**
     * Resizes the image (if required) and returns its path. If the image was not resized, the path will be equal to $src
     *
     * @throws Exception
     */
    public function originalRender(): string
    {
        try {
            $image = $this->imageService->getImage(
                (string)$this->arguments['src'],
                $this->arguments['image'],
                (bool)$this->arguments['treatIdAsReference']
            );

            $cropVariantCollection = $this->createCropVariantCollection();
            $cropVariant = $this->arguments['cropVariant'] ?: 'default';
            $cropArea = $cropVariantCollection->getCropArea($cropVariant);
            $processingInstructions = [
                'width' => $this->arguments['width'],
                'height' => $this->arguments['height'],
                'minWidth' => $this->arguments['minWidth'],
                'minHeight' => $this->arguments['minHeight'],
                'maxWidth' => $this->arguments['maxWidth'],
                'maxHeight' => $this->arguments['maxHeight'],
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
            ];
            if (!empty($this->arguments['fileExtension'])) {
                $processingInstructions['fileExtension'] = $this->arguments['fileExtension'];
            }

            $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);

            return $this->imageService->getImageUri($processedImage, $this->arguments['absolute']);
        } catch (\UnexpectedValueException $e) {
            // thrown if a file has been replaced with a folder
            throw new Exception($this->getExceptionMessage($e->getMessage()), 1509741908, $e);
        } catch (\InvalidArgumentException $e) {
            // thrown if file storage does not exist
            throw new Exception($this->getExceptionMessage($e->getMessage()), 1509741910, $e);
        }
    }

    protected function createCropVariantCollection(FileInterface $image): CropVariantCollection
    {
        $cropString = $this->arguments['crop'];

        if ($cropString === null && $image->hasProperty('crop') && $image->getProperty('crop')) {
            $cropString = $image->getProperty('crop');
        }

        // CropVariantCollection needs a string, but this VH could also receive an array
        if (is_array($cropString)) {
            $cropString = json_encode($cropString);
        }

        return CropVariantCollection::create((string)$cropString);
    }

    protected function getExceptionMessage(string $detailedMessage): string
    {
        $request = $this->renderingContext->getRequest();

        if ($request instanceof RequestInterface) {
            $currentContentObject = $request->getAttribute('currentContentObject');
            if ($currentContentObject instanceof ContentObjectRenderer) {
                return sprintf('Unable to render image uri in "%s": %s', $currentContentObject->currentRecord, $detailedMessage);
            }
        }

        return sprintf('Unable to render image uri: %s', $detailedMessage);
    }
}
