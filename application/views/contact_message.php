<h1>Send a message</h1><? if ($show_form) $style = "color:red"; else $style = ""; ?><? if ($message) { ?>	<p style="<?= $style ?>"><?= $message ?></p><? } ?><? if ($show_form) { ?><div id="contact"><form method="POST" action=""><input type="hidden" name="action" value="message" /><table><tr>	<th>To:</th>	<td><? if ($fixed_recipient) {		?><input type="hidden" name="to" value="<?=$form_to?>" /><?		if ($form_to == "ml_bug_report") echo "MasterList Code Monkeys";		elseif ($form_to == "ml_abuse") echo "MasterList Abuse Police";		else echo $form_to;	} else { ?>		<input type="textbox" name="to" value="<?= $form_to ?>" />	<? } ?>	</td></tr><tr>	<th>Message:</th>	<td><textarea name="message" style="width: 500px; height: 200px"><?= $form_message ?></textarea></td></tr><tr>	<th></th>	<td><input type="submit" value="Send message" /></tr></table></form></div><? } else { ?><a href="<?= $base ?>">Go back home</a><? } ?>