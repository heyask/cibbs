<form>
<input type="password" name="password" id="password">
<input type="password" name="password_confirm" id="password_confirm">
<input type="hidden" name="auth_key" id="auth_key" value="<?php echo $this->input->get('key'); ?>">
<button id="btn-reset-password">비밀번호 재설정하기</button>
</form>