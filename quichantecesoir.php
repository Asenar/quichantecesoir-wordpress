<?php
/*
Plugin Name: Qui Chante Ce Soir plugin
Plugin URI: http://github.com/Asenar/quichantecesoir
Plugin Description: Le plugin Quichantecesoir permet aux artistes d'afficher les dates saisies sur quichantecesoir.com directement sur leur site, soit sur un code sur une page dédiée, soit avec un widget dans l'un des emplacement prévu par Wordpress.
Description: Le plugin Quichantecesoir permet aux artistes d'afficher les dates saisies sur quichantecesoir.com directement sur leur site, soit sur un code sur une page dédiée, soit avec un widget dans l'un des emplacement prévu par Wordpress.
Author: Michaël Marinetti
Plugin Author: Michaël Marinetti
Author URI: http://www.quichantecesoir.com
Version: 0.1
 */

class Qccs {
  public static $script_url = '//quichantecesoir.com/js/widget.js';
  public static $available_cells = 'bigdate,date,cp,city,cp_city_country,main,spectacle,spectacle_only,title,lieu,lieu_address,contact,address,note,link';
  public static $default_order = 'bigdate,cp_country,main,note,link';

  private static $inst = null;
  static public function instance() {
    if (is_null(Qccs::$inst)) {
      self::$inst = new Qccs;
    }
    return self::$inst;
  }
  public function __construct() {
    if ( is_admin() ) {
      add_action('admin_menu', [$this, 'admin_menu']);
      add_action('admin_init', [$this, 'plugin_admin_init']);
      add_action('media_buttons_context', ['Qccs', 'qccs_add_button']);
    }
    else {
      add_action('wp_head', array($this, 'wp_head'));
    }

    // Shortcode
    add_shortcode('quichantecesoir', array($this, 'shortcode'));

    // Widget
    add_action('widgets_init', create_function('', 'return register_widget("Qccs_Widget");'));

    $this->options = get_option('qccs_options');
  }

  public function qccs_add_button($context) {

  $title = 'Ajouter le tag quichantecesoir';

  $context .= '<button type="button" class="button thickbox" title="'.$title.'"
    id="add_qccs_template_tag">
	Qui Chante Ce Soir
	</button>';
  $script = <<<SCRIPT
<script type="text/javascript">
				jQuery(document).ready(function() {
				  jQuery('#add_qccs_template_tag').on('click', function() {
				  	window.send_to_editor('[quichantecesoir table=1 custom_order="bigdate,cp_country,main,note,link"]');
					tb_remove();
				  })
				});
</script>
SCRIPT;
  $context .= $script;

  return $context;
}

  // Admin menu management.
  public function admin_menu() {
    add_options_page('Qui Chante Ce Soir', 'Qui Chante Ce Soir', 'manage_options', 'quichantecesoir-settings', array($this, 'settings'));
  }

  public function wp_head() {
    //echo "\n<script type='text/javascript' src='".Qccs::$script_url."'></script>\n";
    if ( !empty($this->options['custom_css']) ) {
      echo '<style type="text/css">' . $this->options['custom_css'] . '</style>';
    }
  }

