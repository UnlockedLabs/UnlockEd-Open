/* ------------------------------------------------------------------------------
 *
 *  # for gradebook datatable
 *
 *  Original code located in datatable_extension_buttons_html5.js
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var DatatableButtonsHtml5 = function() {


    //
    // Setup module components
    //

    // Basic Datatable examples
    var _componentDatatableButtonsHtml5 = function(course_quiz_object) {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
            }
        });

        if ($('.dynamic-columns')) {
            $('.dynamic-columns').remove();
        }

        var dataSet = [];
        var stuIds = [];

        for (var i = 0; i < course_quiz_object.data.length; i++) {
            // dynamically create column headings for quizzes
            var iterator = course_quiz_object.data.length - 1 - i; // to get the quiz names in reverse order
            if (course_quiz_object.data[iterator].pointsPossible) {
                var $newQuizColumn = $('<th class="dynamic-columns">' + course_quiz_object.data[iterator].quizName + ' <br>(out of total  ' + course_quiz_object.data[iterator].pointsPossible + ')</th>');
            } else {
                var $newQuizColumn = $('<th class="dynamic-columns">' + course_quiz_object.data[iterator].quizName + '</th>');
            }
            $('#quiz-columns').children().eq(0).after($newQuizColumn); // pushes the quiz-named columns in right order
            for (var j = 0; j < course_quiz_object.data[i].quizResults.length; j++) {
                if (stuIds.includes(course_quiz_object.data[i].quizResults[j].studentId)) {
                    continue;
                } else {
                    var stuArray = [];
                    stuArray.push(course_quiz_object.data[i].quizResults[j].studentName);
                    dataSet.push(stuArray);
                    stuIds.push(course_quiz_object.data[i].quizResults[j].studentId);
                }
            }
        }

        for (var i = 0; i < course_quiz_object.data.length; i++) {
            for (var j = 0; j < stuIds.length; j++) {
                var hasntTakenQuiz = true;
                for (var k = 0; k < course_quiz_object.data[i].quizResults.length; k++) {
                    if (stuIds[j] == course_quiz_object.data[i].quizResults[k].studentId) {
                        dataSet[j].push(course_quiz_object.data[i].quizResults[k].studentGrade);
                        hasntTakenQuiz = false;
                    }
                }
                if (hasntTakenQuiz) {
                    dataSet[j].push('-');
                }
            }
        }

        // Column selectors
        $('.datatable-button-html5-columns').dataTable().fnDestroy(); // destroy previous datatable if exists
        $('.datatable-button-html5-columns').DataTable({
            // data: course_quiz_object.data,
            data: dataSet,
            buttons: {            
                buttons: [
                    {
                        extend: 'copyHtml5',
                        className: 'btn btn-light',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-light',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'btn btn-light',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-light',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="icon-three-bars"></i>',
                        className: 'btn bg-blue btn-icon dropdown-toggle'
                    }
                ]
            }
        });

    };

    // Select2 for length menu styling
    var _componentSelect2 = function() {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth: true,
            width: 'auto'
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function(course_quiz_object) {
            _componentDatatableButtonsHtml5(course_quiz_object);
            _componentSelect2();
        }
    }
}();


// Initialize module
// ------------------------------

// document.addEventListener('DOMContentLoaded', function() {
//     DatatableButtonsHtml5.init();
// });
