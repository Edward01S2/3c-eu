<?php

add_filter( 'gform_init_scripts_footer', '__return_true' );
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
add_filter( 'gform_confirmation_anchor', '__return_true');

add_filter( 'gform_tabindex', 'cc_disable_gform_tabindex', 10, 2 );
function cc_disable_gform_tabindex( $tab_index, $form = false ) {
  return -1;
}

add_filter( 'gform_field_css_class', 'cc_gform_field_custom_type_class', 10, 3 );
function cc_gform_field_custom_type_class( $classes, $field, $form ) {
  if ( ! is_admin() ) {
    $classes .= sprintf( ' gfield_%s', esc_attr( $field->type ) );
    if ( $field['inputMask'] && $field['inputMaskValue'] == '99999' ) {
      $classes .= ' gfield_zip';
    }
    if ( isset( $field['size'] ) && empty( $field['size'] ) !== true ) {
      $classes .= sprintf( ' %s', esc_attr( $field['size'] ) );
    }
  }
  return $classes;
}

// add_filter( 'gform_notification', 'cc_change_notification_email', 10, 3 );
function cc_change_notification_email( $notification, $form, $entry ) {
  if ( $notification['name'] == 'Admin Notification' ) {
    return null;
  }
  return $notification;
}

// add_filter( 'gform_cdata_open', 'cc_gform_spinner' );
function cc_gform_spinner( $content = '' ) {
  ?>
  <script>
    document.addEventListener( "DOMContentLoaded", function() {
      gform.addFilter( "gform_spinner_target_elem", function( jQuerytargetElem, formId ) {
        return jQuery( ".gform_footer" );
      } );
    }, false );
  </script>
  <?php
}

// add_filter( 'gform_cdata_open', 'cc_wrap_gform_cdata_open' );
// function cc_wrap_gform_cdata_open( $content = '' ) {
// 	$content = 'document.addEventListener( "DOMContentLoaded", function() { ';
// 	return $content;
// }

// add_filter( 'gform_cdata_close', 'cc_wrap_gform_cdata_close' );
// function cc_wrap_gform_cdata_close( $content = '' ) {
// 	$content = ' }, false );';
// 	return $content;
// }

add_filter( 'gform_ajax_spinner_url', 'cc_gf_spinner_replace', 10, 2 );
function cc_gf_spinner_replace( $image_src, $form ) {
	return  'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
}

add_filter( 'gform_submit_button', 'cc_form_submit_button', 10, 2 );
function cc_form_submit_button( $button, $form ) {
  if ( rgar( $form, 'cc_show_privacy' ) && ( $privacy = get_field( 'form_privacy', 'option' ) ) ) {
    return sprintf( '<div class="privacy">%s</div>', $privacy ) . $button;
  }
  return str_replace( "class='", "class='full-width ", $button );
}

add_filter( 'gform_get_form_filter', 'cc_get_form_filter', 10, 2 );
function cc_get_form_filter( $form_string, $form ) {
  if ( rgar( $form, 'cc_show_long_privacy' ) ) {
    if ( $disclaimer = get_field( 'form_infusionsoft_disclaimer', 'option' ) ) {
      $form_string .= sprintf( '<div class="infusion-disclaimer has-border-container"><div class="border-container">%s</div></div>', $disclaimer );
    }
    if ( $disclaimer = get_field( 'form_3c_disclaimer', 'option' ) ) {
      $form_string .= sprintf( '<div class="ccc-disclaimer">%s</div>', $disclaimer );
    }
    return $form_string;
  }
  return $form_string;
}



