/**
 * CoreShop
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

pimcore.registerNS('pimcore.plugin.coreshop.rules.panel');

pimcore.plugin.coreshop.rules.panel = Class.create(pimcore.plugin.coreshop.abstract.panel, {

    /**
     * @var array
     */
    conditions: [],

    /**
     * @var array
     */
    actions: [],

    /**
     * @var object
     */
    config : {},

    /**
     * constructor
     */
    initialize: function () {
        var me = this;

        Ext.Ajax.request({
            url: this.url.config,
            method: 'GET',
            success: function (result) {
                var config = Ext.decode(result.responseText);
                me.conditions = config.conditions;
                me.actions = config.actions;

                me.config = config;
            }
        });

        // create layout
        this.getLayout();

        this.panels = [];
    },

    getItemClass : function () {
        return pimcore.plugin.coreshop.rules.item;
    },

    getActions : function() {
        return this.actions;
    },

    getConfig : function() {
        return this.config;
    },

    getConditions : function() {
        return this.conditions;
    }
});
