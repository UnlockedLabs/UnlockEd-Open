/* ------------------------------------------------------------------------------
 *
 *  This is the function expression that initializes the
 *  submission bar charts that students see after they
 *  submit a quiz. It shows all the submission attempts
 *  they have for that particular quiz, along with their
 *  scores.
 *
 *  (original code found in
 *  global_assets/js/demo_pages/charts/echarts/columns_waterfalls.js,
 *  which is the demo JS code for echarts_columns_waterfalls.html page)
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var ULQuizSubmissionsColumns = function() {

    //
    // Setup module components
    //

    // Column and waterfall charts
    var _columnsQuizSubmissions = function(quiz_name, attempt_nums, attempt_scores) {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        var number_of_attempts = [];
        var scores_of_attempts = [];

        if (attempt_nums == "Attempt 1") { // if this is the first submission
            number_of_attempts.push(attempt_nums);
            scores_of_attempts.push(attempt_scores);
        } else {
            number_of_attempts = attempt_nums.split(',');
            scores_of_attempts = attempt_scores.split(',');
        }

        // Define elements
        var columns_basic_element = document.getElementById('columns_basic');

        //
        // Charts configuration
        //

        // Basic columns chart
        if (columns_basic_element) {

            // Initialize chart
            var columns_basic = echarts.init(columns_basic_element);


            //
            // Chart config
            //

            // Options
            columns_basic.setOption({

                // Define colors
                color: ['#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80'],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Chart animation duration
                animationDuration: 750,

                // Setup grid
                grid: {
                    left: 0,
                    right: 40,
                    top: 35,
                    bottom: 0,
                    containLabel: true
                },

                // Add legend
                // legend: {
                //     data: ['Evaporation', 'Precipitation'],
                //     itemHeight: 8,
                //     itemGap: 20,
                //     textStyle: {
                //         padding: [0, 5]
                //     }
                // },

                // Add tooltip
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    },
                    formatter: "{a} <br/>{b}: {c}%"
                },

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: number_of_attempts,
                    axisLabel: {
                        color: '#333'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        show: true,
                        lineStyle: {
                            color: '#eee',
                            type: 'dashed'
                        }
                    }
                }],

                // Vertical axis
                yAxis: [{
                    type: 'value',
                    max: 100,
                    axisLabel: {
                        color: '#333'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                        }
                    }
                }],

                // Add series
                series: [
                    {
                        name: quiz_name,
                        type: 'bar',
                        data: scores_of_attempts,
                        itemStyle: {
                            normal: {
                                label: {
                                    show: true,
                                    position: 'top',
                                    textStyle: {
                                        fontWeight: 500
                                    }
                                }
                            }
                        },
                        markLine: {
                            data: [{type: 'average', name: 'Average'}]
                        }
                    }
                ]
            });
        }

        //
        // Resize charts
        //

        // Resize function
        var triggerChartResize = function() {
            columns_basic_element && columns_basic.resize();
        };

        // On sidebar width change
        $(document).on('click', '.sidebar-control', function() {
            setTimeout(function () {
                triggerChartResize();
            }, 0);
        });

        // On window resize
        var resizeCharts;
        window.onresize = function () {
            clearTimeout(resizeCharts);
            resizeCharts = setTimeout(function () {
                triggerChartResize();
            }, 200);
        };
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function(quizName, attemptNums, attemptScores) {
            _columnsQuizSubmissions(quizName, attemptNums, attemptScores);
        }
    }
}();



// Initialize module
// ------------------------------

// document.addEventListener('DOMContentLoaded', function() {
//     ULQuizSubmissionsColumns.init();
// });
