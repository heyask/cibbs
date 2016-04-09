<form action="/bbs/write_update/test">
<input type="hidden" name="document_num" value="0">
<input type="hidden" name="target_document_num" value="0">
<input type="text" name="title" value="asd">
<div class="summernote-editor"></div>
<button type="submit">asd</button>
</form>
<div class="document-info" data-bbs-id="test"></div>
<form action="#" class="myDropzone">
	<div class="dz-default dz-message">
		<div class="dz-icon icon-wrap icon-circle icon-wrap-md">
			<i class="fa fa-cloud-upload fa-3x"></i>
		</div>
		<div>
			<p class="dz-text">첨부할 파일이 있다면 여기로 드래그하세요.</p>
			<p class="text-muted">또는 여기를 클릭해서 선택하세요.</p>
		</div>
	</div>
	<div class="fallback">
		<input name="file" type="file" multiple />
	</div>
</form>

<button type="submit" class="btn btn-default btn-write">제출</button>