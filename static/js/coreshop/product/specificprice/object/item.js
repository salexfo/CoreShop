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

pimcore.registerNS('pimcore.plugin.coreshop.product.specificprice.object');
pimcore.registerNS('pimcore.plugin.coreshop.product.specificprice.object.item');
pimcore.plugin.coreshop.product.specificprice.object.item = Class.create(pimcore.plugin.coreshop.rules.item, {

    iconCls : 'coreshop_icon_price_rule',

    getPanel: function () {
        this.panel = new Ext.TabPanel({
            activeTab: 0,
            title: this.data.name,
            closable: true,
            deferredRender: false,
            forceLayout: true,
            iconCls : this.iconCls,
            items: this.getItems()
        });

        return this.panel;
    },

    initPanel: function () {
        this.panel = this.getPanel();

        this.parentPanel.getTabPanel().add(this.panel);
    },

    getSettings: function () {
        var data = this.data;

        this.settingsForm = Ext.create('Ext.form.Panel', {
            iconCls: 'coreshop_icon_settings',
            title: t('settings'),
            bodyStyle: 'padding:10px;',
            autoScroll: true,
            border:false,
            items: [{
                xtype: 'textfield',
                name: 'name',
                fieldLabel: t('name'),
                width: 250,
                value: data.name
            }, {
                xtype: 'numberfield',
                name: 'priority',
                fieldLabel: t('coreshop_priority'),
                value: this.data.priority ? this.data.priority : 0,
                width : 250
            }, {
                xtype: 'checkbox',
                name: 'inherit',
                fieldLabel: t('coreshop_inherit'),
                checked: this.data.inherit == '1'
            }]
        });

        return this.settingsForm;
    },

    getSaveData : function () {
        if(this.settingsForm.getEl()) {
            var saveData = {};

            // general settings
            saveData['id'] = this.data.id;
            saveData['settings'] = this.settingsForm.getForm().getFieldValues();
            saveData['conditions'] = this.conditions.getConditionsData();
            saveData['actions'] = this.actions.getActionsData();

            return saveData;
        }

        return {};
    },

    isDirty : function() {
        if(this.settingsForm.form.monitor && this.settingsForm.getForm().isDirty()) {
            return true;
        }

        if(this.conditions.isDirty()) {
            return true;
        }

        if(this.actions.isDirty()) {
            return true;
        }

        return false;
    }
});
