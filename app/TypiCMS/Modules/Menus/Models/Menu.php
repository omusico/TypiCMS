<?php namespace TypiCMS\Modules\Menus\Models;

use TypiCMS\Models\Base;
use TypiCMS\Services\ListBuilder\ListBuilder;

use App;

class Menu extends Base {

	use \Dimsav\Translatable\Translatable;

	protected $fillable = array(
		'name',
		'class',
		// Translatable fields
		'title',
		'status',
	);


	/**
	 * Translatable model configs.
	 *
	 * @var array
	 */
	public $translatedAttributes = array(
		'title',
		'status',
	);

	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'menus';

	public $view = 'menus';
	public $route = 'menus';

	/**
	 * lists
	 */
	public $order = 'name';
	public $direction = 'asc';


	/**
	 * Build a menu
	 *
	 * @return menu html
	 */	
	public function __call($name, $arguments = null)
	{
		if ( ! $arguments) {
			$class = isset($arguments['class']) ? $arguments['class'] : '' ;
			$arguments = array('class' => $class);
		} else {
			$arguments = $arguments[0];
		}
		if ($name == 'languages') {
			return with(new ListBuilder)->languagesMenuHtml($arguments);
		}
		$items = App::make('TypiCMS\Modules\Menulinks\Repositories\MenulinkInterface')->getMenu($name);
		return with(new ListBuilder($items))->buildPublic()->toHtml($arguments);
	}

	/**
	 * Relations
	 */
	public function menulinks()
	{
		return $this->hasMany('TypiCMS\Modules\Menulinks\Models');
	}


	/**
	 * Validation rules
	 */
	public static $rules = array(
		'name' => 'required',
	);


}