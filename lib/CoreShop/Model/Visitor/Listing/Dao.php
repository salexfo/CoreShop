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

namespace CoreShop\Model\Visitor\Listing;

use CoreShop\Model\Listing;

/**
 * Class Dao
 * @package CoreShop\Model\Visitor\Listing
 */
class Dao extends Listing\Dao\AbstractDao
{
    /**
     * Object class name.
     *
     * @var string
     */
    protected $modelClass = '\\CoreShop\\Model\\Visitor';
}
