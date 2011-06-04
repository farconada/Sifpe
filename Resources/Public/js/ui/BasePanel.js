Ext.define('Sifpe.grid.Base', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.basegrid',

    initComponent: function() {
        this.addEvents('addclick', 'deleteclick');
        this.editing = Ext.create('Ext.grid.plugin.RowEditing', {
            clicksToEdit: 2
        });

        Ext.apply(this, {
            height: 400,
            autoWidth: true,
            style: 'padding: 20px',
            selType: 'rowmodel',
            plugins: [this.editing],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    items: [
                        {
                            id: 'btnAdd',
                            text: 'Nuevo',
                            iconCls: 'icon-add',
                            scope: this,
                            handler: this.onAddClick
                        },
                        '-',
                        {
                            iconCls: 'icon-delete',
                            text: 'Borrar',
                            disabled: true,
                            itemId: 'delete',
                            scope: this,
                            handler: this.onDeleteClick
                        }
                    ]
                }
            ],
            bbar: Ext.create('Ext.PagingToolbar', {
                store: this.store,
                displayInfo: true,
                displayMsg: 'Mostrando elementos {0} - {1} de {2}',
                emptyMsg: "Nada que mostrar"
            })
        });
        this.callParent();
        this.getSelectionModel().on('selectionchange', this.onSelectChange, this);
    },
    onSelectChange: function(selModel, selections) {
        this.down('#delete').setDisabled(selections.length === 0);
    },
    onDeleteClick: function() {
        this.fireEvent('deleteclick', this);
        var selection = this.getView().getSelectionModel().getSelection()[0];
        if (selection) {
            this.store.remove(selection);
            this.store.sync();
        }
    },
    onAddClick: function() {
        this.fireEvent('addclick', this);
    },
    onEdit: function(editor, e) {
        alert('Editado');
        this.store.sync();
    }

});
