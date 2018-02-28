<div class="row" >
  <div class="col-md-12">
    <h1 class="page_title"><?php echo hm_lang('main_setting'); ?></h1>
  </div>
  <form autocomplete="off" action="" method="post">
    <div class="col-md-6">
      <p class="page_action"><?php echo hm_lang('user_interface'); ?></p>
      <div class="row admin_mainbar_box">
        <div class="list-form-input">
          <?php
          /** Tên website */
          $args = array(
            'nice_name'=>hm_lang('site_name'),
            'name'=>'website_name',
            'input_type'=>'text',
            'required'=>TRUE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'website_name','default_value'=>'Một trang web sử dụng HoaMaiCMS') ),
          );
          build_input_form($args);

          /** Admin email */
          $args = array(
            'nice_name'=>hm_lang('admin_email'),
            'name'=>'admin_email',
            'input_type'=>'text',
            'required'=>TRUE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'admin_email','default_value'=>'admin@'.$_SERVER['SERVER_NAME']) ),
          );
          build_input_form($args);

          /** Favicon */
          $args = array(
            'nice_name'=>hm_lang('favicon'),
            'name'=>'favicon',
            'input_type'=>'text',
            'imageonly'=>TRUE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'favicon') ),
          );
          media_file_input($args);

          /** Số bài trên 1 trang */
          $args = array(
            'nice_name'=>hm_lang('number_of_posts_per_page'),
            'name'=>'post_per_page',
            'input_type'=>'number',
            'required'=>FALSE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'post_per_page','default_value'=>'12') ),
          );
          build_input_form($args);

          /** Kiểu sắp xếp */
          $args = array(
            'nice_name'=>hm_lang('type_of_arrangement'),
            'name'=>'type_of_arrangement',
            'input_type'=>'select',
            'input_option'=>array(
              array('value'=>'number_order','label'=>hm_lang('numerical_order')),
              array('value'=>'public_time','label'=>hm_lang('public_time')),
              array('value'=>'update_time','label'=>hm_lang('update_time')),
            ),
            'required'=>TRUE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'type_of_arrangement','default_value'=>'numerical_order') ),
          );
          build_input_form($args);

          /** Mã nhúng head */
          $args = array(
            'nice_name'=>hm_lang('head_script'),
            'name'=>'head_script',
            'input_type'=>'textarea',
            'required'=>FALSE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'head_script','default_value'=>'') ),
          );
          build_input_form($args);

          /** Mã nhúng footer */
          $args = array(
            'nice_name'=>hm_lang('body_script'),
            'name'=>'footer_script',
            'input_type'=>'textarea',
            'required'=>FALSE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'footer_script','default_value'=>'') ),
          );
          build_input_form($args);
          ?>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <p class="page_action"><?php echo hm_lang('email_and_smtp'); ?></p>
      <div class="row admin_mainbar_box">
        <div class="list-form-input">
          <?php
          /** Cấu hình SMTP */
          $args = array(
            'nice_name'=>hm_lang('email_sent'),
            'name'=>'from_email',
            'input_type'=>'text',
            'required'=>TRUE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'from_email','default_value'=>'') ),
          );
          build_input_form($args);

          $args = array(
            'nice_name'=>hm_lang('sender'),
            'name'=>'from_name',
            'input_type'=>'text',
            'required'=>FALSE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'from_name','default_value'=>'') ),
          );
          build_input_form($args);

          $args = array(
            'nice_name'=>hm_lang('method'),
            'name'=>'email_protocol',
            'input_type'=>'select',
            'input_option'=>array(
              array('value'=>'mail','label'=>'Mail'),
              array('value'=>'smtp','label'=>'SMTP'),
            ),
            'required'=>TRUE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'email_protocol','default_value'=>'') ),
          );
          build_input_form($args);

          $args = array(
            'nice_name'=>hm_lang('smtp_server'),
            'name'=>'smtp_host',
            'input_type'=>'text',
            'required'=>FALSE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'smtp_host','default_value'=>'') ),
          );
          build_input_form($args);

          $args = array(
            'nice_name'=>hm_lang('smtp_port'),
            'name'=>'smtp_port',
            'input_type'=>'number',
            'required'=>FALSE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'smtp_port','default_value'=>'') ),
          );
          build_input_form($args);

          $args = array(
            'nice_name'=>hm_lang('smtp_secure'),
            'name'=>'smtp_secure',
            'input_type'=>'text',
            'required'=>FALSE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'smtp_secure','default_value'=>'') ),
          );
          build_input_form($args);

          $args = array(
            'nice_name'=>hm_lang('smtp_account'),
            'name'=>'smtp_user',
            'input_type'=>'text',
            'required'=>FALSE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'smtp_user','default_value'=>'') ),
          );
          build_input_form($args);

          $args = array(
            'nice_name'=>hm_lang('smtp_password'),
            'name'=>'smtp_pass',
            'input_type'=>'text',
            'required'=>FALSE,
            'default_value'=>get_option( array('section'=>'system_setting','key'=>'smtp_pass','default_value'=>'') ),
          );
          build_input_form($args);
          ?>
        </div>
      </div>
    </div>
  <div class="col-md-6">
      <p class="page_action"><?php echo hm_lang('media_setting'); ?></p>
      <div class="row admin_mainbar_box">
        <div class="list-form-input">
      <?php
        $args = array(
        'nice_name'=>hm_lang('image_quality_after_cropping'),
        'name'=>'cropping_quality',
        'input_type'=>'number',
        'required'=>FALSE,
        'default_value'=>get_option( array('section'=>'system_setting','key'=>'cropping_quality','default_value'=>'80') ),
        );
        build_input_form($args);
      ?>
    </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <button name="save_system_setting" type="submit" class="btn btn-primary"><?php echo hm_lang('save'); ?></button>
      </div>
    </div>
  </form>
</div>
