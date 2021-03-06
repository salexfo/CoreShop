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

pimcore.registerNS('pimcore.plugin.coreshop.mail.rules.actions.orderMail');

pimcore.plugin.coreshop.mail.rules.actions.orderMail = Class.create(pimcore.plugin.coreshop.mail.rules.actions.mail, {

    type : 'orderMail',

    fields : {},

    getForm : function ($super) {
        var form = $super(),
            me = this;

        this.sendInvoices = Ext.create({
            fieldLabel: t('coreshop_mail_rule_send_invoices'),
            xtype: 'checkbox',
            name: 'sendInvoices',
            checked: this.data ? this.data.sendInvoices : false
        });

        this.sendShipments = Ext.create({
            fieldLabel: t('coreshop_mail_rule_send_shipments'),
            xtype: 'checkbox',
            name: 'sendShipments',
            checked: this.data ? this.data.sendShipments : false
        });

        form.add([this.sendInvoices, this.sendShipments]);

        return form;
    },

    getValues : function($super) {
        var values = $super();

        values = Ext.applyIf({
            'sendInvoices' : this.sendInvoices.getValue(),
            'sendShipments' : this.sendShipments.getValue()
        }, values);

        return values;
    }
});
