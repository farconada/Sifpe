Ext.create('Ext.data.Store', {
    storeId:'empresaStore',
        autoLoad: true,
        autoSync: false,
        fields: [
            {name: 'id', type: 'string'},
            {name: 'name',  type: 'string'}
        ],
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