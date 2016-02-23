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
if (!defined("CORESHOP_PATH")) {
    define("CORESHOP_PATH", PIMCORE_PLUGINS_PATH . "/CoreShop");
}
if (!defined("CORESHOP_PLUGIN_CONFIG")) {
    define("CORESHOP_PLUGIN_CONFIG", CORESHOP_PATH . "/plugin.xml");
}
if (!defined("CORESHOP_CONFIGURATION_PATH")) {
    define("CORESHOP_CONFIGURATION_PATH", PIMCORE_CONFIGURATION_DIRECTORY);
}
if (!defined("CORESHOP_TEMPORARY_DIRECTORY")) {
    define("CORESHOP_TEMPORARY_DIRECTORY", PIMCORE_TEMPORARY_DIRECTORY);
}
if (!defined("CORESHOP_UPDATE_DIRECTORY")) {
    define("CORESHOP_UPDATE_DIRECTORY", CORESHOP_PATH . "/update");
}

if (!defined("CORESHOP_BUILD_DIRECTORY")) {
    define("CORESHOP_BUILD_DIRECTORY", CORESHOP_PATH . "/build");
}


if (Pimcore\Tool::classExists("CoreShop\\Plugin")) {
    $plugin = new CoreShop\Plugin();

    if ($plugin::isInstalled()) {
        $template = \CoreShop\Model\Configuration::get("SYSTEM.TEMPLATE.NAME");

        if (!$template) {
            die("No template configured");
        }

        $templateBasePath = '';

        if (is_dir(PIMCORE_WEBSITE_PATH . '/views/coreshop/template/' . $template)) {
            $templateBasePath = PIMCORE_WEBSITE_PATH . "/views/coreshop/template";
            $templateResources = "/website/views/coreshop/template/" . $template . "/static/";
        }

        if (!defined("CORESHOP_TEMPLATE_BASE_PATH")) {
            define("CORESHOP_TEMPLATE_BASE_PATH", $templateBasePath);
        }
        if (!defined("CORESHOP_TEMPLATE_NAME")) {
            define("CORESHOP_TEMPLATE_NAME", $template);
        }
        if (!defined("CORESHOP_TEMPLATE_PATH")) {
            define("CORESHOP_TEMPLATE_PATH", CORESHOP_TEMPLATE_BASE_PATH . "/" . $template);
        }
        if (!defined("CORESHOP_TEMPLATE_RESOURCES")) {
            define("CORESHOP_TEMPLATE_RESOURCES", $templateResources);
        }

        if (!is_dir(CORESHOP_TEMPLATE_PATH)) {
            die(sprintf("Template with name '%s' not found. (%s)", $template, CORESHOP_TEMPLATE_PATH));
        }
    }
}
