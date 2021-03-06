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

namespace CoreShop\Model\Carrier\ShippingRule\Condition;

use CoreShop\Model;
use CoreShop\Model\Carrier\ShippingRule as CarrierShippingRule;

/**
 * Class Countries
 * @package CoreShop\Model\Carrier\ShippingRule\Condition
 */
class Countries extends AbstractCondition
{
    /**
     * @var string
     */
    public static $type = 'countries';

    /**
     * @var array
     */
    public $countries;

    /**
     * Check if Cart is Valid for Condition.
     *
     * @param Model\Carrier $carrier
     * @param Model\Cart $cart
     * @param Model\User\Address $address
     * @param CarrierShippingRule $shippingRule
     *
     * @return mixed
     */
    public function checkCondition(Model\Carrier $carrier, Model\Cart $cart, Model\User\Address $address, CarrierShippingRule $shippingRule)
    {
        if ($address->getCountry() instanceof Model\Country) {
            foreach ($this->getCountries() as $country) {
                if ($address->getCountry()->getId() === $country) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @param array $countries
     */
    public function setCountries($countries)
    {
        $this->countries = $countries;
    }
}
