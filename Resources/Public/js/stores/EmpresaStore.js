Ext.define('Empresa', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'string'
    }, {
        name: 'name',
        type: 'string'
    }],
    validations: [{
        type: 'length',
        field: 'name',
        min: 1
    }]
});
Ext.create('Ext.data.Store', {
    storeId:'empresaStore',
        autoLoad: true,
        autoSync: true,
        model: 'Empresa',
        proxy: {
            type: 'ajax',
            url: '/fe/list.json',
            reader: {
                type: 'json',
                root: 'data'
            },
            api: {
                create: '/fe/save',
                update: '/fe/save',
                destroy: '/fe/delete'
            }
        },
        listeners:{
            add: function(store, records, index){
                alert('store');
            }
        }
    });