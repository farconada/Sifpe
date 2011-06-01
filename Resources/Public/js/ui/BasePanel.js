Ext.define('Sifpe.grid.Base', {
    extend: 'Ext.grid.Panel',
    height: 400,
    autoWidth: true,
    style: 'padding: 20px',
    title: 'Panel Base',
    selType: 'rowmodel',
    plugins: [
        Ext.create('Ext.grid.plugin.RowEditing', {
            clicksToEdit: 1
        })
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            items: [
                {
                    text: 'Nuevo',
                    iconCls: 'icon-add',
                    handler: function() {
                        /*// empty record
                        store.insert(0, new Person());
                        rowEditing.startEdit(0, 0);*/
                    }
                },
                '-',
                {
                    text: 'Borrar',
                    iconCls: 'icon-delete',
                    handler: function() {
                       /* var selection = grid.getView().getSelectionModel().getSelection()[0];
                        if (selection) {
                            store.remove(selection);
                        }*/
                    }
                }
            ]
        }
    ]
});
