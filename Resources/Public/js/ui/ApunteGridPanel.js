Ext.define('Sifpe.grid.Apunte', {
    extend: 'Sifpe.grid.Base',
    alias: 'widget.basegrid',
    initComponent: function() {
        this.groupingFeature = Ext.create('Ext.grid.feature.GroupingSummary', {
            id: 'group',
            groupHeaderTpl: '({rows.length} Apunte{[values.rows.length > 1 ? "s" : ""]})'
        });
        Ext.apply(this, {
            features: [this.groupingFeature],
            fbar  : ['->', {
                text:'Desagrupar',
                iconCls: 'icon-clear-group',
                handler : function() {
                    this.groupingFeature.disable();
                }
            }],
            columns: [
                {
                    header: 'fecha',
                    dataIndex: 'fecha',
                    xtype: 'datecolumn',
                    format: 'Y-m-d',
                    field: {
                        xtype: 'datefield',
                        allowBlank: false,
                        format: 'd-m-Y',
                        minValue: '01/01/2006',
                        minText: 'Cannot have a start date before the company existed!',
                        maxValue: Ext.Date.format(new Date(), 'm/d/Y')
                    }
                },
                {
                    header: 'empresa',
                    dataIndex: 'empresa',
                    flex: true,
                    renderer: getEmpresaFromId,
                    editor: {
                        xtype: 'combo',
                        editable: false,
                        store:  Ext.data.StoreManager.lookup('empresaStore'),
                        queryMode: 'local',
                        displayField: 'name',
                        valueField: 'id'
                    }
                },
                {
                    header: 'cuenta',
                    dataIndex: 'cuenta',
                    flex: true,
                    renderer: getCuentaFromId,
                    editor: {
                        xtype: 'combo',
                        editable: false,
                        store:  Ext.data.StoreManager.lookup('cuentaStore'),
                        queryMode: 'local',
                        displayField: 'name',
                        valueField: 'id'
                    }
                },
                {
                    header: 'cantidad',
                    dataIndex: 'cantidad',
                    align: 'right',
                    type: 'numbercolumn',
                    summaryType: 'sum',
                    summaryRenderer: function(v, params, data) {
                        return 'Total ' + v;
                    },
                    editor: {
                        xtype: 'textfield',
                        allowBlank: false
                    }
                },
                {
                    header: 'notas',
                    dataIndex: 'notas',
                    flex: true,
                    editor: {
                        type: 'textfield'
                    }
                }
            ]
        });
        this.callParent();

    },
    onAddClick: function() {
        this.fireEvent('addclick', this);
        edit = this.editing;
        edit.cancelEdit();
        this.store.insert(0, new Apunte());
        edit.startEdit(0, 0);
    }


});