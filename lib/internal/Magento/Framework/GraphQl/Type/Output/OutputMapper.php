<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types = 1);

namespace Magento\Framework\GraphQl\Type\Output;

use Magento\Framework\GraphQl\Config\ConfigInterface;
use Magento\Framework\GraphQl\Type\Definition\OutputType;
use Magento\Framework\GraphQl\TypeFactory;

/**
 * Map type names to their output type/interface classes.
 */
class OutputMapper
{
    /**
     * @var OutputFactory
     */
    private $outputFactory;

    /**
     * @var TypeFactory
     */
    private $typeFactory;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param OutputFactory $outputFactory
     * @param TypeFactory $typeFactory
     * @param ConfigInterface $config
     */
    public function __construct(
        OutputFactory $outputFactory,
        TypeFactory $typeFactory,
        ConfigInterface $config
    ) {
        $this->outputFactory = $outputFactory;
        $this->config = $config;
        $this->typeFactory = $typeFactory;
    }

    /**
     * Return type object found based off type name input.
     *
     * @param string $type
     * @return OutputType
     */
    public function getTypeObject(string $type) : OutputType
    {
        $instance = $this->typeFactory->getScalar($type);
        if (!$instance) {
            $configElement = $this->config->getTypeStructure($type);
            $instance = $this->outputFactory->create($configElement);
        }
        return $instance;
    }

    /**
     * Retrieve interface object if found, otherwise return null.
     *
     * @param string $type
     * @return OutputInterfaceObject|null
     */
    public function getInterface(string $type) : ?OutputInterfaceObject
    {
        $instance = $this->typeFactory->getScalar($type);
        if (!$instance) {
            $configElement = $this->config->getTypeStructure($type);
            $instance = $this->outputFactory->create($configElement);
        }
        if ($instance instanceof OutputInterfaceObject) {
            return $instance;
        } else {
            return null;
        }
    }
}
