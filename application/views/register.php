<!DOCTYPE html>

<div>
	<?php echo validation_errors(); ?>
	<form action="/index.php/admin/register" method="post">
ID			<input type="text" id="id" name="id" value="<?=set_value('id')?>" placeholder="ID">
PW			<input type="password" id="pw" name="pw" value="<?=set_value('pw')?>" plcaeholder="Password">
RE-PW		<input type="password" id="re_pw" name="re_pw" value="<?=set_value('re_pw')?>" plcaeholder="Password Confirm">
NAME		<input type="name" id="name" name="name" value="<?=set_value('name')?>" plcaeholder="Name">

		<input type="submit" value="Register">
	</form>
</div>

