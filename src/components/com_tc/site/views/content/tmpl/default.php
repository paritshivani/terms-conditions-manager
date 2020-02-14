<?php
/**
 * @version    SVN: <svn_id>
 * @package    Com_Tc
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2016-2017 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
?>
		<script>
			function validateform(form)
			{
				var tc_id = <?php echo (int) $this->tc_id; ?>;
				var user_id = <?php echo (int) $this->user_id; ?>;

				if(jQuery("#agree").is(':checked') == true)
				{
					return true;
				}
				else
				{
					alert('<?php echo JText::_("COM_TC_LATEST_TERMSANDCONDITIONS_NOT_ACCETED_ERROR"); ?>');
					return false;
				}
			}
		</script>
		<?php if (!empty($this->tc_id))
		{?>
			<div class="alert alert-warning">
				<b><?php echo JText::_("COM_TC_TERMS_AND_CONDITIONS_MSG"); ?></b>
			</div>
			<div class="well">
				<!-- <?php echo JText::_("COM_TC_TERMS_AND_CONDITIONS_MSG"); ?> -->

			<div class="">
				<h1><?php echo $this->termsandconditions->title; ?></h1>
				<b><?php echo JText::_("COM_TC_LATEST_TERMSANDCONDITIONS_VERSION") . $this->termsandconditions->version; ?>&nbsp;&nbsp;
				<?php echo JText::_("COM_TC_LATEST_TERMSANDCONDITIONS_UPDATED_DATE") . $this->termsandconditions->modified_on; ?></b>
			</div>
			<br>
			<div class="">
				<?php
						echo nl2br($this->termsandconditions->content);
		 } ?>
			</div>
<?php
		$app        = JFactory::getApplication();
		$input      = $app->input;
		$returnURL     = $input->get('return', '', 'STRING');
?>
		<br><br>
		<div class="">
			 <form action="" method="post" name="form" onsubmit="return validateform(this)">
			 <div class="checkbox">
				<label class="padded-l-0"><input id="agree" type="checkbox" name="accept" value="1"> <?php	echo JText::_('COM_TC_LATEST_TERMSANDCONDITIONS_AGREE'); ?><span></span></label>
			</div>
			<input type="hidden" name="option" value="com_tc">
			<input type="hidden" name="task" value="content.accept()">
			<input type="hidden" name="user_id" value="<?php echo $this->user_id; ?>">
			<input type="hidden" name="tc_id" value="<?php echo $this->tc_id; ?>">
			<input type="hidden" name="return_url" value="<?php echo $returnURL; ?>">
			<button class="btn btn-primary" type="submit" value="Submit" name="Submit">
			<?php echo JText::_('COM_TC_ACCEPT_TERMSANDCONDITIONS_BUTTON'); ?></button>
			</form>
		</div>
		</div>
