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

pimcore.registerNS('pimcore.plugin.coreshop.shops.item');
pimcore.plugin.coreshop.shops.item = Class.create(pimcore.plugin.coreshop.abstract.item, {

    iconCls : 'coreshop_icon_shop',

    url : {
        save : '/plugin/CoreShop/admin_shop/save'
    },

    getItems : function () {
        return [this.getFormPanel()];
    },

    getFormPanel : function () {
        this.store = new Ext.data.Store({
            restful: false,
            idProperty: 'id',
            remoteSort: true,
            proxy: {
                type: 'ajax',
                url: '/plugin/CoreShop/admin_shop/list-sites',
                reader: {
                    type: 'json'
                }
            }
        });

        this.store.load();

        this.formPanel = new Ext.form.Panel({
            bodyStyle:'padding:20px 5px 20px 5px;',
            border: false,
            region : 'center',
            autoScroll: true,
            forceLayout: true,
            defaults: {
                forceLayout: true
            },
            buttons: [
                {
                    text: t('save'),
                    handler: this.save.bind(this),
                    iconCls: 'pimcore_icon_apply'
                }
            ],
            items: [
                {
                    xtype:'fieldset',
                    autoHeight:true,
                    labelWidth: 250,
                    defaultType: 'textfield',
                    defaults: { width: 300 },
                    items :[
                        {
                            fieldLabel: t('name'),
                            name: 'name',
                            value: this.data.name
                        },
                        {
                            fieldLabel: t('coreshop_shop_site'),
                            xtype: 'combo',
                            name: 'siteId',
                            width: 400,
                            store: this.store,
                            displayField: 'name',
                            valueField: 'id',
                            triggerAction: 'all',
                            typeAhead: false,
                            editable: false,
                            forceSelection: true,
                            queryMode: 'local',
                            value: this.data.siteId
                        },
                        {
                            fieldLabel: t('coreshop_shop_template'),
                            name: 'template',
                            value: this.data.template
                        }
                    ]
                }
            ]
        });

        return this.formPanel;
    },

    getSaveData : function () {
        return {
            data: Ext.encode(this.formPanel.getForm().getFieldValues())
        };
    }
});
