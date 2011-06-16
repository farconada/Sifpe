Ext.define('Apunte', {
    extend: 'Ext.data.Model',
    fields: [
        {
            name: 'id',
            type: 'string'
        },
        {
            name: 'fecha',
            type: 'date'
        },
        {
            name: 'notas',
            type: 'string'
        },
        {
            name: 'empresa',
            type: 'string'
        },
        {
            name: 'cuenta',
            type: 'string'
        },
        {
            name: 'cantidad',
            type: 'number'
        }
    ]
});