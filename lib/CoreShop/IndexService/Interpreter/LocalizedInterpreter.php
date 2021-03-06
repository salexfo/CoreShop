<?php
/**
 * CoreShop.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\IndexService\Interpreter;

use CoreShop\Exception\UnsupportedException;
use CoreShop\Model\Index\Config\Column\AbstractColumn;

/**
 * Class LocalizedInterpreter
 * @package CoreShop\IndexService\Interpreter
 */
abstract class LocalizedInterpreter extends AbstractInterpreter
{
    /**
     * @var string
     */
    public static $type = 'localizedInterpreter';

    /**
     * interpret value.
     *
     * @param mixed $value
     * @param AbstractColumn $config
     *
     * @return mixed
     *
     * @throws UnsupportedException
     */
    public function interpret($value, $config = null)
    {
        throw new UnsupportedException('Not implemented in abstract');
    }

    /**
     * interpret value for language.
     *
     * @param mixed $language
     * @param mixed $value
     * @param array $config
     *
     * @return mixed
     *
     * @throws UnsupportedException
     */
    public function interpretForLanguage($language, $value, $config = null)
    {
        throw new UnsupportedException('Not implemented in abstract');
    }
}
