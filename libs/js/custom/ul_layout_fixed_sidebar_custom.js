/* ------------------------------------------------------------------------------
 *
 *  # Layout - fixed navbar and sidebar with custom scrollbar
 *
 *  Demo JS code for layout_fixed_sidebar_custom.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var FixedSidebarCustomScroll = function() {


    //
    // Setup module components
    //

    // Perfect scrollbar
    var _componentPerfectScrollbar = function() {
        if (typeof PerfectScrollbar == 'undefined') {
            console.warn('Warning - perfect_scrollbar.min.js is not loaded.');
            return;
        }

        // Initialize
        var ps = new PerfectScrollbar('.sidebar-fixed .sidebar-content', {
            wheelSpeed: 2,
            wheelPropagation: true
        });
    };

    var _fixSiteLogoToggle = function(){
        $('#navbar-mobile-toggle').on('click',function(){
            if($('body').hasClass('sidebar-xs')){
                $('.sidenavneedscollapse').hide();
                $('#sidebarToggleArrow').removeClass('icon-arrow-left8').addClass('icon-arrow-right8');
            }
            else
            {
                $('.sidenavneedscollapse').show();
                $('#sidebarToggleArrow').removeClass('icon-arrow-right8').addClass('icon-arrow-left8');
            }
        });
    }

    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentPerfectScrollbar();
            _fixSiteLogoToggle();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    FixedSidebarCustomScroll.init();
});

