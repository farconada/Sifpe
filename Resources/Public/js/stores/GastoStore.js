Ext.create('Ext.data.Store', {
    storeId:'gastoStore',
    autoLoad: true,
    pageSize: 1,
    totalProperty: 'totalMeses',
    autoSync: true,
    model: 'Apunte',
    proxy: {
        type: 'ajax',
        encode: true,
        url: baseUrl + 'gasto/list',
        reader: {
            type: 'json',
            root: 'data'
        },
        writer: {
            type: 'json',
            encode: true,
            root: 'entity'
        },
        api: {
            create: baseUrl + 'gasto/save',
            update: baseUrl + 'gasto/save',
            destroy: baseUrl + 'gasto/delete'
        }
    }
});