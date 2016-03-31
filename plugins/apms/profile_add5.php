<?php
	if ($pun_config['o_pms_enabled'] == '1' && $pun_user['g_pm'] == '1') :
?>
				<script type="text/javascript">
				//<![CDATA[
				function switchEtatByCheck(id_element, id_from_element)
				{
					if (!document.getElementById) { return; }

					var element = document.getElementById(id_element);

					if (document.getElementById(id_from_element).checked==false) {
						element.blur();
						element.disabled = true;
					}
					else {
						element.disabled = false;
						element.focus();
					}
				}
				//]]>
				</script>
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang_pms['Private Messages'] ?></legend>
						<div class="infldset">
<?php if ($pun_config['o_pms_notification'] == '1') : ?>
							<div class="rbox">
								<label><input type="checkbox" id="notify_pm" name="form[notify_pm]" value="1"<?php if ($user['notify_pm'] == 1) echo ' checked="checked"' ?> /><?php echo $lang_pms['email_option'] ?><br /></label>
								<p><?php echo $lang_pms['email_option_infos'] ?></p>
								<label><input type="checkbox" id="notify_pm_full" name="form[notify_pm_full]" value="1"<?php if ($user['notify_pm_full'] == 1) echo ' checked="checked"' ?> /><?php echo $lang_pms['email_option_full'] ?><br /></label>
							</div>
<?php else : ?>
							<input type="hidden" id="notify_pm" name="form[notify_pm]" value="0" />
							<?php endif; ?>
						</div>
					</fieldset>
				</div>
<?php endif; ?>
