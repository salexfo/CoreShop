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

namespace CoreShop\IndexService\Getter;

use CoreShop\Exception\UnsupportedException;
use CoreShop\Model\Index\Config\Column;
use CoreShop\Model\Product;

/**
 * Class Localizedfield
 * @package CoreShop\IndexService\Getter
 */
class Localizedfield extends AbstractGetter
{
    /**
     * @var string
     */
    public static $type = 'localizedfield';

    /**
     * get value.
     *
     * @param $object
     * @param Column $config
     *
     * @return mixed
     *
     * @throws UnsupportedException
     */
    public function get(Product $object, Column $config)
    {
        $language = null;
        
        if (\Zend_Registry::isRegistered("Zend_Locale")) {
            $language = \CoreShop::getTools()->getLocale();
        }

        $getter = 'get'.ucfirst($config->getKey());

        return $object->$getter($language);
    }
}
