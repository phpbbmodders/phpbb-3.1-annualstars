<?php
/**
 *
 * Annual Stars extension for the phpBB Forum Software package
 *
 * @copyright (c) 2014 RMcGirr83
 * @copyright (c) 2026, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\annualstars;

/**
 * Annual Stars extension base
 */
class ext extends \phpbb\extension\base
{
	/**
	 * Check whether the extension can be enabled.
	 * The current phpBB version should meet or exceed
	 * the minimum version required by this extension.
	 *
	 * @return bool|array
	 * @access public
	 */
	public function is_enableable()
	{
		$enableable = $this->check_phpbb_version() && $this->check_php_version();

		if (!$enableable)
		{
			$language = $this->container->get('language');
			$language->add_lang('install_annualstars', 'phpbbmodders/annualstars');

			return $language->lang('ANNUALSTARS_NOT_ENABLEABLE');
		}

		return $enableable;
	}

	/**
	 * Require phpBB 3.3.19
	 *
	 * @return bool
	 */
	public function check_phpbb_version()
	{
		return phpbb_version_compare(PHPBB_VERSION, '3.3.19', '>=');
	}

	/**
	 * Require PHP 7.4
	 *
	 * @return bool
	 */
	public function check_php_version()
	{
		return PHP_VERSION_ID >= 70400;
	}
}
