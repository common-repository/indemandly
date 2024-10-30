<?php
if (!defined('DB_NAME'))
    die('Error: Plugin "wp-indemandly" does not support standalone calls, damned hacker.');
class wpIndemandlyAdmin extends wpIndemandly
{
    function __construct()
    {
        $this->init_lang();
        $this->load_options();
        add_action('admin_menu', array($this, 'modify_menu'));
    }

    function update()
    {
        $this->options = $_REQUEST['options'];
        $this->update_options();
        echo '<div class="updated">' . __('Options updated.', 'wp-indemandly') . '</div>';
        $this->load_options();
    }

    function modify_menu()
    {
        add_options_page(
            'Indemandly',
            'Indemandly',
            'manage_options',
            __FILE__,
            array($this, 'admin_options')
        );
    }

    function option_page()
    {
        ?>
        <p><?php _e('Indemandly.com plugin', 'wp-indemandly'); ?></p>
        <p>
          <form method="post" action="">
              <?php wp_nonce_field('wp-indemandly', 'update-options');

              $opt = $this->GetOptionInfo();
              foreach ($opt as $arr) {
                  $this->show_option($arr);
              }
              ?>
              <input type="submit" name="submit" value="<?php _e('Save Changes', 'wp-indemandly') ?>" class="button-primary"/>
              <br/><br/>
              To find your <b>username</b> check business tab at <a target="_blank" href="https://indemandly.com/business">Indemandly dashboard</a> <br/>
              To update your business information, widget look and manage enquiries visit your <a target="_blank" href="https://indemandly.com/login">Indemandly dashboard</a> <br/>
          </form>
        </p>
        <?php
    }

    function show_option($arr)
    {
        if ($arr['type'] == 'chk') {
            echo '<br><input type="checkbox" name="options[' . $arr['name'] . ']" value="1"';
            if ($this->options[$arr['name']])
                echo ' checked';
            echo '>' . $arr['label'];
        } elseif ($arr['type'] == 'txt') {
            echo '<br>' . $arr['label'] . ':<br><input class="regular-text code" type="text" name="options[' . $arr['name'] . ']" value="' . $this->options[$arr['name']] . '">';
        } elseif ($arr['type'] == 'text') {
            echo '<p>' . $arr['label'] . ':</p>';
            echo '<textarea name="options[' . $arr['name'] . ']" class="large-text code" rows="6" cols="50">' . $this->options[$arr['name']] . '</textarea>';
        }
        echo '<br/><br/>';
    }

    function admin_options()
    {
        echo '<div class="wrap"><h2>Indemandly widget configuration</h2>';
        if ($_REQUEST['submit']) {
            check_admin_referer('wp-indemandly', 'update-options');
            $this->update();
        } else
            $this->option_page();
        echo '</div>';

    }
}
