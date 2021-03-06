<?php
defined('_JEXEC') or die;


class SpeventsModelSessions extends JModelList
{
	public function __construct(array $config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = [
				'id','a.id',
				'title','a.title',
				'date','a.date','event_id','a.event_id',
				'hall','a.hall',
				'created_by','a.created_by',
				'enabled','a.enabled',
				'access','a.access',
				'created_on','a.created_on',
				'ordering','a.ordering',
				'language','a.language',

			];
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = 'a.ordering', $direction = 'asc')
	{
		$app = JFactory::getApplication();
		$context = $this->context;

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access');
		$this->setState('filter.access', $access);

		$published = $this->getUserStateFromRequest($this->context . '.filter.enabled', 'filter_enabled', '');
		$this->setState('filter.enabled', $published);

		$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		$events = $this->getUserStateFromRequest($this->context . '.filter.events', 'filter_events', '');
		$this->setState('filter.events', $events);

		parent::populateState($ordering, $direction);
	}

	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.enabled');
		$id .= ':' . $this->getState('filter.language');
		$id .= ':' . $this->getState('filter.events');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		$app = JFactory::getApplication();
		$state = $this->get('State');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select(
			$this->getState('list.select','a.*')
		);
		$query->from('#__spevents_sessions as a');
		$published = $this->getState('filter.enabled',false);

		if (is_numeric($published))
		{
			$query->where($db->quoteName('a.enabled') . ' = ' . (int)$published);
		}
		elseif ($published == '')
		{
			$query->where($db->quoteName('a.enabled') . ' IN (0, 1)');
		}

		$user = JFactory::getUser();
		$user_id = $user->get('id','INT');
		if ($user_id)
		{
			$query->where($db->quoteName('a.created_by') . '=' . $db->quote($user_id));
		}

		//filter by keyword
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search,true) . '%');
			$query->where('a.title LIKE '. $search );
		}

		//get filtered data by events
		$filter_events = $this->getState('filter.event');
		if (!empty($filter_events))
		{
			$query->where($db->quoteName('a.event_id') . '=' . $db->quote($filter_events));
		}

		//filter by speaker
		if ($speaker = $this->getState('filter.speaker'))
		{
			$speakers = SpeventsHelper::findInObject('speakers', '#__spevents_sessions', $speaker);
			if (count($speakers))
			{
				$query->where($db->quoteName('a.id') . ' IN ( '. implode(',', $speakers) . ' )');
			}
			else
			{
				$quere->where($db->quoteName('a.id') . ' < 0');
			}
		}


		$orderCol = $this->getState('list.ordering','a.ordering');
		$orderDirn = $this->getState('list.direction','asc');

		$order = $db->escape($orderCol) . ' ' . $db->escape($orderDirn);
		$query->order($order);

		return $query;
	}

	//Prepare data for sidebar filter by events 
	public function eventFilterOptions()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("a.id, a.title");
		$query->from($db->quoteName('#__spevents_events','a'));

		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}
}