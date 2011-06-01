Ext.create('Ext.data.Store', {
    storeId:'empresaStore',
        autoLoad: true,
        autoSync: true,
        fields: ['id','name'],
        proxy: {
            type: 'rest',
            url: empresaListAsJsonUrl,
            reader: {
                type: 'json',
                root: 'data'
            },
            writer: {
                type: 'json'
            }
        }
    });
/*
EmpresaStore = Ext.extend(Ext.data.JsonStore, {
    constructor: function(cfg) {
        cfg = cfg || {};
        EmpresaStore.superclass.constructor.call(this, Ext.apply({
            storeId: 'EmpresaStore',
            url: empresaListAsJsonUrl,
            root: 'data',
            idProperty: 'id',
            autoLoad: true,
            fields: ['id','name']
        }, cfg));
    }
});
new EmpresaStore();

EmpresaRecord = Ext.data.Record.create([
    {
        name: 'empresa_id',
        type: 'string'
    },
    {
        name: 'name',
        type: 'string'
    }
]);*/
