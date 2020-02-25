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
		$app = JFactory::getApplication();

		// Get value
		$tcVersion = $app->input->post->getFloat('tcVersion', 0.0);
		$tcClient = $app->input->post->get('tcClient', '', 'STRING');

		// Get the model.
		$model = $this->getModel('content', 'TcModel');
		$getMaxTCVersion = $model->checkDuplicateAndLatestVersionTC($tcVersion, $tcClient);

		if ($getMaxTCVersion == 'newVersion')
		{
			// If T&C is first new version[new TC]
			echo 0;
		}
		else
		{
			echo $getMaxTCVersion;
		}

		jexit();
	}
}
