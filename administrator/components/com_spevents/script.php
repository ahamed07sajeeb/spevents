<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of Spevents component
 */
class Com_speventsInstallerScript{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) {
		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option=com_spevents&view=events');
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) {
		echo '<p>' . JText::_('COM_SPEVENTS_UNINSTALL_TEXT') . '</p>';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) {
		// $parent is the class calling this method
		echo '<p>' . JText::sprintf('COM_SPEVENTS_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) {
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '<p>' . JText::_('COM_SPEVENTS_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) {
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '<p>' . JText::_('COM_SPEVENTS_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
	}
}
