<?php
/*
Plugin Name: Embed Wave
Plugin URI: http://signpostmarv.name/embed-wave/
Description: Allows multiple waves to be embedded within the same page!
Version: 1.3
Author: SignpostMarv Martin
Author URI: http://signpostmarv.name/
 Copyright 2009 SignpostMarv Martin  (email : embed-wave.wp@signpostmarv.name)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class Marvulous_Embed_Wave_provider
{
	public $_id_format;
	public $_WavePanel;
	public function Marvulous_Embed_Wave_provider($id_format,$WavePanel)
	{
		$this->_id_format = $id_format;
		$this->_WavePanel = $WavePanel;
	}
	public function id_format()
	{
		return $this->_id_format;
	}
	public function WavePanel()
	{
		return $this->_WavePanel;
	}
}
class Marvulous_Embed_Wave
{
	public function providers($id=null)
	{
		$providers = apply_filters('Marvulous_Embed_Wave::provider',array());
		if(isset($id) == false)
		{
			return $providers;
		}
		else if(isset($providers[$id]) == true)
		{
			return $providers[$id];
		}
		else
		{
			return false;
		}
	}
	public function add_default_providers($value)
	{
		$value['google-wave'] = new Marvulous_Embed_Wave_provider('googlewave.com!w+_idgoeshere_','https://wave.google.com/wave/');
		$value['google-wave-sandbox'] = new Marvulous_Embed_Wave_provider('wavesandbox.com!w+_idgoeshere_','https://wave.google.com/a/wavesandbox.com/');
		return $value;
	}
	public function deactivate()
	{
		remove_shortcode('wave');
	}
	public function plugins_loaded()
	{
		wp_register_script('Google Wave Embed API','http://wave-api.appspot.com/public/embed.js');
		wp_register_script('Marvulous_Embed_Wave',$this->plugin_file('js'),array('jquery','Google Wave Embed API'));
		wp_register_style('Marvulous_Embed_Wave',$this->plugin_file('css'));
		add_shortcode('wave',array($this,'shortcode'));
		add_action('wp_print_scripts',array($this,'print_scripts'));
		add_action('wp_print_styles',array($this,'print_styles'));
		add_action('wp_head',array($this,'js'));
	}
	public function plugin_file($file)
	{
		return untrailingslashit(trailingslashit(trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) )) . 'marvulous.wave.wp.' . $file);
	}
	public function print_styles()
	{
		wp_enqueue_style('Marvulous_Embed_Wave');
	}
	public function print_scripts()
	{
		wp_enqueue_script('Marvulous_Embed_Wave');
	}
	public function shortcode($atts,$content='')
	{
		extract(shortcode_atts(array(
			'id' => '',
			'type'=>'',
			'height'=>'',
			'width'=>'',
		),$atts));
		$type = trim($type);
		$id = trim($id);
		$height = trim($height);
		$width = trim($width);
		$content = trim($content);
		$_height = false;
		$_width  = false;
		$cssunit_regex = '/^\d+(\.[\d]+)?(\%|px|em)$/';
		if(strlen($content) > 0)
		{
			$content = '<div class="alt-content">' . htmlentities2($content) . '</div>';
		}
		if(preg_match($cssunit_regex,$height) == 1)
		{
			$_height = true;
		}
		else
		{
			unset($height);
		}
		if(preg_match($cssunit_regex,$width) == 1)
		{
			$_width = true;
		}
		else
		{
			unset($width);
		}
		if($id == '')
		{
			return;
		}
		if($type == '')
		{
			$type = 'google-wave';
		}
		$style = '';
		if($_height != false || $_width != false)
		{
			$style = ' style="';
			if($_height != false)
			{
				$style .= 'height:' . esc_attr($height) . ';';
			}
			if($_width != false)
			{
				$style .= 'width:' . esc_attr($width) . ';';
			}
			$style .= '"';
		}
		if($this->providers($type) == false)
		{
			return;
		}
		else
		{
			return '<div id="' . esc_attr($id) . '" class="wave-panel ' . esc_attr($type) . '"' . $style . '>' . $content . '</div>';
		}
	}
	public function js()
	{
		$id_format = array();
		$WavePanel = array();
		foreach($this->providers() as $label=>$provider)
		{
			$id_format[] = '\'' . js_escape($label) . '\' : \'' . js_escape($provider->id_format()) . '\'';
			$WavePanel[] = '\'' . js_escape($label) . '\' : \'' . js_escape($provider->WavePanel()) . '\'';
		}
		echo '<script type="text/javascript">',"\n",
			'marvulous.wave.wp[\'_id_format\'] = {'  , "\n\t",
				implode(',' . "\n\t\t",$id_format) , "\n",'};' , "\n" ,
			'marvulous.wave.wp[\'_WavePanel\'] = {' , "\n\t",
				implode(',' . "\n\t\t",$WavePanel) , "\n",'};' , "\n" ,
			'jQuery(document).ready(marvulous.wave.wp.detect);',"\n",
			'</script>',"\n";
		;
	}
	public function widgets_init()
	{
		return register_widget('Marvulous_Embed_Wave_Widget');
	}
}
class Marvulous_Embed_Wave_Widget extends WP_Widget
{
	function Marvulous_Embed_Wave_Widget( $id_base = false, $widget_options = array(), $control_options = array() )
	{
		parent::WP_Widget($id_base,'Embed Wave',$widget_options,$control_options);
	}
    public function widget($args, $instance) {
        extract( $args );
		$_Marvulous_Embed_Wave = new Marvulous_Embed_Wave;
		$content = isset($instance['content']) ? $instance['content'] : null;
		if(isset($instance['content']))
		{
			unset($instance['content']);
		}
		$instance['id'] = 'wp_sidebar::' . $instance['id'];
		echo $before_widget,$_Marvulous_Embed_Wave->shortcode($instance,$content),$after_widget;
    }
	function update($new, $old) {
		return $new;
	}
	public function form($instance)
	{
		$providers = apply_filters('Marvulous_Embed_Wave::provider',array());
		$select_this_provider = empty($instance) ? key($providers) : $instance['type'];
		$value = empty($instance) ? '' : ' value="' . esc_attr($instance['id']) . '"';
?>
		<p><label for="<?php echo $this->get_field_id('id'); ?>">Wave ID: </label><input type="text" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>"<?php echo $value; ?> /></p>
		<p><label for="<?php echo $this->get_field_id('type'); ?>">Provider: </label><select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
<?php
		foreach(array_keys($providers) as $provider)
		{
?>
			<option value="<?php echo esc_attr($provider); ?>"<?php if(empty($instance) === true && $provider === $select_this_provider){?> selected="selected"<?php }?> ><?php echo htmlentities2(str_replace(array('-','_'),' ',$provider)); ?></option>
<?php
		}
?>
		</select></p>
<?php
	}
}
$Marvulous_Embed_Wave = new Marvulous_Embed_Wave;
add_filter('Marvulous_Embed_Wave::provider',array($Marvulous_Embed_Wave,'add_default_providers'));
register_activation_hook(__FILE__,array($Marvulous_Embed_Wave,'activate'));
register_deactivation_hook(__FILE__,array($Marvulous_Embed_Wave,'deactivate'));
add_action('plugins_loaded',array($Marvulous_Embed_Wave,'plugins_loaded'));
add_action('widgets_init',array($Marvulous_Embed_Wave,'widgets_init'));
?>