<html>
<body>
	<h1><?= sprintf(lang('email_forgot_password_heading'), $identity);?></h1>
	<p><?= sprintf(lang('email_forgot_password_subheading'), anchor('reset-kata-sandi/'. $forgotten_password_code, lang('email_forgot_password_link')));?></p>
</body>
</html>