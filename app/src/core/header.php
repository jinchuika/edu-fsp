<?php
class Header
{

    function __construct($nivel_dir=0)
    {
        for ($temp_dir=0; $temp_dir < $nivel_dir; $temp_dir++) { 
            $this->nivel_dir .= '../';
        }
    }
    
    /**
     * Crea un nuevo item para el menu
     *
     * @param  string  $texto
     * @param  array|string  $options
     * @return Item_menu
     */
    public function add($texto, $options)
    {
        $url  = $this->getUrl($options);
        
            // if $data contains 'pid' we  set the given pid 
        $pid  = ( isset($options['pid']) ) ? $options['pid'] : null;
        
            // we seprate html attributes from reserved keys
        $attr = ( is_array($options) ) ? $this->extractAttr($options) : array();
        
            // making an instance of Item_menu class
        $item = new Item_menu($this, $texto, $url, $attr, $pid);
        
            // Add the item to the menu array
        array_push($this->menu, $item);
        
            // return the object just created
        return $item;
    }
    
    /**
     * Extract valid html attributes from user's options
     *
     * @param  array $options
     * @return array
     */
    public function extractAttr($options){
        return array_diff_key($options, array_flip($this->reserved));
    }
    
    public function imprimir_header()
    {
        if(1==1){
            ?>
            <div class="navbar">
                <div class="navbar-content">

                    <a href="#" class="element"><span class="icon-grid-view"></span> FUNSEPA <sup>2.0</sup></a>
                    <span class="element-divider"></span>
                    <a class="pull-menu" href="#"></a>
                    <ul class="element-menu">
                        <li>
                            <a class="dropdown-toggle" href="#">Products</a>
                            <ul class="dropdown-menu" data-role="dropdown">
                                <li><a href="#">Windows 8</a></li>
                                <li><a href="#">Skype</a></li>
                                <li><a href="#">Internet Explorer</a></li>
                                <li><a href="#">Office</a></li>
                            </ul>
                        </li>
                        
                        <li class="pull-right">
                            <a class="dropdown-toggle" href="#"><span class="icon-cog"></span></a>
                            <ul class="dropdown-menu" data-role="dropdown">
                                <li><a href="#">Products</a></li>
                                <li><a href="#">Download</a></li>
                                <li><a href="#">Support</a></li>
                                <li><a href="#">Buy Now</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="no-tablet-portrait">
                        <span class="element-divider"></span>
                        <a class="element brand" href="#"><span class="icon-spin"></span></a>
                        <a class="element brand" href="#"><span class="icon-printer"></span></a>
                        <span class="element-divider"></span>

                        <div class="element input-element">
                            <form>
                                <div class="input-control text">
                                    <input type="text" placeholder="Search...">
                                    <button class="btn-search"></button>
                                </div>
                            </form>
                        </div>

                        <div class="element place-right">
                            <a class="dropdown-toggle" href="#"><span class="icon-cog"></span></a>
                            <ul class="dropdown-menu place-right" data-role="dropdown">
                                <li><a href="#">Products</a></li>
                                <li><a href="#">Download</a></li>
                                <li><a href="#">Support</a></li>
                                <li><a href="#">Buy Now</a></li>
                            </ul>
                        </div>
                        <span class="element-divider place-right"></span>
                        <button class="element image-button image-left place-right">
                            Sergey Pimenov
                            <img src="images/me.jpg"/>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
}
?>