  // Manage plugin settings
  public function settings() {
?>
    <div>
    <div class="wrap" id="quichantecesoir_wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2>Qui Chante Ce Soir</h2>
    <form action="options.php" method="post">
<?php
    settings_fields('plugin_options');
    do_settings_sections('qccs');
?>
    <input name = "Submit" type="submit" class="button-primary" tabindex="1" value="<?php esc_attr_e('Save Settings'); ?>" />
    </form></div>
<?php
  }
  // Register_settings
  public function plugin_admin_init() { // whitelist options
    register_setting('plugin_options', 'qccs_options', array($this, 'options_validate'));
    add_settings_section('settings_section', 'Configuration générale', array($this, 'main_description'), 'qccs');
    add_settings_field('all_settings', 'Toutes les options', array($this,'settings_inputs'), 'qccs', 'settings_section');

  }
  //The Settings Inputs
  public function settings_inputs() {
    $options = get_option('qccs_options');

		echo "<tr><td>";
		echo "<p><label for='qccs_options[url]'><strong>url</strong></label><br>";
    $url = esc_attr( $options['url'] );
		echo "<input id='qccs_options_url' name='qccs_options[url]' type='text' value='$url' /><br>";
		echo "Si non vide, le template <code>[quichantecesoir]</code> utilisera cette url";
		echo "</p>";

    echo "<p><label for='qccs_options[custom_order]'><strong>custom_order</strong></label></p>";
    $custom_order = esc_attr( $options['custom_order'] );
    echo "<input id='qccs_options_custom_order' name='qccs_options[custom_order]' type='text' value='$custom_order' /></p>";
    echo '<div class="help-block">';
    echo 'défaut: <code>'.self::$default_order.'</code>';
    echo 'cases dispo : <code>'.self::$available_cells.'</code>';
    echo '</div>';

    echo "<p><label for='qccs_options[title]'><strong>title</strong></label></p>";
    $title = esc_attr( $options['title'] );
    echo "<input id='qccs_options_title' name='qccs_options[title]' type='text' value='$title' /></p>";

    echo "<p><label for='qccs_options[table]'><strong>Table</strong></label></p>";
    echo '<label>';
    $table = esc_attr( $options['table'] );
    echo "<input id='qccs_options_table' name='qccs_options[table]' type='checkbox' value='1' ".($table?'checked="checked"':'')." />";
    echo "Cochez cette case pour générer un tableau html (affichage correct dès l'installation) plutôt que des éléments neutres (nécessite une personnalisation css)</p>";
    echo '</label>';


    $css = esc_attr( $options['custom_css'] );
    echo "<p>
      <strong>CSS actuel</strong>
      <br>
			Utilisez ce champ pour voir les règles css actuelles, ou les modifier / ajouter / supprimer pour personnaliser l'affichage
      </p>
      <div style='border:1px solid #ccc;background:#f5f5f5;color:#444;max-height:200px;overflow:auto;'>"
      ."<pre class='css' id='default-css'>$css</pre></div>";
    echo "<p>
      <strong>Modifier:</strong>
      <br>
      <textarea name='qccs_options[custom_css]' style='width:100%;height:300px;' tabindex='1'>$css</textarea>
      </p>";
		echo "<h3>Aide CSS</h3>";
		echo <<<EXAMPLE_CSS
<dl>

<dt>ID et Classes Css principales</dt>
<dd><pre>
#qccs-date-list    { /* conteneur principal */}
.table-qccs        { /* pour le mode table uniquement */}
.qccs-tbody        { /* pour le mode table uniquement */}
.qccs-event-box    { /* conteneur d'un événement */ }
.qccs-cell         { /* tous les blocs d'info */ }
.qccs-images       { /* le bloc des images (s'il est affiché) */}
.qccs-cp_country   { /* code postal et ville */}
.qccs-date         { /* date et heure */}
.qccs-main         { /* liste des artistes + lieu */}
.qccs-content_note { /* information supplémentaire importante */}
.qccs-event_link   { /* lien "détail" vers quichantecesoir */}
</pre>
</dd>

<dt>Exemple pour avoir une liste avec un effet "zébré"</dt>
<dd><pre>
.table-qccs .qccs-event-box{background-color:#eee}
div.table-qccs .qccs-event-box{border-bottom:1px solid #ccf}
.table-qccs .qccs-event-box:hover{background-color:#fff}
.table-qccs .qccs-event-box:nth-child(odd){background-color:#eee}
.table-qccs .qccs-event-box:nth-child(even){background-color:#ddd}   
.table-qccs .qccs-event-box > div {border: 0}
</pre>
</dd>

</dl>
EXAMPLE_CSS;
		echo '</td></tr>';
  }
  //Validation
  public function options_validate($input) {
    return $input;
  }
  // [quichantecesoir] shortcode
  public function shortcode( $atts, $content = null, $code = '' ) {
    return $this->template_tag($atts, false);
  }

  // actual processing of the template tag
  public function template_tag( $params = array(), $echo = true ) {
    if ( !is_array( $params ) ) {
      $str = $params;
      $params = array();
      parse_str($str, $params);
    }
    if ( empty($params['url']) ) {
      $params['url'] = $this->options['url'];
    }
    if ( empty($params['title']) ) {
      $params['title'] = $this->options['title'];
    }
    if ( empty($params['artist_name']) ) {
      $params['artist_name'] = $this->options['artist_name'];
    }

    if ( empty($params['custom_order']) ) {
      $params['custom_order'] = $this->options['custom_order'];
    }
    if (empty($params['table'])) {
      $params['table'] = $this->options['table'];
    }

    $qccs_options = [];
    if ($params['table']) {
      $qccs_options[] = 'data-qccs-table="1"';
    } else {
      $qccs_options[] = 'data-qccs-table="0"';
    }

    if ($params['custom_order']) {
      $qccs_options[] = 'data-qccs-order="'.$params['custom_order'].'"';
    }

    $classes = 'qccs-widget';
    $output = '<a href="'.$params['url'].'" class="'.$classes.'" '.implode(' ', $qccs_options).'>'
      .($params['artist_name']?$params['artist_name']:'Les prochaines dates sur qui chante ce soir').'</a>';
    $output .= "<script type='text/javascript' src='".Qccs::$script_url."'></script>";
    $options = get_option('qccs_options');
    if ( $echo ) {
      echo $output;
    }
    else {
      return $output;
    }
  }

} // end Qccs

//
// Quichantecesoir Widget
//
class Qccs_Widget extends WP_Widget {

  public function __construct() {
    parent::__construct(false, $name = 'Qui Chante Ce Soir');
  }

  public function widget( $args, $instance ) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    echo $before_widget;
    if ( $title )
      echo $before_title . $title . $after_title;
    $params = [
      'url' => $instance['url'],
      'display_limit' => $instance['display_limit'],
      'table'         => $instance['table'],
      'custom_order'  => $instance['custom_order'],
      'artist_name'   => $instance['artist_name'],
    ];
    Qccs::instance()->template_tag($params);
    echo $after_widget;
  }

  public function update( $new_instance, $old_instance ) {
    $instance = $new_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['url'] = strip_tags(stripslashes($new_instance['url']));
    $instance['display_limit'] = strip_tags(stripslashes($new_instance['display_limit']));
    $instance['table'] = (bool)$new_instance['table'];
    return $instance;
  }

  public function form( $instance ) {
    if ( empty($instance['url']) ) {
      $options = get_option('qccs_options');
      $instance['url'] = $options['url'];
    }
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'widget-form.php';
  }

} // end quichantecesoir JS Widget


global $qccs;
$qccs = Qccs::instance();
