<?php
/*
  Plugin Name: Nano Slider
  Description: Nano slider for wordpress
  Author: Maxanderson
  Version: 1.0
 */

function set_nano_defaults()
{
    $opt = array(
		'nano_width'  => '560',
		'nano_height'  => '560',
        'nano_effect'  => 'random',
        'nano_slices'  => '13',
        'nano_boxCols' => '10',
        'nano_boxRows' => '5',
        'nano_animSpeed' => '600',
        'nano_pauseTime' => '2500',
        'nano_startSlide' => '0',
        'nano_directionNav' => 'true',
        'nano_controlNav' => 'true',
        'nano_pauseOnHover' => 'true',
        'nano_randomStart'  => 'true',
    );

    foreach ( $opt as $k => $v )
    {
        update_option($k, $v);
    }

    

    return;
}
register_activation_hook(__FILE__, 'set_nano_defaults');

function delete_nano_options() {


$opt = array(
		'nano_width'  => '560',
		'nano_height'  => '560',
        'nano_effect'  => 'random',
        'nano_slices'  => '13',
        'nano_boxCols' => '10',
        'nano_boxRows' => '5',
        'nano_animSpeed' => '600',
        'nano_pauseTime' => '2500',
        'nano_startSlide' => '0',
        'nano_directionNav' => 'true',
        'nano_controlNav' => 'true',
        'nano_pauseOnHover' => 'true',
        'nano_randomStart'  => 'true'
    );

    foreach ( $opt as $k => $v )
    {
        delete_option($k, $v);
    }
	
}

register_deactivation_hook(__FILE__, 'delete_nano_options');

class nano_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct('nano_Widget', 'Nano Slider', array('description' => __('A Nano Slider Widget', 'text_domain')));
    }

    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Widget Slider', 'text_domain');
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }
    public function widget($args, $instance) {
        extract($args);
        //the title	
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        if (!empty($title))
            echo $before_title . $title . $after_title;
        echo nano_slider('nano_widget');
        echo $after_widget;
    }

}

function nano_register_scripts() {
    if (!is_admin()) {
        // register  
        wp_register_script('nano-script', plugins_url('js/jquery.nano.slider.js', __FILE__));
        // enqueue  
        wp_enqueue_script('jquery');
        wp_enqueue_script('nano-script');
    }
}

function nano_register_styles() {
    // register  
    wp_register_style('nano_styles', plugins_url('nano-slider.css', __FILE__));
    wp_register_style('nano_styles_theme', plugins_url('themes/default/default.css', __FILE__));

    // enqueue  

    wp_enqueue_style('nano_styles');
    wp_enqueue_style('nano_styles_theme');
}

function nano_widgets_init() {
    register_widget('nano_Widget');
}

