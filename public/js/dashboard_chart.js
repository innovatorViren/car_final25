"use strict";
var KTDatatablesBasicScrollable = function() {

    var dashboard = function() 
    {
        const apexChart = "#dashboardChart";
        var options = {
            series: [{
                name: 'Printing',
                data: [44, 55, 57, 56, 61, 58, 63, 60, 76, 85, 101, 98]
            }, {
                name: 'Solventless Lamination',
                data: [76, 85, 101, 98, 87, 105, 91, 114, 35, 41, 36, 26]
            }, {
                name: 'Slitting',
                data: [35, 41, 36, 26, 45, 48, 52, 53, 30, 40, 31, 25]
            }, {
                name: 'Pouching',
                data: [30, 40, 31, 25, 46, 49, 50, 55, 56, 89, 42, 85]
            }, {
                name: 'Rewinding',
                data: [56, 89, 42, 85, 20, 80, 30, 78, 22, 12, 100, 79]
            }, {
                name: 'Extrusion Coating Lamination',
                data: [22, 12, 100, 79, 30, 88, 61, 69, 98, 78, 81, 55]
            }, {
                name: 'Blown Film 3 Layer',
                data: [98, 78, 81, 55, 65, 30, 78, 23, 90, 100, 110, 88]
            },{
                name: 'Solvent Based Lamination',
                data: [90, 100, 110, 88, 66, 78, 32, 50, 44, 55, 57, 56]
            }],
            chart: {
                toolbar: {
                    show: false,
                },
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                }
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar']
            },
            yaxis: {
                title: {
                    text: '₹ (kg)'
                },
                tickAmount: 3.5,
            },
            legend: {
              show: false,
            },
            fill: {
                opacity: 1
            },
            tooltip: {
             y: {
                formatter: function formatter(val) {
                    return  val + " kg";
                }
                }
            },
            colors: ['#3c3b90', '#1bc5bd', '#ffa800', '#448e4d', '#cd82ad', '#b9783f', '#cc4748', '#6993ff']
            // colors: [success, '#3c3b90', warning, danger, '#cd82ad', '#b9783f', '#cc4748', '#6993ff']
        };

        var chart = new ApexCharts(document.querySelector(apexChart), options);
        chart.render();
    };

    // var purchaseOrderChart = function () {
    //     var element = document.getElementById("purchase_order_chart");

    //     if (!element) {
    //         return;
    //     }

    //     var options = {
    //         series: [{
    //             name: 'Net Weight',
    //             data: [120000000, 70000000, 170000000, 250000000, 200000000, 80000000, 90000000, 100000000, 130000000, 90000000, 60000000, 200000000]
    //         }, {
    //             name: 'Bill',
    //             data: [220000000, 210000000, 250000000, 80000000, 90000000, 1050000000, 110000000, 120000000, 130000000, 140000000, 150000000, 160000000]
    //         }],
    //         chart: {
    //             type: 'bar',
    //             height: 350,
    //             toolbar: {
    //                 show: false
    //             }
    //         },
    //         plotOptions: {
    //             bar: {
    //                 horizontal: false,
    //                 columnWidth: ['30%'],
    //                 endingShape: 'rounded'
    //             },
    //         },
    //         legend: {
    //             show: false
    //         },
    //         dataLabels: {
    //             enabled: false
    //         },
    //         stroke: {
    //             show: true,
    //             width: 2,
    //             colors: ['transparent']
    //         },
    //         xaxis: {
    //             categories: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
    //             axisBorder: {
    //                 show: false,
    //             },
    //             axisTicks: {
    //                 show: false
    //             },
    //             labels: {
    //                 style: {
    //                     colors: KTApp.getSettings()['colors']['gray']['gray-500'],
    //                     fontSize: '12px',
    //                     fontFamily: KTApp.getSettings()['font-family']
    //                 }
    //             }
    //         },
    //         yaxis: {
    //             labels: {
    //                 style: {
    //                     colors: KTApp.getSettings()['colors']['gray']['gray-500'],
    //                     fontSize: '12px',
    //                     fontFamily: KTApp.getSettings()['font-family']
    //                 }
    //             },
    //             min: 50000000,
    //             max: 250000000,
    //             tickAmount: 4,
    //         },
    //         fill: {
    //             opacity: 1
    //         },
    //         states: {
    //             normal: {
    //                 filter: {
    //                     type: 'none',
    //                     value: 0
    //                 }
    //             },
    //             hover: {
    //                 filter: {
    //                     type: 'none',
    //                     value: 0
    //                 }
    //             },
    //             active: {
    //                 allowMultipleDataPointsSelection: false,
    //                 filter: {
    //                     type: 'none',
    //                     value: 0
    //                 }
    //             }
    //         },
    //         tooltip: {
    //             style: {
    //                 fontSize: '12px',
    //                 fontFamily: KTApp.getSettings()['font-family']
    //             },
    //             y: {
    //                 formatter: function (val, options) {
    //                     if(options.seriesIndex == 0){
    //                         return val + " kg" 
    //                     }
    //                     return "₹ " + val 
    //                 }
    //             }
    //         },
    //         colors: [KTApp.getSettings()['colors']['theme']['base']['warning'], KTApp.getSettings()['colors']['gray']['gray-300']],
    //         grid: {
    //             borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
    //             strokeDashArray: 4,
    //             yaxis: {
    //                 lines: {
    //                     show: true
    //                 }
    //             }
    //         }
    //     };

    //     var chart = new ApexCharts(element, options);
    //     chart.render();
    // };

    // var salesOrderChart = function () {
    //     var element = document.getElementById("sales_order_chart");

    //     if (!element) {
    //         return;
    //     }

    //     var options = {
    //         series: [{
    //             name: 'Net Weight',
    //             data: [220000000, 210000000, 250000000, 80000000, 90000000, 1050000000, 110000000, 120000000, 130000000, 140000000, 150000000, 160000000]
    //         }, {
    //             name: 'Invoice',
    //             data: [120000000, 70000000, 170000000, 250000000, 200000000, 80000000, 90000000, 100000000, 130000000, 90000000, 60000000, 200000000]
    //         }],
    //         chart: {
    //             type: 'bar',
    //             height: 350,
    //             toolbar: {
    //                 show: false
    //             }
    //         },
    //         plotOptions: {
    //             bar: {
    //                 horizontal: false,
    //                 columnWidth: ['30%'],
    //                 endingShape: 'rounded'
    //             },
    //         },
    //         legend: {
    //             show: false
    //         },
    //         dataLabels: {
    //             enabled: false
    //         },
    //         stroke: {
    //             show: true,
    //             width: 2,
    //             colors: ['transparent']
    //         },
    //         xaxis: {
    //             categories: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
    //             axisBorder: {
    //                 show: false,
    //             },
    //             axisTicks: {
    //                 show: false
    //             },
    //             labels: {
    //                 style: {
    //                     colors: KTApp.getSettings()['colors']['gray']['gray-500'],
    //                     fontSize: '12px',
    //                     fontFamily: KTApp.getSettings()['font-family']
    //                 }
    //             }
    //         },
    //         yaxis: {
    //             labels: {
    //                 style: {
    //                     colors: KTApp.getSettings()['colors']['gray']['gray-500'],
    //                     fontSize: '12px',
    //                     fontFamily: KTApp.getSettings()['font-family']
    //                 }
    //             },
    //             min: 50000000,
    //             max: 250000000,
    //             tickAmount: 4,
    //         },
    //         fill: {
    //             opacity: 1
    //         },
    //         states: {
    //             normal: {
    //                 filter: {
    //                     type: 'none',
    //                     value: 0
    //                 }
    //             },
    //             hover: {
    //                 filter: {
    //                     type: 'none',
    //                     value: 0
    //                 }
    //             },
    //             active: {
    //                 allowMultipleDataPointsSelection: false,
    //                 filter: {
    //                     type: 'none',
    //                     value: 0
    //                 }
    //             }
    //         },
    //         tooltip: {
    //             style: {
    //                 fontSize: '12px',
    //                 fontFamily: KTApp.getSettings()['font-family']
    //             },
    //             y: {
    //                 formatter: function (val, options) {
    //                     if(options.seriesIndex == 0){
    //                         return val + " kg" 
    //                     }
    //                     return "₹ " + val 
    //                 }
    //             }
    //         },
    //         colors: [KTApp.getSettings()['colors']['theme']['base']['success'], KTApp.getSettings()['colors']['gray']['gray-300']],
    //         grid: {
    //             borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
    //             strokeDashArray: 4,
    //             yaxis: {
    //                 lines: {
    //                     show: true
    //                 }
    //             }
    //         }
    //     };

    //     var chart = new ApexCharts(element, options);
    //     chart.render();
    // };

    return {

        init: function() {
            dashboard();
            // purchaseOrderChart();
            // salesOrderChart();
        },

    };

}();

jQuery(document).ready(function() {
    KTDatatablesBasicScrollable.init();
});
