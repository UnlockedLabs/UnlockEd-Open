/**
 * Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'Flash,Iframe,Save';
	config.toolbarCanCollapse = true;
};

CKEDITOR.on('dialogDefinition', function(e) {
	var dialogName = e.data.name;
	var dialogDefinition = e.data.definition;
	var oldImplementationOk = e.data.definition.onOk;
	var oldImplementationShow = e.data.definition.onShow;


	//Because we have the CKEditor embedded in a Bootstrap Modal, CKE dialogs that pop up get lost behind the BS dialog
	//We therefore use  the dialogDefinition.onShow event to manipulate visibilities to prevent that from  happening.
	
	if (dialogName == "link" || dialogName == "anchor" || dialogName == "link" || dialogName == "image" || dialogName == "table" || dialogName == "find" || dialogName == "replace" || dialogName == "creatediv")
	{
		dialogDefinition.onShow = function () {
			$('#modal_full').css('visibility','hidden');
			oldImplementationShow.apply(this,e);
        }
        dialogDefinition.onOk = function () {
			$('#modal_full').css('visibility', 'visible');
			oldImplementationOk.apply(this,e);
			return true;
        }
        dialogDefinition.onCancel = function () {
			$('#modal_full').css('visibility', 'visible');
        }
	}
  
});

