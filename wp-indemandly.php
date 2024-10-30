<?php
if (!defined('DB_NAME'))
    die('Error: Plugin "wp-indemandly" does not support standalone calls, damned hacker.');
#@error_reporting(E_ALL ^ E_NOTICE);#disable extra error reporting
/*
Plugin Name: Indemandly
Plugin URI: https://indemandly.com
Description: Indemandly.com widget plugin. To set up, visit <a href="options-general.php?page=indemandly/wp-indemandly-options.php">configuration panel</a>.
Version: 1.0.3
Author: indemandly.com
Text Domain: indemandly
License: GPLv2 or later
Min WP Version: 2.6
Max WP Version: 4.9.4
*/

class wpIndemandly
{
    var $options;/*all plugin options*/

    function init_lang()
    {
        $plugin_dir = basename(dirname(__FILE__));
        load_plugin_textdomain('wp-indemandly', false, $plugin_dir . '/lang');
    }

    function update_options()
    {
        $opt = $this->GetOptionInfo();
        foreach ($opt as $key => $arr) {
            $name = $arr['name'];
            if (!isset($this->options[$name]))
                $this->options[$name] = '0';//for damn checkboxes
        }
        foreach ($this->options as $i => $val)
            $this->options[$i] = stripslashes($val);
        $r = update_option('wp_indemandly', $this->options);
        if (!$r) {
            if (serialize($this->options) != serialize(get_option('wp_noexternallinks'))) {
                echo '<div class="error">' . __('Failed to update options!', 'wp-indemandly') . '</div>';
            }
            /*else echo 'nothing changed ;_;';*/
        }
    }

    function GetOptionInfo()
    {
        return array(
            array('name' => 'domain', 'def_value' => 'your-username', 'type' => 'txt', 'label' => __('Indemandly username', 'wp-indemandly')),
        );
    }


    function load_options()
    {
        $opt = $this->GetOptionInfo();
        $this->options = get_option('wp_indemandly');
        if (!$this->options)
            $this->options = array();
        /*check if options are fine*/
        foreach ($opt as $key => $arr) {
            $name = $arr['name'];
            if (!isset($this->options[$name]) && $arr['def_value'])/* no option value, but it should be*/ {
                $val = $arr['def_value'];
                $this->options[$name] = $val;
            }
        }

    }
}

if (is_admin()) {
    include_once(plugin_dir_path(__FILE__) . 'wp-indemandly-options.php');
    new wpIndemandlyAdmin();
} else {
    include_once(plugin_dir_path(__FILE__) . 'wp-indemandly-add.php');
    new wpIndemandlyAdd();
}

?>
