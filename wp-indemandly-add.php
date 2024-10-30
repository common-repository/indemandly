<?php
if (!defined('DB_NAME'))
    die('Error: Plugin "wp-indemandly" does not support standalone calls, damned hacker.');

class wpIndemandlyAdd extends wpIndemandly
{

    function inline_scripts()
    {
        $domain = $this->options['domain'];
        $domain = explode(".", trim($domain), 2);
        $domain = $domain[0];
        $domain = str_replace('http://', '',  $domain);
        $domain = str_replace('https://', '', $domain);
        $domain = str_replace(':', '', $domain);
        $domain = str_replace('/', '', $domain);
        $domain = str_replace('"', '', $domain);
        $domain = str_replace("'", '', $domain);

        ?>
        <script type="text/javascript">
          (function (d) {
            var s = d.createElement('script')
            s.src = 'https://widget.indemand.ly/launcher.js'
            s.onload = function () {
              new Indemandly({ domain: '<?php echo $domain; ?>'  })
            }
            var c = d.getElementsByTagName('script')[0]
            c.parentNode.insertBefore(s, c)
          })(document)
        </script>

        <?php
    }

    function load_scripts()
    {
        if( is_front_page() || is_page() ||  is_home() || is_single() || is_category() ) {
            add_action('wp_head', array($this, 'inline_scripts'));
        }

    }
    function __construct()
    {
        $this->load_options();
        add_action('wp_enqueue_scripts', array($this, 'load_scripts'));
    }
}