function nano_slider($type='nano_slider') {
   
    $args = array('post_type' => 'nano-slider', 'posts_per_page' => -1,'orderby'=>'menu_order');
	?>
<style type="text/css">
.slider-wrapper {
<?php	if ( wp_is_mobile() ) { ?>
    width:100%;
    height:100%;
	<?php	} else 	{	?>
	width:<?php echo get_option('nano_width') ? get_option('nano_width'):560; ?>px;
    height:<?php echo get_option('nano_height')? get_option('nano_height'):560; ?>px; 
	<?php } ?>
	margin:10px auto;
}
#nano_slider {
    <?php	if ( wp_is_mobile() ) { ?>
    width:100%;
    height:100%;
	<?php	} else 	{	?>
	width:<?php echo get_option('nano_width') ? get_option('nano_width'):560; ?>px;
    height:<?php echo get_option('nano_height')? get_option('nano_height'):560; ?>px; 
	<?php } ?>
	margin:0 auto;
}
</style>
<script type="text/javascript">
jQuery(window).load(function() {
	jQuery('#nano_slider').nanoSlider({
	effect: '<?php echo get_option('nano_effect')? get_option('nano_effect'):'random';?>', 
    slices: <?php echo get_option('nano_slices')? get_option('nano_slices') :'13';?>,
    boxCols: <?php echo get_option('nano_boxCols') ? get_option('nano_boxCols'):'10';?>,
    boxRows: <?php echo get_option('nano_boxRows') ?  get_option('nano_boxRows'):'5' ;?>,
    animSpeed: <?php echo get_option('nano_animSpeed') ? get_option('nano_animSpeed'):'600';?>,
    pauseTime: <?php echo get_option('nano_pauseTime')? get_option('nano_pauseTime'):'2500';?>,
    startSlide: <?php echo get_option('nano_startSlide') ? get_option('nano_startSlide'):'1';?>, 
    directionNav: <?php echo get_option('nano_directionNav') ? get_option('nano_directionNav'):'true';?>, 
    controlNav: <?php echo get_option('nano_controlNav') ? get_option('nano_controlNav'):'true';?>,   
    pauseOnHover: <?php echo get_option('nano_pauseOnHover') ? get_option('nano_pauseOnHover') : 'true';?>, 
    randomStart:  <?php echo get_option('nano_randomStart')? get_option('nano_randomStart') : 'true';?> 
	});
});
</script>


    <div class="slider-wrapper theme-default">
   <div id="nano_slider" class="nanoSlider">
   <?php
    //the loop
    $loop = new WP_Query($args);
	 if ($loop->have_posts()) {
    while ($loop->have_posts()) {
        $loop->the_post();
		$the_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
        echo '<img title="'.get_the_title().'" src="' . $the_url[0] . '" alt=""/>';
    }
	 }
	else
	{
	$default_images=array('1.jpg','2.jpg','3.jpg','4.jpg');
	
		foreach($default_images as $img)
		{
		 echo '<img src="'.plugins_url('images/'. $img , __FILE__). '" alt=""/>';
		}
		
	}
	?>
    </div>
   </div>
<?php
}

function nano_init() {
    add_shortcode('nano-slider', 'nano_slider');
    
    add_image_size('nano_slider_img', 180, 100, true);
  
    $args = array('public' => true, 'label' => 'Nano Slider','supports' => array('title', 'thumbnail','page-attributes'));
    register_post_type('nano-slider', $args);
}

function nano_settings_menu(){
    add_submenu_page( 'edit.php?post_type=nano-slider', 'Settings', 'Settings', 'manage_options', 'nano-settings-menu', 'nano_settings' );
}
add_action( 'admin_menu', 'nano_settings_menu' );

add_action('admin_init', 'nano_reg_function' );

function nano_reg_function() {
	register_setting( 'nano-settings-group', 'nano_effect' );
	register_setting( 'nano-settings-group', 'nano_slices' );
	register_setting( 'nano-settings-group', 'nano_boxCols' );
	register_setting( 'nano-settings-group', 'nano_boxRows' );
	register_setting( 'nano-settings-group', 'nano_animSpeed' );
	register_setting( 'nano-settings-group', 'nano_pauseTime' );
	register_setting( 'nano-settings-group', 'nano_startSlide' );
	register_setting( 'nano-settings-group', 'nano_directionNav' );
	register_setting( 'nano-settings-group', 'nano_controlNav' );
	register_setting( 'nano-settings-group', 'nano_randomStart' );
	register_setting( 'nano-settings-group', 'nano_pauseOnHover' );
	register_setting( 'nano-settings-group', 'nano_width' );
	register_setting( 'nano-settings-group', 'nano_height' );
}


