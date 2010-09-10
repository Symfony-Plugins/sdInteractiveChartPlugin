var CHARTS = {};

CHARTS.toCreate = Array();


/**
 * When the document has loaded we should go through the list of charts
 * and create them!
 *
 */
$(document).ready(function() {
    var len = CHARTS.toCreate.length;
    for (var i = 0; i < len; i++) {
        var chart = CHARTS.toCreate[i];
        if (chart.chartType == undefined) {
            if (typeof(console) != "undefined") {
                console.error('No chart type (chartType) defined.');
            }
            continue;
        }

        if ((chart.url != undefined) && (chart.url != '')) {
            // This chart requires data from an AJAX request!
            CHARTS.loadData(chart, i);
        } else {
            // create the chart!
            CHARTS.createChart(i);
        }
    }
});


CHARTS.createChart = function(chartIndex) {
    // I think all charts this plugin will implement can use the same creation code
    // But just in case this can stay like it is for now!
        CHARTS.createBarChart(chartIndex);
}


CHARTS.createBarChart = function(chartIndex) {
    chartInfo = CHARTS.toCreate[chartIndex];
    // load on the data
    if (typeof(chartInfo.data) == 'function') {
        chartInfo.data = chartInfo.data();
    }

    var chart = eval('new google.visualization.' + chartInfo.chartType + '(document.getElementById("' + chartInfo.div + '"))');
    chart.draw(chartInfo.data, chartInfo);
    // if there's a call back we better call it!
    if ((chartInfo.callback != undefined) && (typeof(chartInfo.callback) == 'function')) {
        chartInfo.callback(chartInfo, chartIndex);
    }
}


CHARTS.loadData = function(graphObj, index) {
    if (graphObj.ajaxData == undefined) {
        graphObj.ajaxData = {};
    }
    graphObj.index = index;
    var ind = index;
    jQuery.get(graphObj.url, graphObj.ajaxData, function(json) {

        var data = new google.visualization.DataTable();
        if (json.labels != undefined) {
            data.addColumn('string', 'Labels');
        }
        var rowCountAdded = false;


        // now shoot round and add all the data!
        for (i = 0; i < json.data.length; i++) {
            data.addColumn('number', json.dataNames[i]);
            if (!rowCountAdded) {
                data.addRows(json.data[i].length);
                        // add labels!
                for (var t = 0; t < json.labels.length; t++) {
                    data.setValue(t, 0, json.labels[t]);
                }
                rowCountAdded = true;
            }
            for (var j = 0; j < json.data[i].length; j++) {
                data.setValue(j, i+1, json.data[i][j]);
            }

        }
        delete json.data;
        delete json.labels;
        delete json.dataNames;
        CHARTS.toCreate[ind].data = data;

        for (attrname in json) { CHARTS.toCreate[ind][attrname] = json[attrname]; }

        // now lets call the chart creation function
        CHARTS.createChart(ind);
    },
    'json');
}
