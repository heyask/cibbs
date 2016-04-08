$(document).ready(function() {
	var FormWysiwyg = function () {

	    return {

	        // =========================================================================
	        // CONSTRUCTOR APP
	        // =========================================================================
	        init: function () {
	            FormWysiwyg.bootstrapWYSIHTML5();
	            FormWysiwyg.summernote();
	        },

	        // =========================================================================
	        // BOOTSTRAP WYSIHTML5
	        // =========================================================================
	        bootstrapWYSIHTML5: function () {
	            if($('.wysihtml5-textarea').length){
	                $('.wysihtml5-textarea').wysihtml5();
	            }
	        },

	        // =========================================================================
	        // SUMMERNOTE
	        // =========================================================================
	        summernote: function () {
	            if($('.summernote-editor').length){
	                $('.summernote-editor').summernote({
						toolbar: [
							['style', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
							['fontname', ['fontname']],
							['fontsize', ['fontsize']],
							['color', ['color']],
							['para', ['style', 'ul', 'ol', 'paragraph']],
							['insert', ['link', 'picture', 'video', 'hr']],
							['view', ['codeview']],
							['misc', ['undo', 'redo', 'fullscreen']]
						],
						height: 500,
						lang: 'ko-KR'
					});
					$('.summernote-editor-no-toolbar').summernote({
						toolbar: [],
						lang: 'ko-KR'
					});
	            }
	            if($('.tmp_content').length) 
	            {
	            	$('.summernote-editor').code($('.tmp_content').html());
	            }
	        }

	    };

	}();

	FormWysiwyg.init();
});