function nano_settings() {

?>

<div class="wrap">
<h2>Nano Slider Setting</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'nano-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Type of Animation</th>
        <td>
        <label>
        <?php $effect = get_option('nano_effect'); ?>
        <?php $effects_array=array('random','sliceDownRight','sliceDownLeft','sliceUpRight','sliceUpLeft', 'sliceUpDown','sliceUpDownLeft','fold','fade', 'boxRandom','boxRain','boxRainReverse','boxRainGrow','boxRainGrowReverse','slideInFromRight','slideInFromLeft','slideInFromTop','slideInFromBottom','foldReverse');?>
        <select name="nano_effect" id="nano_effect">
        <?php foreach($effects_array as $value) { ?>
        <option value="<?php echo $value;?>" <?php if($effect == $value) echo 'selected="selected"'; ?>><?php echo $value;?></option>
        <?php } ?>
        </select>
        </label>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Number of slices</th>
        <td>
        <label>
        <input type="text" name="nano_slices" id="nano_slices" size="7" value="<?php echo get_option('nano_slices'); ?>" />
        </label>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Box Cols</th>
        <td>
        <label>
        <input type="text" name="nano_boxCols" id="nano_boxCols" size="7" value="<?php echo get_option('nano_boxCols'); ?>" />
        </label>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Box Rows</th>
        <td>
        <label>
        <input type="text" name="nano_boxRows" id="nano_boxRows" size="7" value="<?php echo get_option('nano_boxRows'); ?>" />
        </label>
        </td>
        </tr>
 <tr valign="top">
        <th scope="row">Transition Speed</th>
        <td>
        <label>
        <input type="text" name="nano_animSpeed" id="nano_animSpeed" size="7" value="<?php echo get_option('nano_animSpeed'); ?>" />
        </label>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Slide Pause Time</th>
        <td>
        <label>
        <input type="text" name="nano_pauseTime" id="nano_pauseTime" size="7" value="<?php echo get_option('nano_pauseTime'); ?>" />
        </label>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Starting Slide</th>
        <td>
        <label>
        <input type="text" name="nano_startSlide" id="nano_startSlide" size="7" value="<?php echo get_option('nano_startSlide'); ?>" />
        </label>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Next & Prev Navigation</th>
        <td>
        
        <input type="radio" name="nano_directionNav" value="true" <?php if(get_option('nano_directionNav') == 'true') echo ' checked="checked"'; ?>  /> Yes
        <input type="radio" name="nano_directionNav" value="false" <?php if(get_option('nano_directionNav') == 'false') echo ' checked="checked"'; ?> /> No
       
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Control Navigation</th>
        <td>
        
        <input type="radio" name="nano_controlNav" value="true" <?php if(get_option('nano_controlNav') == 'true') echo ' checked="checked"'; ?>  /> Yes
        <input type="radio" name="nano_controlNav" value="false" <?php if(get_option('nano_controlNav') == 'false') echo ' checked="checked"'; ?> /> No
        
        </td>
        </tr>
        
         <tr valign="top">
        <th scope="row">Random Start</th>
        <td>
        
        <input type="radio" name="nano_randomStart" value="true" <?php if(get_option('nano_randomStart') == 'true') echo ' checked="checked"'; ?>  /> Yes
        <input type="radio" name="nano_randomStart" value="false" <?php if(get_option('nano_randomStart') == 'false') echo ' checked="checked"'; ?> /> No
       
        </td>
        </tr>
        
         <tr valign="top">
        <th scope="row">Pause OnHover</th>
        <td>
        
        <input type="radio" name="nano_pauseOnHover" value="true" <?php if(get_option('nano_pauseOnHover') == 'true') echo ' checked="checked"'; ?>  /> Yes
        <input type="radio" name="nano_pauseOnHover" value="false" <?php if(get_option('nano_pauseOnHover') == 'false') echo ' checked="checked"'; ?> /> No
       
        </td>
        </tr>
        
		<tr valign="top">
        <th scope="row">Width</th>
        <td>
        <label>
        <input type="text" name="nano_width" id="nano_width" size="7" value="<?php echo get_option('nano_width'); ?>" />px
        </label>
        </td>
        </tr>

		<tr valign="top">
        <th scope="row">Height</th>
        <td>
        <label>
        <input type="text" name="nano_height" id="nano_height" size="7" value="<?php echo get_option('nano_height'); ?>" />px
        </label>
        </td>
        </tr>

    </table>
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>
</div>

<?php } 


//hooks
add_theme_support('post-thumbnails');
add_action('init', 'nano_init');
add_action('widgets_init', 'nano_widgets_init');
add_action('wp_print_scripts', 'nano_register_scripts');
add_action('wp_print_styles', 'nano_register_styles');
?>