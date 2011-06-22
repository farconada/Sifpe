Ext.define('Sifpe.chart.ResumenCuenta', {
    extend: 'Ext.chart.Chart',
    alias: 'widget.resumencuentachart',

    initComponent: function() {
        Ext.apply(this, {
            animate: true,
            shadow: true,
            store: Ext.data.StoreManager.lookup('resumenCuentaStore'),
            legend: {
                position: 'right'
            },
            axes: [
                {
                    type: 'Numeric',
                    position: 'left',
                    fields: ['cantidad','cantidad_anterior'],
                    grid: true,
                    minimum: 0
                },
                {
                    type: 'Category',
                    position: 'bottom',
                    fields: ['cuenta'],
                    label: {
                        renderer: function (value) {
                            if (value.length >= 10) {
                                return value.substring(0, 15);
                            }
                            return value;
                        },
                        rotate: {
                            degrees: 20
                        }
                    }
                }
            ],
            series: [
                {
                    type: 'column',
                    axis: 'left',
                    highlight: true,
                    xField: 'cuenta',
                    yField: ['cantidad','cantidad_anterior'],
                    tips: {
                        trackMouse: true,
                        width: 74,
                        height: 38,
                        renderer: function(storeItem, item) {
                            this.setTitle(item.value[1]);
                        }
                    }
                }
            ]
        });
        this.callParent();
    }

});