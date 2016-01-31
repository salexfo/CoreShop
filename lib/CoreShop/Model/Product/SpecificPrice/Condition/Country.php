<?php
/**
 * CoreShop
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015 Dominik Pfaffenbauer (http://dominik.pfaffenbauer.at)
 * @license    http://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Model\Product\SpecificPrice\Condition;

use CoreShop\Model;
use CoreShop\Model\Country as CountryModel;
use CoreShop\Tool;

class Country extends AbstractCondition
{

    /**
     * @var int
     */
    public $country;

    /**
     * @var string
     */
    public $type = "country";

    /**
     * @return int
     */
    public function getCountry()
    {
        if (!$this->country instanceof CountryModel) {
            $this->country = CountryModel::getById($this->country);
        }

        return $this->country;
    }

    /**
     * @param int $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Check if Product is Valid for Condition
     *
     * @param Model\Product $product
     * @param Model\Product\SpecificPrice $specificPrice
     * @return boolean
     */
    public function checkCondition(Model\Product $product, Model\Product\SpecificPrice $specificPrice)
    {
        if ($this->getCountry()->getId() !== Tool::getCountry()->getId()) {
            return false;
        }

        return true;
    }
}