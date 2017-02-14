<?php

/**
*
* Annual Stars extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 RMcGirr83
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbmodders\annualstars\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	static public function getSubscribedEvents()
	{
		return array(
			'core.viewtopic_cache_user_data'	=> 'viewtopic_cache_user_data',
			'core.viewtopic_cache_guest_data'	=> 'viewtopic_cache_guest_data',
			'core.viewtopic_modify_post_row'	=> 'viewtopic_modify_post_row',
			'core.memberlist_view_profile'		=> 'memberlist_view_profile',
		);
	}

	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	*
	* @param \phpbb\user	$user		User object
	*/
	public function __construct(\phpbb\template\template $template, \phpbb\user $user)
	{
		$this->template = $template;
		$this->user = $user;
	}

	public function viewtopic_cache_user_data($event)
	{
		$array = $event['user_cache_data'];
		$stars = $this->annual_stars($event['row']['user_regdate']);
		$array['annual_stars'] = $stars;
		$event['user_cache_data'] = $array;
	}

	public function viewtopic_cache_guest_data($event)
	{
		$array = $event['user_cache_data'];
		$stars = '';
		$array['annual_stars'] = $stars;
		$event['user_cache_data'] = $array;
	}

	public function viewtopic_modify_post_row($event)
	{
		$event['post_row'] = array_merge($event['post_row'], array('ANNUAL_STARS' => $event['user_poster_data']['annual_stars']));
	}

	public function memberlist_view_profile($event)
	{
		$stars = $this->annual_stars($event['member']['user_regdate']);

		$this->template->assign_vars(array(
			'ANNUAL_STARS'	=> $stars,
		));
	}

	private function annual_stars($reg_date)
	{
		$this->user->add_lang_ext('phpbbmodders/annualstars', 'annualstars');
		$stars = '';
		if ($reg_years = (int) ((time() - (int) $reg_date) / 31536000))
		{
			$reg_output = sprintf($this->user->lang['YEAR_OF_MEMBERSHIP'], $reg_years);

			if($reg_years > 1)
			{
				$reg_output = sprintf($this->user->lang['YEARS_OF_MEMBERSHIP'], $reg_years);
			}
			$stars = str_repeat($this->generate_stars($reg_output), $reg_years);
		}
		return $stars;
	}

	private function generate_stars($reg_output)
	{
		return '<span class="imageset icon_annual_star" title="' . $reg_output . '"></span>';
	}
}
