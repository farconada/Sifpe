Ext.create('Ext.data.Store', {
    storeId:'resumenMesStore',
    autoLoad: false,
    pageSize: 1,
    fields: ['ingresos','gastos',
        {   name:'porcentaje',
            mapping:0,
            convert: function (value, record) {
                return (record.data.gastos/record.data.ingresos)*100;
            }
        }
    ],
    proxy: {
        type: 'ajax',
        encode: true,
        url: baseUrl + 'gasto/listResumenMes',
        reader: {
            type: 'json',
            root: 'data'
        }
    }
});