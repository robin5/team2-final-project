/*********************************
 *       File: lab5.js
 *     Author: Robin Murray
 *      Class: CTEC 126
 * Assignment: Lab 5 
 *        Due: June 12, 2017 
 *********************************/
 
/*************************************************************
 * Outside Temperature Guage
 *************************************************************/
 
Highcharts.chart('pie-chart-36', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 0,
        plotShadow: false
    },
    title: {
        text: 'Grades',
        align: 'center',
        verticalAlign: 'middle',
        y: 40,
		floating: true
    },
	
    navigation: {
        buttonOptions: {
            enabled: false
        }
	},

	exporting: {
		enabled: false  
	},
	
	credits: {
        enabled: false
    },

    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            size:'100%',
            dataLabels: {
                enabled: true,
                distance: -25,
                style: {
                    fontWeight: 'bold',
                    color: 'white'
                }
            },
            startAngle: -90,
            endAngle: 90,
            center: ['50%', '75%']
        }
    },

    chart: {           
        margin: [0, 0, 0, 0],
        spacingTop: 0,
        spacingBottom: 0,
        spacingLeft: 0,
        spacingRight: 0
    },
	
    series: [{
        type: 'pie',
        name: 'Browser share',
        innerSize: '50%',
        data: [
            ['A', 66.66],
            ['B', 33.33]
        ]
    }]
});