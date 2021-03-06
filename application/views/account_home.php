<h1>Manage your account</h1>
<p>To manage your posts, <a href="<?=$url_base?>post/edit">click here</a>.</p>
<? if ($message) { ?>
<p class="info"><?=$message?></p>
<? } ?>
<? if ($errors) { ?>
<ul class="error">
<? foreach ($errors as $error) { echo "<li>$error</li>\r\n"; } ?>
</ul> <? } ?>

<table>
<tr>
<th>Change password</th>
<td>
<form action="" method="POST">
<table>
<tr><td>Current password:</td><td><input type="password" name="currentpw" /></td></tr>
<tr><td>New password:</td><td><input type="password" name="newpw" /></td></tr>
<tr><td>Re-enter the password:</td><td><input type="password" name="verifypw" /></td></tr>
<tr><td></td><td><input type="submit" name="changepw" value="Change my password"/></td></tr>
</table>
</form>
</td>
</tr>

<tr>
<form action="" method="POST">
<th>Change email</th>
<td><p>You can change your email address if you want to receive notifications somewhere else.&nbsp; That email address will be sent a confirmation email to make sure you own it.</p>
Your email address: <input type="textbox" name="email" value="<?=$email_address?>" style="width: 250px;"/>&nbsp; <input type="submit" name="changemail" value="Change my email address" />
</td>
</form>
</tr>

<tr>
<form action="" method="POST">
<th>Deactivate account</th>
<td>
<p>You can disable your account by clicking the button below.&nbsp; Please note that ALL of your posts will become inactive if you do so.</p>
<input type="checkbox" name="acknowledged" value="yes" />Yes, I understand that my posts will become inactive if I disable my account.<br /><br />
<input type="submit" name="disable" value="De-activate my account" />
</td>

</form>
</tr>
</table>