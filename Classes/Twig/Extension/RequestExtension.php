<?php
namespace DMK\T3twig\Twig\Extension;

/***************************************************************
 * Copyright notice
 *
 * (c) 2016 DMK E-BUSINESS GmbH <dev@dmk-ebusiness.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use DMK\T3twig\Twig\EnvironmentTwig;

/**
 * Class TSParserExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class RequestExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                't3gp',
                [$this, 'renderGetPost'],
                ['needs_environment' => true]
            ),
        ];
    }

    /**
     * @param \DMK\T3twig\Twig\EnvironmentTwig $env
     * @param string $paramName
     * @param array $arguments
     *
     * @return mixed|null
     */
    public function renderGetPost(
        EnvironmentTwig $env,
        $paramName,
        array $arguments = []
    ) {
        return $this->performCommand(
            function (\Tx_Rnbase_Domain_Model_Data $arguments) use ($env, $paramName) {
                $paths = explode('|', $paramName);
                $segment = array_shift($paths);

                if ($arguments->getGlobal()) {
                    $param = \tx_rnbase_parameters::getPostOrGetParameter($segment);
                } else {
                    $param = $env->getParameters()->get($segment);
                }

                while (($segment = array_shift($paths)) !== null) {
                    if (!isset($param[$segment])) {
                        return null;
                    }
                    $param = $param[$segment];
                }

                // reduce empty parameters
                if (is_array($param) && $arguments->getNoEmpty()) {
                    foreach (array_keys($param, '') as $key) {
                        unset($param[$key]);
                    }
                }

                return $param;
            },
            $env,
            $arguments
        );
    }

    /**
     * Get Extension name
     *
     * @return string
     */
    public function getName()
    {
        return 't3twig_requestExtension';
    }
}
