$(document).ready(function() {
	/*window.onbeforeunload = function(){
		return '지금 페이지를 벗어나면 작성중인 글이 모두 사라집니다. \n\n계속하시겠습니까?';
	};*/
		
	function sendFile(file, editor) {

	    data = new FormData();
	    data.append("SummernoteFile", file);
	    $.ajax({
	       data: data,
	       type: "POST",
	       url: "/bbs/servlet_image_upload/11",
	       cache: false,
	       contentType: false,
	       processData: false,
	       success: function(data) {
	         var obj =  $.parseJSON(data);
	         if (obj.success) {
	             $(editor).summernote("insertImage", obj.save_url);
	         } else {
	            switch(parseInt(obj.error)) {
	                case 1: alert('업로드 용량 제한에 걸렸습니다.'); break; 
					case 2: alert('MAX_FILE_SIZE 보다 큰 파일은 업로드할 수 없습니다.'); break;
					case 3: alert('파일이 일부분만 전송되었습니다.'); break;
					case 4: alert('파일이 전송되지 않았습니다.'); break;
					case 6: alert('임시 폴더가 없습니다.'); break;
					case 7: alert('파일 쓰기 실패'); break;
					case 8: alert('알수 없는 오류입니다.'); break;
	                case 100: alert('이미지 파일이 아닙니다.(jpeg, jpg, gif, bmp, png 만 올리실 수 있습니다.)'); break; 
	                case 101: alert('이미지 파일이 아닙니다.(jpeg, jpg, gif, bmp, png 만 올리실 수 있습니다.)'); break; 
	                case 102: alert('0 byte 파일은 업로드 할 수 없습니다.'); break; 
	            }
	         }
	       },
	       error:function(request,status,error){
	    	    console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
	    	   }
	   });
	}
	
	
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
						lang: 'ko-KR',
						disableDragAndDrop: true,
						callbacks : {
							onImageUpload: function (files) {
								var maxSize = 1 * 1024 * 1024 * 1024; // limit 1MB  
									
								var isMaxSize = false; 
								var maxFile = null;
								for (var i = 0; i < files.length; i++) {
									if (files[i].size > maxSize) {
										isMaxSize = true;
										maxFile = files[i].name; 
										break;   
									}

								}

								if (isMaxSize) { // 사이즈 제한에 걸렸을 때 
									alert('[' + maxFile + '] 파일이 업로드 용량(1MB)을 초과하였습니다.');
								} else {
									for(var i = 0; i < files.length; i++) {
										sendFile(files[i], this);
									}
								}
							}
						}
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

	function bytesToSize(bytes) {
		   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		   if (bytes == 0) return '0 Byte';
		   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		   return (bytes / Math.pow(1024, i)).toFixed(0) + ' ' + sizes[i];
		};
		
	
		
	/*Dropzone.options.myDropzone = {
			url: '/test',
			  paramName: "attach_file", // The name that will be used to transfer the file
			  maxFilesize: 10, // MB
			  maxFiles: 20,
			  addRemoveLinks: true,
			  acceptedFiles: 'image/gif',
			  autoProcessQueue: true,
			  dictCancelUpload: '삭제',
			  dictRemoveFile: '취소',
			  init: function () {
					btnUpload = $('#btn-upload');

					  this.on("sending", function(file, xhr, data) {
	                      data.append("tags", $('#input-tags').val());
						  btnUpload.html('... 업로드하는 중 ... <img src="/common/img/hourglass.gif">').addClass('disabled');
	                  });
						
					  this.on("uploadprogress", function(file, xhr, data) {
						  if(xhr >= 100)
						  {
							  btnUpload.html('업로드 완료. 처리중입니다... 기다려주세요.. 오래걸릴 수 있습니다. <img src="/common/img/hourglass.gif">');
						  }
	                  });
						
					  this.on("success", function(file, responseText) {
						  btnUpload.html('처리가 완료되었습니다. 이미지 페이지로 이동합니다.');
						  console.log(responseText);
						  location.href="/bbs/view/" + responseText
	                  });
						
					  this.on("complete", function(file, xhr, data) {
						  console.log(3);
	                  });
						
					  this.on("error", function(file, xhr, data) {
						  console.log(data);
						  btnUpload.html('알 수 없는 에러가 발생하였습니다.').removeClass('disabled');
	                  });
				  
				  
			  },
           accept: function (file, done) {
               if (file.name == "justinbieber.jpg") {
                   done("Naha, you don't."); //just in case!!!!
               } else {
                   //console.log("done!!!");
                   console.log(done());
               }
           },
           addedfile: function(file) {
        	   var _this = this;

               
               var removeButton = Dropzone.createElement("<button data-dz-remove " +
                   "class='del_thumbnail btn btn-default'><span class='glyphicon glyphicon-trash'></span></button>");

               removeButton.addEventListener("click", function (e) {
                   e.preventDefault();
                   e.stopPropagation();
                   var server_file = $(file.previewTemplate).children('.server_file').text();
                   // Do a post request and pass this path and use server-side language to delete the file
                   //          $.post(server_file,{'X-CSRFToken': $cookies.csrftoken}, 'json');
                   $http({
                       method: 'POSt',
                       url: server_file,
                       headers: {
                           'X-CSRFToken': $cookies.csrftoken
                       }
                   });
                   _this.removeFile(file);
               });
               file.previewElement.appendChild(removeButton);
           },
           headers: { "avatar": "avatarupload" }, //you might be also to piggyback of the headers in serverside
			};*/

	btn_write = $('.btn-write');
	btn_write_orig_text = btn_write.html();
	bbs_id = $('.document-info').attr('data-bbs-id');
	attached_file = [];
	
	var myDropzone = new Dropzone(".myDropzone", { 
		url: "/bbs/servlet_attach_file/" + bbs_id,
		paramName: "attach_file", // The name that will be used to transfer the file
		maxFilesize: 10, // MB
		maxFiles: 5,
		autoProcessQueue: true,
		dictCancelUpload: 'X',
		dictRemoveFile: 'X'
	});
	
	myDropzone.on("sending", function(file, xhr, data) {
		if($.inArray(file.name, attached_file) == -1)
		{
			data.append("tags", $('#input-tags').val());
			btn_write.html('... 처리중 ... <img src="/assets/img/hourglass.gif">').addClass('disabled');
		}
		else
		{
			this.removeFile(file);
			alert('같은 파일을 업로드 할 수 없습니다.');
		}
	});
	
	myDropzone.on("error", function (file, response, xhr) {
		console.log(response);
	});
	
	myDropzone.on("success", function (file, response) {
		data = $.parseJSON(response);
		if(data.success !== true)
		{
			alert(data.msg);
			this.removeFile(file);
		}
		else
		{
			$('.summernote-editor').summernote('insertImage', data.save_url, data.filename);
			file.editor_save_url = data.save_url;
			file.editor_filename = data.filename;
			attached_file.push(file.name);
			btn_write.removeClass('disabled').html(btn_write_orig_text);
		}
	});
	
	myDropzone.on("complete", function (file, response) {
		var _this = this;

		var insertButton = Dropzone.createElement('<button data-editor-insert ' +
        'class="btn-dz-editor-insert-file btn btn-info btn-sm" style="width:50%;"><span class="fa fa-plus"></span>삽입</button>');
		var removeButton = Dropzone.createElement('<button data-dz-remove ' +
        'class="btn-dz-remove-file btn btn-danger btn-sm" style="width:50%;"><span class="fa fa-remove"></span>삭제</button>');

        removeButton.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            
			var editor_code_tmp = $('.summernote-editor').summernote('code');
			$editor_code = $(editor_code_tmp);
			$editor_code.find("img[src$='"+file.editor_filename+"']").remove();
			attached_file.splice($.inArray(file.name, attached_file), 1);
			console.log($editor_code.html());
			
			$('.summernote-editor').summernote('code', $editor_code);
            /*
            $.ajax({
                type: 'POST',
                url: 'DeleteImage',
                data: name,
                dataType: 'json'
            });*/
            
            _this.removeFile(file);
        });
        
        insertButton.addEventListener("click", function (e) {
            e.preventDefault(); 
            e.stopPropagation();
            
            $('.summernote-editor').summernote('insertImage', file.editor_save_url, file.editor_filename);
        });
        file.previewElement.appendChild(insertButton);
        file.previewElement.appendChild(removeButton);
	});
});