<?php

namespace helvete\Controls;

use App;

class TabControl extends \Nette\Application\UI\Control
{
	/**
	 * Tab items data in format:
	 *	$tabs = array(
	 *		'itemKey' => array(
	 *			'name' => 'Very nice name!',
	 *			'data' => $someControlToNest,
	 *			'active' => null,
	 *		),
	 *	);
	 *
	 * @var array
	 */
	private $_items = [];

	/**
	 * Component name
	 */
	private $_name;

	/**
	 * Template file name
	 */
	private $_tpl = '/TabControl.latte';

	/**
	 * Component construct
	 *
	 * @param  \Nette\Application\UI\PresenterComponent	$parent
	 * @param  string 									$name
	 * @param  array									$items
	 */
	public function __construct($parent, $name, $items = [])
	{
		parent::__construct($parent, $name);

		$this->_tpl = __DIR__ . $this->_tpl;
		$this->_name = $name;
		$this->setItems($items);
	}


	/**
	 * Render component
	 *
	 * @return void
	 */
	public function render()
	{
		$template = $this->template;

		if (!file_exists($this->_tpl)) {
			throw new \Exception("Missing template file: {$this->_tpl}");
		}
		$template->setFile($this->_tpl);

		$this->_activate();
		$template->items = $this->_items;

		$template->render();
	}


	/**
	 * Set items
	 *
	 * @param  array	$items
	 * @return self
	 */
	public function setItems(array $items)
	{
		$this->_items = $items;

		return $this;
	}


	/**
	 * Set template file
	 *
	 * @param  string	$filePath
	 * @return self
	 */
	public function setTemplate($filePath)
	{
		$this->_tpl = $filePath;

		return $this;
	}


	/**
	 * Make sure at least one tab is marked active
	 *
	 * @return void
	 */
	private function _activate()
	{
		$active = $this->fetchActive();
		if (!is_null($active) && array_key_exists($active, $this->_items)) {
			$this->_items[$active]['active'] = true;
			return;
		}
		if (!array_search(
			true,
			array_map(
				function($element) {
					return $element['active'];
				},
				$this->_items)
			)
		) {
			$first = array_shift($this->_items);
			$first['active'] = true;
			array_unshift($this->_items, $first);
		}
	}


	/**
	 * Store active tab info
	 *
	 * @param  string	$activeItemKey
	 * @return self
	 */
	public function storeActive($activeItemKey)
	{
		$sessionSection = $this->getPresenter()->getSession($this->_name);
		$sessionSection->params['active'] = $activeItemKey;

		return $this;
	}


	/**
	 * Return key of active item
	 *
	 * @return string
	 */
	public function fetchActive()
	{
		$sessionSection = $this->getPresenter()->getSession($this->_name);

		if (is_null($sessionSection->params)
			|| is_null($sessionSection->params['active'])
		) {
			return null;
		}

		return $sessionSection->params['active'];
	}


	/**
	 * Handle signal for storing active tab into session
	 *
	 * @param  string	$activeItemKey
	 * @return void
	 */
	public function handleActivate($activeItemKey)
	{
		$this->storeActive($activeItemKey);
	}
}
