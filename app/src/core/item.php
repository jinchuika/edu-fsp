<?php

class Item_menu {
	
	/**
	 * Reference to the menu manager
	 *
	 * @var Menu
	 */
	protected $manager;
	
	/**
	 * Item_menu's id
	 *
	 * @var int
	 */
	protected $id;
	
	/**
	 * Item_menu's parent_id
	 *
	 * @var int
	 */
	protected $parent_id;
	
	/**
	 * Item_menu's meta data
	 *
	 * @var array
	 */
	protected $meta;
	
	/**
	 * Item_menu's attributes
	 *
	 * @var array
	 */
	protected $attributes = array();
	
	/**
	 * Item_menu's hyperlink
	 *
	 * @var Link
	 */
	public    $link;


	/**
	 * Creates a new Lavary\Menu\MenuItem_menu instance.
	 *
	 * @param  Menu  $manager
	 * @param  string  $title
	 * @param  string  $url
     * @param  array  $attributes
     * @param  int  $parent_id
	 * @return void
	 */
	public function __construct($manager, $title, $url, $attributes = array(), $parent_id = 0)
	{
		$this->manager     = $manager;
		$this->id          = $this->id();
		$this->parent_id         = $parent_id;
		$this->title       = $title;
		$this->attributes  = $attributes;
		
		// Create an object of type Link
		$this->link        = new Link($title, $url);
	}

	/**
	 * Creates a sub Item_menu
	 *
	 * @param  string  $title
	 * @param  string|array  $options
	 * @return void
	 */
	public function add($title, $options)
	{
		if( !is_array($options) ) {
			$options = array('url' => $options);
		}
		
		$options['parent_id'] = $this->id;
				
		return $this->manager->add( $title, $options );
	}

	/**
	 * Generate a unique id for the item
	 *
	 * @return int
	 */
	protected function id()
	{
		return $this->manager->length() + 1;
	}

	/**
	 * Return Item_menu's id
	 *
	 * @return int
	 */
	public function get_id()
	{
		return $this->id;
	}

	/**
	 * Returns Item_menu's parent_id
	 *
	 * @return int
	 */
	public function get_parent_id()
	{
		return $this->parent_id;
	}

	/**
	 * Check if the item has any children
	 *
	 * @return boolean
	 */
	public function hasChildren()
	{
		return (count($this->manager->whereParent($this->id))) ? true : false;
	}

	/**
	 * Return Item_menu's children
	 *
	 * @return array
	 */
	public function children()
	{
		return $this->manager->whereParent($this->id);
	}


	/**
	 * Set or get items's attributes
	 *
	 * @return string|Item_menu
	 */
	public function attributes()
	{
		$args = func_get_args();

		if(is_array($args[0])) {
			$this->attributes = array_merge($this->attributes, $args[0]);
			return $this;
		}
		
		elseif(isset($args[0]) && isset($args[1])) {
			$this->attributes[$args[0]] = $args[1];
			return $this;
		} 
		
		elseif(isset($args[0])) {
			return isset($this->attributes[$args[0]]) ? $this->attributes[$args[0]] : null;
		}
		
		return $this->attributes;
	}


	/**
	 * Set or get items's meta data
	 *
	 * @return string|MenuItem_menu
	 */
	public function meta()
	{
		$args = func_get_args();

		if(is_array($args[0])) {
			$this->meta = array_merge($this->meta, $args[0]);
			return $this;
		}
		
		elseif(isset($args[0]) && isset($args[1])) {
			$this->meta[$args[0]] = $args[1];
			return $this;
		} 
		
		elseif(isset($args[0])) {
			return isset($this->meta[$args[0]]) ? $this->meta[$args[0]] : null;
		}
		
		return $this->meta;
	}

}