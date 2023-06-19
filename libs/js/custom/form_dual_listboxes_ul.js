/* ------------------------------------------------------------------------------
 *
 *  # Dual listboxes
 *
 *  Demo JS code for form_dual_listboxes.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var DualListboxesUL = function() {


    //
    // Setup module components
    //

    // Dual listbox
    var _componentDualListbox = function() {
        if (!$().bootstrapDualListbox) {
            console.warn('Warning - duallistbox.min.js is not loaded.');
            return;
        }

        // Multiple selection
        $('.listbox-no-selection').bootstrapDualListbox({
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            selectorMinimalHeight: 300
        });

// to be removed
        // Add options
        $('.listbox-add').on('click', function(){
            $('.listbox-dynamic-options').append('<option value="apples">Apples</option><option value="oranges" selected>Oranges</option>');
            $('.listbox-dynamic-options').trigger('bootstrapDualListbox.refresh'); // CHRISNOTE: should/can I use this refresh?
        });

        // Add options with clearing highlights
        $('.listbox-add-clear').on('click', function(){
            $('.listbox-dynamic-options').append('<option value="apples">Apples</option><option value="oranges" selected>Oranges</option>');
            $('.listbox-dynamic-options').trigger('bootstrapDualListbox.refresh', true); // CHRISNOTE: should/can I use this refresh?
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentDualListbox();
        }
    }
}();


// Initialize module
// ------------------------------

// document.addEventListener('DOMContentLoaded', function() {
//     DualListboxesUL.init();
// });
