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

pimcore.registerNS('pimcore.plugin.coreshop.report.abstract');
pimcore.plugin.coreshop.report.abstract = Class.create(pimcore.report.abstract, {

    url : '',
    remoteSort : false,

    matchType: function (type) {
        var types = ['global'];
        if (pimcore.report.abstract.prototype.matchTypeValidate(type, types)) {
            return true;
        }

        return false;
    },

    getName: function () {
        return 'coreshop';
    },

    getIconCls: function () {
        return 'coreshop_icon_report';
    },

    getGrid : function () {
        return false;
    },

    getFromField : function () {
        return this.panel.down('[name=from]');
    },

    getToField : function () {
        return this.panel.down('[name=to]');
    },

    getFromStartDate : function() {
        return new Date(new Date().getFullYear(), 0, 1);
    },

    getToStartDate : function() {
        return new Date(new Date().getFullYear(), 11, 31);
    },

    showPaginator: function() {
        return false;
    },

    getDocketItemsForPanel: function() {

        return [
            {
                xtype: 'toolbar',
                dock: 'top',
                items: this.getFilterFields()
            }
        ];
    },

    getPanel: function () {

        if (!this.panel) {

            var bbar = null;

            if(this.showPaginator() !== false) {
                bbar = pimcore.helpers.grid.buildDefaultPagingToolbar(this.getStore());
            }

            this.panel = new Ext.Panel({
                title: this.getName(),
                layout: 'fit',
                border: false,
                items: [],
                bbar: bbar,
                dockedItems: this.getDocketItemsForPanel()
            });

            grid = this.getGrid();

            if (grid) {
                this.panel.add(grid);
            }

            this.filter();
        }

        return this.panel;
    },

    getFilterFields : function () {
        return [
            {
                xtype : 'button',
                text : t('coreshop_report_day'),
                handler : function () {
                    var today = new Date();
                    var yesterday = new Date();

                    yesterday.setDate(today.getDate() - 1);

                    this.getFromField().setValue(yesterday);
                    this.getToField().setValue(today);

                    this.filter();
                }.bind(this)
            },
            {
                xtype : 'button',
                text : t('coreshop_report_month'),
                handler : function () {
                    var now = new Date();

                    this.getFromField().setValue(new Date(now.getFullYear(), now.getMonth(), 1));
                    this.getToField().setValue(new Date(now.getFullYear(), now.getMonth() + 1, 0));

                    this.filter();
                }.bind(this)
            },
            {
                xtype : 'button',
                text : t('coreshop_report_year'),
                handler : function () {
                    var now = new Date();

                    this.getFromField().setValue(new Date(now.getFullYear(), 0, 1));
                    this.getToField().setValue(new Date(now.getFullYear(), 11, 31));

                    this.filter();
                }.bind(this)
            },
            {
                xtype : 'button',
                text : t('coreshop_report_day_minus'),
                handler : function () {
                    var today = new Date();
                    var yesterday = new Date();

                    today.setDate(today.getDate() - 1);
                    yesterday.setDate(today.getDate() - 1);

                    this.getFromField().setValue(yesterday);
                    this.getToField().setValue(today);

                    this.filter();
                }.bind(this)
            },
            {
                xtype : 'button',
                text : t('coreshop_report_month_minus'),
                handler : function () {
                    var now = new Date();

                    this.getFromField().setValue(new Date(now.getFullYear(), now.getMonth() - 1, 1));
                    this.getToField().setValue(new Date(now.getFullYear(), now.getMonth(), 0));

                    this.filter();
                }.bind(this)
            },
            {
                xtype : 'button',
                text : t('coreshop_report_year_minus'),
                handler : function () {
                    var now = new Date();

                    this.getFromField().setValue(new Date(now.getFullYear() - 1, 0, 1));
                    this.getToField().setValue(new Date(now.getFullYear() - 1, 11, 31));

                    this.filter();
                }.bind(this)
            },
            '->',
            {
                xtype: 'datefield',
                fieldLabel: t('coreshop_report_year_from'),
                name : 'from',
                value : this.getFromStartDate()
            },
            {
                xtype: 'datefield',
                fieldLabel: t('coreshop_report_year_to'),
                name : 'to',
                value : this.getToStartDate()
            },
            {
                xtype : 'button',
                text : t('coreshop_report_filter'),
                handler : function () {
                    this.filter();
                }.bind(this)
            }
        ];
    },

    getStore : function () {
        if (!this.store) {
            var me = this,
                fields = ['timestamp', 'text', 'data'];

            if(Ext.isFunction(this.getStoreFields)) {
                fields = Ext.apply(fields, this.getStoreFields());
            }

            this.store = new Ext.data.Store({
                autoDestroy: true,
                remoteSort : this.remoteSort,
                proxy: {
                    type: 'ajax',
                    url: this.url,
                    actionMethods : {
                        read : 'POST'
                    },
                    reader: {
                        type: 'json',
                        rootProperty: 'data',
                        totalProperty : 'total'
                    }
                },
                fields: fields
            });

            this.store.on('beforeload', function(store, operation) {
                store.getProxy().setExtraParams(me.getFilterParams());
            });
        }

        return this.store;
    },

    filter : function () {
        this.getStore().load();
    },

    getFilterParams : function () {
        return {
            'filters[from]' : this.getFromField().getValue().getTime() / 1000,
            'filters[to]' : this.getToField().getValue().getTime() / 1000
        };
    }
});

