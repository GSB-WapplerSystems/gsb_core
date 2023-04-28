<?php

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers;

use FluidTYPO3\Flux\Form;
use FluidTYPO3\Flux\Form\Field\Custom;
use FluidTYPO3\Flux\Service\FluxService;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Core\ApplicationContext;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3Fluid\Fluid\Core\Parser\Interceptor\Escape;

/**
 * AbstractTestCase
 */
abstract class AbstractTestCase extends TestCase
{
    private array $singletonInstancesBackup = [];
    protected array $singletonInstances = [];

    protected function setUp(): void
    {
        if (!defined('LF')) {
            define('LF', PHP_EOL);
        }
        if (!defined('TYPO3_MODE')) {
            define('TYPO3_MODE', 'FE');
        }
        if (!defined('TYPO3_REQUESTTYPE')) {
            define('TYPO3_REQUESTTYPE', 1);
        }
        if (!defined('TYPO3_REQUESTTYPE_FE')) {
            define('TYPO3_REQUESTTYPE_FE', 1);
        }
        if (!defined('TYPO3_REQUESTTYPE_CLI')) {
            define('TYPO3_REQUESTTYPE_CLI', 3);
        }
        if (!defined('TYPO3_version')) {
            define('TYPO3_version', '12.2.0');
        }

        $pwd = realpath(__DIR__ . '/../../');

        Environment::initialize(
            new ApplicationContext('Development'),
            true,
            false,
            $pwd,
            $pwd . '/public',
            $pwd . '/var',
            $pwd . '/config/system',
            $pwd . '/index.php',
            'linux',
        );

        $GLOBALS['EXEC_TIME'] = time();
        $GLOBALS['LANG'] = (object)['csConvObj' => new CharsetConverter()];
        $GLOBALS['TYPO3_CONF_VARS']['BE']['versionNumberInFilename'] = false;
        $GLOBALS['TYPO3_CONF_VARS']['FE']['versionNumberInFilename'] = false;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['preProcessors'] = [];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['interceptors'] = [
            Escape::class,
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['fluid_template'] = [
            'frontend' => VariableFrontend::class,
            'backend' => TransientMemoryBackend::class,
        ];

        $this->singletonInstancesBackup = GeneralUtility::getSingletonInstances();

        foreach ($this->singletonInstances as $className => $instance) {
            GeneralUtility::setSingletonInstance($className, $instance);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        GeneralUtility::resetSingletonInstances($this->singletonInstancesBackup);
        GeneralUtility::purgeInstances();

        unset($GLOBALS['TSFE']);
    }

    /**
     * Helper function to call protected or private methods
     *
     * @param object $object The object to be invoked
     * @param string $name the name of the method to call
     * @param mixed $arguments
     * @return mixed
     */
    protected function callInaccessibleMethod($object, $name, ...$arguments)
    {
        $reflectionObject = new \ReflectionObject($object);
        $reflectionMethod = $reflectionObject->getMethod($name);
        $reflectionMethod->setAccessible(true);
        return $reflectionMethod->invokeArgs($object, $arguments);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @param mixed $value
     */
    protected function setInaccessiblePropertyValue(object $object, string $propertyName, $value): void
    {
        $reflectionProperty = new \ReflectionProperty($object, $propertyName);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @return mixed
     */
    protected function getInaccessiblePropertyValue(object $object, string $propertyName)
    {
        $reflectionProperty = new \ReflectionProperty($object, $propertyName);
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue($object);
    }

    /**
     * @param string $propertyName
     * @param mixed $value
     * @param mixed $expectedValue
     * @param mixed $expectsChaining
     */
    protected function assertGetterAndSetterWorks($propertyName, $value, $expectedValue = null, $expectsChaining = false)
    {
        $instance = $this->createInstance();
        $setter = 'set' . ucfirst($propertyName);
        $getter = 'get' . ucfirst($propertyName);
        $chained = $instance->$setter($value);
        $expectedValue = $expectedValue ?? $value;
        if ($expectsChaining === true) {
            self::assertSame($instance, $chained);
        } else {
            self::assertNull($chained);
        }
        self::assertEquals($expectedValue, $instance->$getter());
    }

    /**
     * @param mixed $value
     */
    protected function assertIsInteger($value)
    {
        $isIntegerConstraint = new IsType(IsType::TYPE_INT);
        self::assertThat($value, $isIntegerConstraint);
    }

    /**
     * @param mixed $value
     */
    protected function assertIsBoolean($value)
    {
        $isBooleanConstraint = new IsType(IsType::TYPE_BOOL);
        self::assertThat($value, $isBooleanConstraint);
    }

    /**
     * @param mixed $value
     */
    protected function assertIsValidAndWorkingFormObject($value)
    {
        self::assertInstanceOf(Form::class, $value);
        self::assertInstanceOf(Form\FormInterface::class, $value);
        self::assertInstanceOf(Form\ContainerInterface::class, $value);
        /** @var Form $value */
        $structure = $value->build();
        self::assertIsArray($structure);
        // scan for and attempt building of closures in structure
        foreach ($value->getFields() as $field) {
            if ($field instanceof Custom === true) {
                $closure = $field->getClosure();
                $output = $closure($field->getArguments());
                self::assertNotEmpty($output);
            }
        }
    }

    /**
     * @param mixed $value
     */
    protected function assertIsValidAndWorkingGridObject($value)
    {
        self::assertInstanceOf(Form\Container\Grid::class, $value);
        self::assertInstanceOf(Form\ContainerInterface::class, $value);
        /** @var Form $value */
        $structure = $value->build();
        self::assertIsArray($structure);
    }

    /**
     * @param string $shorthandTemplatePath
     * @return string
     */
    protected function getAbsoluteFixtureTemplatePathAndFilename($shorthandTemplatePath)
    {
        return realpath(str_replace('EXT:vhs/', './', $shorthandTemplatePath));
    }

    /**
     * @param array $methods
     * @return FluxService
     */
    protected function createFluxServiceInstance($methods = ['dummy'])
    {
        /** @var FluxService $fluxService */
        $fluxService = $this->getMockBuilder(FluxService::class)->setMethods($methods)->disableOriginalConstructor()->getMock();
        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)->disableOriginalConstructor()->getMock();
        $fluxService->injectConfigurationManager($configurationManager);
        return $fluxService;
    }

    /**
     * @return string
     */
    protected function createInstanceClassName()
    {
        return str_replace('Tests\\Unit\\', '', substr(static::class, 0, -4));
    }

    /**
     * @return object
     */
    protected function createInstance()
    {
        $instanceClassName = $this->createInstanceClassName();
        return new $instanceClassName();
    }
}
