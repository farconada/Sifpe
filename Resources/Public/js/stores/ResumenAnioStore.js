Ext.create('Ext.data.Store', {
    storeId:'resumenAnioStore',
    autoLoad: true,
    pageSize: 1,
    fields: ['mes','cantidad','cantidad_anterior'],
    proxy: {
        type: 'ajax',
        encode: true,
        url: baseUrl + '/gasto/listResumenAnual',
        reader: {
            type: 'json',
            root: 'data'
        }
    }
});