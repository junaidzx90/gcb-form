<?php
global $gcbRegAlerts;
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Gcb_Form
 * @subpackage Gcb_Form/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="gcb_form_wrapper">
  <div class="gcb_form_container">
    <div class="title_container">
      <h2>Registration Form</h2>
      <?php
      $success = null;
      if(empty($gcbRegAlerts)){
        $gcbRegAlerts = get_transient( 'user_created_success' );
        if(!empty($gcbRegAlerts)){
          $success = true;
        }
      }
      
      if(!empty($gcbRegAlerts)){
        ?>
        <div class="gcb_errors <?php echo (($success) ? 'success' : '') ?>"><i class="fas fa-exclamation-circle"></i>
          <p><?php echo $gcbRegAlerts ?></p>
        </div>
        <?php
      }
      ?>
    </div>
    <div class="row clearfix">
      <div class="form-wrapper">
        <form id="gcb__registration_form" method="post" enctype="multipart/form-data">
          <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope"></i></span>
            <input type="email" required oninvalid="setCustomValidity('You must have to add your email.')" oninput="setCustomValidity('')" name="gcb_reg_email" placeholder="Email" value="<?php echo ((isset($_POST['gcb_reg_email'])) ? $_POST['gcb_reg_email'] : '') ?>" />
          </div>
          <div class="input_field"> <span><i class="fas fa-id-badge"></i></span>
            <input type="number" required name="gcb_reg_pubgmid" placeholder="Pubgm ID" value="<?php echo ((isset($_POST['gcb_reg_pubgmid'])) ? $_POST['gcb_reg_pubgmid'] : '') ?>" />
          </div>
          <div class="row clearfix">
            <div class="col_half">
              <div class="input_field"> <span><i class="fas fa-user-tag"></i></span>
                <input type="text" required oninvalid="setCustomValidity('You must have to add your clan tag.')" oninput="setCustomValidity('')" name="gcb_reg_clan_tag" placeholder="Clan tag" value="<?php echo ((isset($_POST['gcb_reg_clan_tag'])) ? $_POST['gcb_reg_clan_tag'] : '') ?>" />
              </div>
            </div>
            <div class="col_half">
              <div class="input_field"> <span><i aria-hidden="true" class="fa fa-user"></i></span>
                <input type="text" required oninvalid="setCustomValidity('You must have to add your name.')" oninput="setCustomValidity('')" name="gcb_reg_name" placeholder="In game name" value="<?php echo ((isset($_POST['gcb_reg_name'])) ? $_POST['gcb_reg_name'] : '') ?>" />
              </div>
            </div>
          </div>

          <div class="input_field select_option"> <span><i class="fas fa-map-marker-alt"></i></span>
            <select required oninvalid="setCustomValidity('Please select your region.')" oninput="setCustomValidity('')" name="gcb_region">
              <option value="">Region</option>
              <?php
              $terms = get_terms( array(
                  'taxonomy' => 'sp_region',
                  'hide_empty' => false,
              ) );
              if($terms){
                foreach($terms as $term){
                  echo '<option value="'.$term->term_id.'">'.$term->name.'</option>';
                }
              }
              ?>
            </select>
          </div>
          
          <div class="input_field select_option"> <span><i class="fas fa-users"></i></span>
            <?php
            $args = array(
              'post_type' => 'sp_team',
              'name' => 'gcb_reg_team',
              'values' => 'ID',
              'option_none_value' => '',
              'selected' => ((isset($_POST['gcb_reg_team'])) ? $_POST['gcb_reg_team'] : ''),
              'show_option_none' => 'Select a team',
              'property'  => 'required oninvalid="setCustomValidity(\'You must have to add your pubgmid.\')" oninput="setCustomValidity(\'\')"'
            );
            sp_dropdown_pages( $args );
            ?>
            <div class="select_arrow"></div>
          </div>
          <div class="input_field"> 
            <div class="profile_image_upload">
              <img src="" alt="profile-image">
              <label for="gcb_reg_profile_image">
                <i class="fas fa-camera"></i> Profile Image
                <input type="file" name="gcb_reg_profile_image" id="gcb_reg_profile_image">
              </label>
            </div>
          </div>
          <?php wp_nonce_field( 'gcb_nonce', 'gcb_reg_nonce' ) ?>
          <input class="button" type="submit" name="gcb_registration" value="Register" />
        </form>
      </div>
    </div>
  </div>
</div>