Ext.define('Cuenta', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'string'
    }, {
        name: 'name',
        type: 'string'
    },
            {
        name: 'grupo_cuenta_id',
        type: 'string'
    }
    ],
    validations: [{
        type: 'length',
        field: 'name',
        min: 1
    }],
    associations: [
            {type: 'belongsTo', model: 'GrupoCuenta',    foreignKey: 'grupo_cuenta_id', name: 'grupo'}
        ]

});
Ext.create('Ext.data.Store', {
    storeId:'cuentaStore',
        autoLoad: true,
        pageSize: 1,
        totalProperty: 'total',
        autoSync: true,
        model: 'Cuenta',
        proxy: {
            type: 'ajax',
            encode: true,
            url: baseUrl + 'cuenta/list',
            reader: {
                type: 'json',
                root: 'data'
            },
            writer: {
              type: 'json',
              encode: true,
              root: 'cuenta'
            },
            api: {
                create: baseUrl + 'cuenta/save',
                update: baseUrl + 'cuenta/save',
                destroy: baseUrl + 'cuenta/delete'
            }
        }
    });