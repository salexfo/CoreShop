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

use CoreShop\Model;
use CoreShop\Controller\Action\Admin;

/**
 * Class CoreShop_Admin_SettingsController
 */
class CoreShop_Admin_SettingsController extends Admin
{
    public function init()
    {
        parent::init();

        // check permissions
        $notRestrictedActions = ['get-settings'];
        if (!in_array($this->getParam('action'), $notRestrictedActions)) {
            $this->checkPermission('coreshop_permission_settings');
        }
    }

    public function getSettingsAction()
    {
        $result = [];
        $shops = Model\Shop::getList();
        $valueArray = [];
        $systemSettings = [];

        foreach ($shops as $shop) {
            $config = new Model\Configuration\Listing();
            $config->setFilter(function ($entry) use ($shop) {
                if (startsWith($entry['key'], 'SYSTEM.') && ($entry['shop'] === null || $entry['shop'] === $shop->getId())) {
                    return true;
                }

                return false;
            });

            $configurations = $config->getConfigurations();

            if (is_array($configurations)) {
                foreach ($configurations as $c) {
                    if (in_array($c->getKey(), Model\Configuration::getSystemKeys())) {
                        $systemSettings[$c->getKey()] = $c->getData();
                    } else {
                        $valueArray[$shop->getId()][$c->getKey()] = $c->getData();
                    }
                }
            }
        }

        $result['systemSettings'] = $systemSettings;

        if (Model\Configuration::get("SYSTEM.ISINSTALLED")) {
            $pimcoreClasses = \CoreShop::getPimcoreClasses();
            $classMapping = [];

            foreach ($pimcoreClasses as $key=>$value) {
                $classMapping[$key] = $value['pimcoreClass'];
            }

            foreach ($classMapping as $key => &$class) {
                $class = str_replace('Pimcore\\Model\\Object\\', '', $class);
                $class = str_replace('\\', '', $class);
            }

            $result['classMapping'] = $classMapping;
            $result['multishop'] = Model\Configuration::multiShopEnabled();
            $result['defaultShop'] = Model\Shop::getDefaultShop()->getId();
            $result['coreshop'] = $valueArray;
            $result['orderStates'] = Model\Order\State::getValidOrderStates();
        }

        $pluginConfig = \Pimcore\ExtensionManager::getPluginConfig('CoreShop');

        $result['plugin'] = $pluginConfig['plugin'];

        $this->_helper->json($result);
    }

    public function getAction()
    {
        $shops = Model\Shop::getList();
        $valueArray = [];
        $systemValues = [];
            
        foreach ($shops as $shop) {
            $shopValues = [];

            $config = new Model\Configuration\Listing();
            $config->setFilter(function ($entry) use ($shop) {
                if (startsWith($entry['key'], 'SYSTEM.') && ($entry['shopId'] === null || $entry['shopId'] === intval($shop->getId()))) {
                    return true;
                }

                return false;
            });

            $configurations = $config->getConfigurations();

            if (is_array($configurations)) {
                foreach ($configurations as $c) {
                    if (in_array($c->getKey(), Model\Configuration::getSystemKeys())) {
                        $systemValues[$c->getKey()] = $c->getData();
                    } else {
                        $shopValues[$c->getKey()] = $c->getData();
                    }
                }
            }

            $valueArray[$shop->getId()] = $shopValues;
        }

        $response = [
            'values' => $valueArray,
            'systemValues' => $systemValues
        ];

        $this->_helper->json($response);
        $this->_helper->json(false);
    }

    public function setAction()
    {
        $systemValues = \Zend_Json::decode($this->getParam('systemValues'));
        $values = \Zend_Json::decode($this->getParam('values'));
        $values = array_htmlspecialchars($values);
        $diff = [];

        if (Model\Configuration::multiShopEnabled()) {
            $diff = call_user_func_array("array_diff_assoc_recursive", $values);
        }


        if (Model\Configuration::multiShopEnabled()) {
            foreach ($values as $shop => $shopValues) {
                foreach ($shopValues as $key => $val) {
                    Model\Configuration::remove($key);
                }

                break;
            }
        }

        foreach ($values as $shopId => $shopValues) {
            foreach ($shopValues as $key => $value) {
                if (array_key_exists($key, $diff)) {
                    Model\Configuration::set($key, $value, $shopId);
                } else {
                    Model\Configuration::set($key, $value);
                }
            }
        }

        foreach ($systemValues as $key => $value) {
            Model\Configuration::set($key, $value);
        }

        $this->_helper->json(['success' => true]);
    }
}
