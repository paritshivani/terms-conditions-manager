<?php
/**
 * @version    SVN: <svn_id>
 * @package    Com_Tc
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2016-2017 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

jimport('joomla.application.component.controllerform');

/**
 * Content controller class.
 *
 * @since  1.6
 */
class TcControllerContent extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'contents';
		parent::__construct();
	}

	/**
	 * Method to check valid TC based on client & version values via AJAX.
	 *
	 * @return void
	 *
	 * @since  3.0
	 */
	public function checkDuplicateAndLatestVersionTC()
	{
		$app = Factory::getApplication();

		// Get value
		$tcVersion = $app->input->post->getFloat('tcVersion', 0.0);
		$tcClient = $app->input->post->get('tcClient', '', 'STRING');

		// Get the model.
		$model = $this->getModel('content', 'TcModel');
		$getMaxTCVersion = $model->checkDuplicateAndLatestVersionTC($tcVersion, $tcClient);

		if ($getMaxTCVersion == 'newVersion')
		{
			// If T&C is first new version[new TC]
			echo 1;
		}
		else
		{
			echo $getMaxTCVersion;
		}

		jexit();
	}

	/**
		* Function to save field data
		*
		* @param   string  $key     key
		* @param   string  $urlVar  urlVar
		*
		* @return  boolean|void
		*/
	public function save($key = null, $urlVar = null)
	{
		$app = Factory::getApplication();
		$input = Factory::getApplication()->input;
		$task = $input->get('task');
		$data = $input->post->get('jform', '', 'ARRAY');
		$model = $this->getModel('content');
		$form = $model->getForm($data);
		$tc_id = $data['tc_id'];
		$data = $model->validate($form, $data);
		$msg = Text::_('COM_TC_SAVED_SUCCESSFULLY');
		// Check for errors.
		if ($data === false)
		{
			$app->enqueueMessage(Text::_('COM_TC_ENTER_URL_PATTERN', 'error'));
			$this->setRedirect(Route::_('index.php?option=com_tc&view=content&layout=edit&tc_id=' . $tc_id, false));

			return false;
		}

		$result = $model->save($data);

		if (!$result)
		{
			$app->enqueueMessage(Text::_('COM_TC_INVALID_DATA', 'error'));
			$this->setRedirect(Route::_('index.php?option=com_tc&view=content&layout=edit&tc_id=' . $tc_id, false));

			return false;

		}

		switch ($task)
		{
			case 'apply':
				// Redirect back to the edit screen.
			$redirect = Route::_('index.php?option=com_tc&view=content&layout=edit&tc_id=' . $tc_id, false);
				$app->redirect($redirect, $msg);
					break;
			case 'save2new':

				// Redirect back to the edit screen.
				$redirect = Route::_('index.php?option=com_tc&view=content&layout=edit', false);
				$app->redirect($redirect, $msg);
					break;

				default:
				// Redirect to the list screen.
				$redirect = Route::_('index.php?option=com_tc&view=contents', false);
				$app->redirect($redirect, $msg);
					break;
		}
	}
}
