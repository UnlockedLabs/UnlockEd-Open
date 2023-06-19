/* ------------------------------------------------------------------------------
 *
 *  # Echarts - Bar and Tornado charts
 *
 *  Demo JS code for echarts_bars_tornados.html page
 * 
 *  ChrisNote: This code was taken from the bars_tornados.js file
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

let ULQuizSubmissionsBars = function() {

    //
    // Setup module components
    //

    // Bar and tornado charts
    let _barsTornadosExamples = function(course_object) {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        // Define elements
        let bars_basic_element = document.getElementById('bars_basic');
        
        const colorCodes = ['#FF0000', '#008800', '#FF4500','#0000FF',  '#FFFF00', '#4B0082', '#EE82EE'];
        let quizNameArray = [];
        let studentNameArray = [];
        let studentGradesArray = [];
        let seriesArray = [];

        // console.log(echarts);
        
        // Parse course object
        for (let i of course_object.data) {
            quizNameArray.push(i.quizName);    // populate quizNameArray
            for (let j of i.quizResults) {
                if (!studentNameArray.includes(j.studentName)) {
                    studentNameArray.push(j.studentName);    // populate studentNameArray
                }                
            }
        }
        
        for (let i=0; i < course_object.data.length; i++) {
            let quizGradesArray = new Array(studentNameArray.length);

            for (let j=0; j < studentNameArray.length; j++) {
                if (course_object.data[i].quizResults.findIndex(item => item.studentName === studentNameArray[j]) !== -1) {
                    let index = course_object.data[i].quizResults.findIndex(item => item.studentName === studentNameArray[j]);
                    quizGradesArray[j] = course_object.data[i].quizResults[index].studentGrade;
                    // console.log('Not negative 1 '+ index);
                } else {
                    // console.log(course_object.data[i].quizResults.findIndex(item => item.studentName === studentNameArray[j]));
                    quizGradesArray[j] = 0;
                }
            }            
            studentGradesArray.push(quizGradesArray);
        }

        let counter = 0;
        for (let i in studentGradesArray) {
            let seriesItem = {
                name: quizNameArray[i],
                type: 'bar',
                itemStyle: {
                    normal: {
                        color: colorCodes[counter]
                    }
                },
                data: studentGradesArray[i]
            };

            seriesArray.push(seriesItem);
            counter = ++counter % colorCodes.length;            
        }

        // console.log('Course Object quizzes: ' + course_object.quizzes);
        // console.log('Quiz Name Array: ' + quizNameArray);
        // console.log('Student Name Array: ' + studentNameArray);
        // console.log('Student Grades Array: ' + studentGradesArray);
        // console.log('Series Array: ' + seriesArray);

        //
        // Charts configuration
        //

        // Basic bar chart
        if (bars_basic_element) {

            // clear out previous chart (if there is one)
            if (bars_basic_element.innerHTML != '') {
                echarts.dispose(bars_basic_element);
            }
            
            // Initialize chart
            let bars_basic = echarts.init(bars_basic_element);

            //
            // Chart config
            //

            // clear out previous bars
            bars_basic.setOption({});

            // Options
            bars_basic.setOption({

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
                    right: 30,
                    top: 35,
                    bottom: 0,
                    containLabel: true
                },

                // Add legend
                legend: {
                    data: quizNameArray,
                    itemHeight: 8,
                    itemGap: 20,
                    textStyle: {
                        padding: [0, 5]
                    }
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis',
                    // formatter: "{a} <br/>{b} : {c} ({d}%)",
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    },
                    axisPointer: {
                        type: 'shadow',
                        shadowStyle: {
                            color: 'rgba(0,0,0,0.025)'
                        }
                    }
                },

                // Horizontal axis
                xAxis: [{
                    type: 'value',
                    max: 100,
                    boundaryGap: [0, 0.01],
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
                    type: 'category',
                    data: studentNameArray,
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
                            color: ['#eee']
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.015)']
                        }
                    }
                }],

                // Add series
                series: seriesArray
            });

        }

        //
        // Resize charts
        //

        // Resize function
        let triggerChartResize = function() {
            bars_basic_element && bars_basic.resize();
        };

        // On sidebar width change
        $(document).on('click', '.sidebar-control', function() {
            setTimeout(function () {
                triggerChartResize();
            }, 0);
        });

        // On window resize
        let resizeCharts;
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
        init: function(quiz_array) {
            _barsTornadosExamples(quiz_array);
        }
    }
}();