add_filter( 'gform_form_settings', 'cc_privacy_form_setting', 10, 2 );
function cc_privacy_form_setting( $settings, $form ) {
  $settings[ __( 'Form Layout', 'gravityforms' ) ]['cc_show_privacy'] = '
    <tr>
      <th><label for="cc_show_privacy">Short Privacy Policy</label></th>
      <td>
        <input type="checkbox" id="cc_show_privacy" name="cc_show_privacy" value="1"' . checked( rgar( $form, 'cc_show_privacy' ) ) . '>
        <label for="cc_show_privacy">Show privacy policy text</label>
      </td>
    </tr>';
  $settings[ __( 'Form Layout', 'gravityforms' ) ]['cc_show_long_privacy'] = '
    <tr>
      <th><label for="cc_show_long_privacy">Long Privacy Policy</label></th>
      <td>
        <input type="checkbox" id="cc_show_long_privacy" name="cc_show_long_privacy" value="1"' . checked( rgar( $form, 'cc_show_long_privacy' ) ) . '>
        <label for="cc_show_long_privacy">Show long privacy policy after form</label>
      </td>
    </tr>';
  $settings[ __( 'Form Layout', 'gravityforms' ) ]['cc_show_entries'] = '
    <tr>
      <th><label for="cc_show_entries">Show Entries</label></th>
      <td>
        <input type="checkbox" id="cc_show_entries" name="cc_show_entries" value="1"' . checked( rgar( $form, 'cc_show_entries' ) ) . '>
        <label for="cc_show_entries">Show entries list above form</label>
      </td>
    </tr>';
  if ( rgar( $form, 'cc_show_entries' ) ):
  $settings[ __( 'Form Layout', 'gravityforms' ) ]['cc_show_entries_fields'] = '
    <tr>
      <th><label for="cc_show_entries_fields">-- Fields</label></th>
      <td>
        <textarea rows="3" class="fieldwidth-3" type="text" id="cc_show_entries_fields" name="cc_show_entries_fields">' . rgar( $form, 'cc_show_entries_fields' ) . '</textarea>
        <p class="description">Use Field IDs in brackets to replace values.  Example: {1} {2}, {3} -- "FirstName LastName, Email"</p>
      </td>
    </tr>';
  endif;
  return $settings;
}

add_filter( 'gform_pre_form_settings_save', 'save_cc_show_privacy_setting' );
function save_cc_show_privacy_setting($form) {
  $form['cc_show_privacy'] = rgpost( 'cc_show_privacy' );
  $form['cc_show_long_privacy'] = rgpost( 'cc_show_long_privacy' );
  $form['cc_show_entries'] = rgpost( 'cc_show_entries' );
  $form['cc_show_entries_fields'] = rgpost( 'cc_show_entries_fields' );
  return $form;
}

// add_filter( 'gform_form_args', 'cc_setup_form_args', 10, 1 );
// function cc_setup_form_args( $args ) {
//   $form_args = array(
//     'display_title' => false,
//     'display_description' => false,
//   );
//   return $form_args;
// }

add_shortcode( 'gravityentries', 'cc_gform_entry_shortcodes' );
function cc_gform_entry_shortcodes( $atts ) {
  $id = $atts['id'];
  $form = GFAPI::get_form( $id );
// }

// add_filter( 'gform_pre_render', 'cc_gform_pre_render', 99 );
// function cc_gform_pre_render( $form ) {
  if ( rgar( $form, 'cc_show_entries' ) && ( $template = rgar( $form, 'cc_show_entries_fields' ) ) ) {
    $template = str_replace( PHP_EOL, '<br>', $template );
    $entries = GFAPI::get_entries( $form['id'] );
    if ( count( $entries ) > 0 ) {
      preg_match_all( '/{(\d)}+/', $template, $template_fields );
      if ( count( $template_fields[0] ) > 0 ) {
        ob_start();
        ?>
        <style>
        #form-entries {
          overflow-y: auto;
          /* margin-bottom: 40px; */
          padding: 20px 30px;
          background-color: #fff;
          color: #006691;
        }
        @media only screen and (max-width: 499px) {
          #form-entries {
            max-height: 600px;
          }
        }
        @media only screen and (min-width: 500px) {
          #form-entries {
            columns: 2;
          }
        }
        @media only screen and (min-width: 1024px) {
          #form-entries {
            columns: 3;
          }
        }
        #form-entries p {
          margin: 0 0 15px;
          break-inside: avoid;
        }
        </style>
        <?php
        echo '<div id="form-entries">';
        foreach ( $entries as $entry ) {
          $line = $template;
          foreach ( $template_fields[1] as $index => $field ) {
            $line = str_replace( $template_fields[0][$index], $entry[$field], $line );
          }
          echo sprintf( '<p>%s</p>', $line );
        }
        echo '</div>';
        $content = ob_get_clean();
        return $content;
      }
    }
  }
  // return $form;
}
