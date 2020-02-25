<?php
/**
 * @version    SVN: <svn_id>
 * @package    Com_Tc
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2016-2017 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Filesystem\File;

$tjInstallerPath = JPATH_ROOT . '/administrator/manifests/packages/tc/tjinstaller.php';

if (File::exists(__DIR__ . '/tjinstaller.php'))
{
	include_once __DIR__ . '/tjinstaller.php';
}
elseif (File::exists($tjInstallerPath))
{
	include_once $tjInstallerPath;
}

/**
 * Script file of activitystream component
 *
 * @since  0.0.1
 */
class Pkg_TcInstallerScript extends TJInstaller
{
	protected $extensionName = 'tc';

	/** @var array The list of extra modules and plugins to install */
	private $installationQueue = array(
		'postflight' => array(
		// Plugins => { (folder) => { (element) => (published) }* }*
		'plugins' => array(
			'system' => array(
				'tc' => 1
			)
		)
		)
	);

	protected $extensionsToEnable = array (
	// 0 - type, 1 - name, 2 - publish?, 3 - client, 4 - group, 5 - position
	array ('plugin', 'tc', 1, 1, 'system', ''));

	/**
	 * method to install the component
	 *
	 * @param   STRING  $parent  parent
	 *
	 * @return void
	 */
	public function install($parent)
	{
		// Enable the extensions on fresh install
		$this->enableExtensions();
	}

	/**
	 * method to uninstall the component
	 *
	 * @param   STRING  $parent  parent
	 *
	 * @return void
	 */
	public function uninstall($parent)
	{
		// Uninstall subextensions
		$status = $this->uninstallSubextensions($parent);

		return $status;
	}

	/**
	 * method to update the component
	 *
	 * @param   STRING  $parent  parent
	 *
	 * @return void
	 */
	public function update($parent)
	{
		$this->installSqlFiles($parent);
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @param   STRING  $type    type
	 * @param   STRING  $parent  parent
	 *
	 * @return void
	 */
	public function preflight($type, $parent)
	{
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @param   STRING  $type    type
	 * @param   STRING  $parent  parent
	 *
	 * @return void
	 */
	public function postflight($type, $parent)
	{
		// Copy tjinstaller file into packages folder
		$this->copyInstaller($parent);

		// Install subextensions
		$status = $this->installSubextensions($parent);

		// Show the post-installation page
		$this->renderPostInstallation($status);
	}

	/**
	 * installSqlFiles
	 *
	 * @param   JInstaller  $parent  parent
	 *
	 * @return  void
	 */
	public function installSqlFiles($parent)
	{
		$db = JFactory::getDBO();

		// Obviously you may have to change the path and name if your installation SQL file ;)
		if (method_exists($parent, 'extension_root'))
		{
			$sqlfile = $parent->getPath('extension_root') . '/admin/sql/install.mysql.utf8.sql';
		}
		else
		{
			$sqlfile = $parent->getParent()->getPath('extension_root') . '/sql/install.mysql.utf8.sql';
		}

		// Don't modify below this line
		$buffer = file_get_contents($sqlfile);

		if ($buffer !== false)
		{
			jimport('joomla.installer.helper');
			$queries = JInstallerHelper::splitSql($buffer);

			if (count($queries) != 0)
			{
				foreach ($queries as $query)
				{
					$query = trim($query);

					if ($query != '' && $query{0} != '#')
					{
						$db->setQuery($query);

						if (!$db->query())
						{
							JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));

							return false;
						}
					}
				}
			}
		}
	}

	/**
	 * Method to copy installer file
	 *
	 * @param   JInstaller  $parent  Class calling this method
	 *
	 * @return  void
	 */
	protected function copyInstaller($parent)
	{
		$src  = $parent->getParent()->getPath('source') . '/tjinstaller.php';
		$dest = JPATH_ROOT . '/administrator/manifests/packages/tc/tjinstaller.php';

		File::copy($src, $dest);
	}
}
