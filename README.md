# tab-control
Simple nette tabcontrol component with active tab session cache

Allows for easy controls nesting under tabs. Template utilizes bootstrap classes.

Usage example within a presenter:

```php
<?php

namespace App\Presenters;

class DemoPresenter extends Nette\Application\UI\Presenter
{

	/**
	 * Default action
	 *
	 * @return void
	 */
	public function renderDefault()
	{
		$tabs = array(
			'testComponentOne' => array(
				'name' => 'Test component One',
				'data' => $this['testComponentOne'],
				'active' => null,
			),
			'testComponentTwo' => array(
				'name' => 'Test component Two',
				'data' => $this['testComponentTwo'],
				'active' => null,
			),
		);
		$this['demoTabControl']->setItems($tabs);
	}


	/**
	 * Factory for component One
	 *
	 * @return Control
	 */
	public function createComponentTestComponentTwo()
	{
		...
	}


	/**
	 * Factory for component Two
	 *
	 * @return Control
	 */
	public function createComponentTestComponentOne()
	{
		...
	}


	/**
	 * Creates component demoTabControl
	 *
	 * @return void
	 */
	public function createComponentDemoTabControl()
	{
		return new \helvete\Controls\TabControl($this, 'demoTabControl');
	}


	...
}
```
