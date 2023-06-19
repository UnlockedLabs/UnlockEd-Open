var ul = {
    test: "this is an object propertry",

    successSwalAlert: function(title, msg) {
        swal({
                title: title,
                text: msg,
                confirmButtonColor: '#3085d6',
                confirmButtonClass: 'btn btn-info',
                allowOutsideClick: false,
                confirmButtonText: 'OK',
                type: "success"
            });
    },

    errorSwalAlert: function(title, msg) {
        swal({
                title: title,
                text: msg,
                confirmButtonColor: '#3085d6',
                confirmButtonClass: 'btn btn-info',
                allowOutsideClick: false,
                confirmButtonText: 'OK',
                type: "error"
            });
    },

    ajax_content_area: function(url, content) {

        //add validation
        var $content = $(content);
    
        $.ajax({
            type: 'GET',
            url: url,
            timeout: 30000,
            beforeSend: function() {
                $content.html('<div id="load">Loading</div>');
            },
            complete: function() {
                $('#load').remove();
            },
            success: function(data) {
                $content.html(data);
            },
            error: function(data) {
                $content.html(data.responseText);
            },
            fail : function() {
                $content.html('<div id="load">Please try again soon.</div>');
            }
        });

    },

    admin_fetch_html: function(url, content) {

        scrollToTopCustom();
        this.ajax_content_area(url, content);
        return false; //stop page change and propagation

    },

    stripUrl: function(id) {
        // This function strips the http.../ url from emojis in CKEditor and replaces it with src="libs/ckeditor4...
        return CKEDITOR.instances[id].getData().replace(/src=.*?ckeditor4/gi, 'src="libs/ckeditor4');
    }

};