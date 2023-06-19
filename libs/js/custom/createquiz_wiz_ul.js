/* ------------------------------------------------------------------------------
 *
 *  # Steps wizard
 *
 *  Demo JS code for form_wizard.html page
 *
 * ---------------------------------------------------------------------------- */

// $('#wizard-p-'+currentIndex).


// Setup module
// ------------------------------
var quizwizhandle; //CHRISNOTE: what is this?

var CreateQuizFormWizard = function() {


    //
    // Setup module components
    //

    // Wizard
    var _componentWizard = function(form_id) {
        if (!$().steps) {
            console.warn('Warning - steps.min.js is not loaded.');
            return;
        }
        
        //
        // Wizard with validation
        //

        // Stop function if validation is missing
        if (!$().validate) {
            console.warn('Warning - validate.min.js is not loaded.');
            return;
        }

        // Show QuizzicUL Quiz Creator form
        var create_form = $(form_id).show();


        // Initialize wizard
        // setup for QuizzicUL Quiz Creator form
        $(form_id).steps({
            headerTag: 'h6',
            bodyTag: 'fieldset',
            titleTemplate: '<span class="number">+</span> #title#',
            enableCancelButton: false,
            // enableAllSteps: true, //unnecessary                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
            onCanceled: function (event) {
                // alert("You are cancelled!");
                // validator.resetForm();
                // create_form.resetForm();
            },
            labels: {
                previous: '<i class="icon-arrow-left13 mr-2" /> Previous',
                next: 'Next <i class="icon-arrow-right14 ml-2" />',
                finish: 'Create Quiz <i class="icon-arrow-right14 ml-2" />',
                cancel: 'Cancel'
            },
            transitionEffect: 'fade',
            autoFocus: true,
            onStepChanging: function (event, currentIndex, newIndex) {

                // Only show Add Question/Add Answer buttons on the question step
                if (newIndex == 2) {
                    // $('#add-question-div').show();
                    // $('#add-answer-div').show();

                    // gives number of questions for quiz
                    // $("#questionPoints-chris").on('input', function() {
                    // 	var num = $(this).val();
                    // 	console.log("i changed");
                    // 	console.log(num);
                    // });

                    // var question_counter = 1;

                    // to add another question
                    $('.question-button').on('click', function(e) {
                        e.preventDefault();

                        $.get(
                            "libs/php/guid_for_js.php"
                        ).done(function(data) {

                            var answer_counter = 1;
                            
                            var question_id = data;
                            var html = `
                                <div class="form-group row pt-4 border-top">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="row">
                                                    <label class="col-md-4 col-form-label text-lg-right" for="questionPoints">Points:</label>
                                                    <!-- NOTE: this value is stored in the quiz_questions table -->
                                                    <input type="number" name="points-${question_id}" class="form-control col-md-5" id="questionPoints-${question_id}" value="1" min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                <!--deleted concepts tags here-->    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="col-md-12 col-form-label" for="questionText">Question text:</label>						
                                        <textarea name="question_text-${question_id}" id="questionText-${question_id}" data-question_id="${question_id}" data-question_position="${question_counter++}" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group row pb-1 answer-container">
                                    <div class="col-md-6 mb-2">
                                        <div class="row">
                                            <label class="col-md-4 col-form-label text-lg-right" for="quizAnswer"><span class="bg-green text-white px-1 py-1 rounded">Correct Answer:</span></label>
                                            <div class="col-md-8">
                                                <input type="text" name="quiz_answer-${answer_counter}" class="form-control quiz-answers" data-answer_position="${answer_counter++}" data-question_id="${question_id}" data-correct="yes" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="row">
                                            <label class="col-md-4 col-form-label text-lg-right" for="quizAnswer">Answer:</label>
                                            <div class="col-md-8">
                                                <input type="text" name="quiz_answer-${answer_counter}" class="form-control quiz-answers" data-answer_position="${answer_counter++}" data-question_id="${question_id}" data-correct="no">
                                            </div>
                                        </div>
                                    </div>					
                                </div>
                            `;

                            // append question to question step fieldset
                            $('.question-container').append(html);

                            // initialize ckeditor for added question
                            initSample(`questionText-${question_id}`);

                            // initialize tags for added question
                            $('.select-multiple-tags').select2({
                                tags: true
                            });
                            
                        });
                        
                    });

                    // to add another answer
                    $('.answer-button').on('click', function(e) {
                        e.preventDefault();
                        
                        var answer_counter = $(this).closest('.add-quiz-elements-container').prev().find('.answer-container:last-of-type').find('.col-md-6:last-of-type').find('input').data('answer_position') + 1;
                        var question_id = $(this).closest('.add-quiz-elements-container').prev().find('.answer-container:last-of-type').find('.col-md-6:last-of-type').find('input').data('question_id');
                        
                        var html = `
                            <div class="col-md-6 mb-2">
                                <div class="row">
                                    <label class="col-md-4 col-form-label text-lg-right" for="quizAnswer">Answer:</label>
                                    <div class="col-md-8">
                                        <input type="text" name="quiz_answer-${answer_counter}" class="form-control quiz-answers" data-answer_position="${answer_counter}" data-question_id="${question_id}" data-correct="no">
                                    </div>
                                </div>
                            </div>
                        `;

                        // append answer to answer container
                        $('.answer-container:last-of-type').append(html);

                        // alert("Add an answer");
                    });

                } // else {
                    // $('#add-question-div').hide();
                    // $('#add-answer-div').hide();
                // }

                // Allways allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }

                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {

                    // To remove error styles
                    create_form.find('.body:eq(' + newIndex + ') label.error').remove();
                    create_form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
                }

                create_form.validate().settings.ignore = ':disabled,:hidden';
                return create_form.valid();
            },
            onFinishing: function (event, currentIndex) {
                create_form.validate().settings.ignore = ':disabled';
                return create_form.valid();
            },
            onFinished: function (event, currentIndex) {
                $(form_id).submit();
            }
        });


        // Initialize validation
        $(form_id).validate({
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            errorClass: 'validation-invalid-label',
            highlight: function(element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function(element, errorClass) {
                $(element).removeClass(errorClass);
            },

            // Different components require proper error label placement
            errorPlacement: function(error, element) {

                // Unstyled checkboxes, radios
                if (element.parents().hasClass('form-check')) {
                    // error.appendTo( element.parents('.form-check').parent() ).before('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
                    error.appendTo( element.parents('.form-group').parent() );
                }

                // Input with icons and Select2
                else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                    error.appendTo( element.parent() );
                }

                // Input group, styled file input
                else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                    error.appendTo( element.parent().parent() );
                }

                // Other elements
                else {
                    error.insertAfter(element);
                }
            },
            rules: {
                email: {
                    email: true
                }
            }
        });

    };

    // Dragula examples
    // (Originally from extension_dnd.js)
    var _componentDragula = function() {
        if (typeof dragula == 'undefined') {
            console.warn('Warning - dragula.min.js is not loaded.');
            return;
        }

    };
 
    // Uniform
    var _componentUniform = function() {
        if (!$().uniform) {
            console.warn('Warning - uniform.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.form-input-styled').uniform({
            fileButtonClass: 'action btn bg-blue'
        });
    };

    // Select2 select
    var _componentSelect2 = function() {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        }

        // Initialize
        var $select = $('.form-control-select2').select2({
            minimumResultsForSearch: Infinity,
            width: '100%'
        });

        // Tagging support
        // (This snippet originally came from form_select2.js)
        $('.select-multiple-tags').select2({
            tags: true
        });

        // Trigger value change when selection is made
        $select.on('change', function() {
            $(this).trigger('blur');
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function(form_id) {
            _componentWizard(form_id);
            _componentUniform();
            _componentSelect2();
        }
    }
}();
