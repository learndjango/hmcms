<!-- custom js page -->
<?php
hm_admin_js('js/user.js');
hm_admin_css('css/user.css');
?>
<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo hm_lang('account'); ?></h1>
	</div>
	<form action="?run=user_ajax.php&action=edit&id=<?php echo hm_get('id'); ?>" method="post" class="ajaxForm ajaxFormuserAdd">
		<div class="row">
			<div class="col-md-6 admin_user">
				<p class="page_action"><?php echo hm_lang('account_information'); ?></p>
				<div class="row admin_user_box">
					<div class="list-form-input">
						<?php
						$args=array(
									'input_type'=>'text',
									'name'=>'user_login',
									'nice_name'=>hm_lang('user_name'),
									'description'=>hm_lang('account_name_is_used_for_login'),
									'required'=>TRUE,
									'default_value'=>$args_use->user_login,
									);
						build_input_form($args);


						$args=array(
									'input_type'=>'password',
									'name'=>'password',
									'nice_name'=>hm_lang('password'),
									'description'=>hm_lang('set_a_complex_password_to_protect_your_account'),
									'required'=>FALSE,
									'placeholder'=>hm_lang('leave_blank_if_you_do_not_need_to_change_your_password'),
									);
						build_input_form($args);

						$args=array(
									'input_type'=>'password',
									'name'=>'password2',
									'nice_name'=>hm_lang('retype_the_password_again'),
									'description'=>hm_lang('retype_the_password_again_coincide_with_the_password_entered_above'),
									'required'=>FALSE,
									'placeholder'=>hm_lang('leave_blank_if_you_do_not_need_to_change_your_password'),
									);
						build_input_form($args);

						$args=array(
									'input_type'=>'text',
									'name'=>'nicename',
									'nice_name'=>hm_lang('display_name'),
									'description'=>hm_lang('the_name_represents_you_when_displayed_on_the_website'),
									'required'=>TRUE,
									'default_value'=>$args_use->user_nicename,
									);
						build_input_form($args);

						$args=array(
									'input_type'=>'email',
									'name'=>'user_email',
									'nice_name'=>hm_lang('email'),
									'description'=>hm_lang('email_will_be_used_to_retrieve_the_password'),
									'required'=>TRUE,
									'default_value'=>$args_use->user_email,
									);
						build_input_form($args);
						
						if ( in_array($_SESSION['admin_user']['user_role'], array( 1, 2 )) ) {
						$args=array(
									'input_type'=>'select',
									'name'=>'userrole',
									'nice_name'=>hm_lang('user_role'),
									'required'=>TRUE,
									'input_option'=>array(
														array('value'=>1,'label'=>hm_lang('administrator')),
														array('value'=>2,'label'=>hm_lang('webmaster')),
														array('value'=>3,'label'=>hm_lang('editor')),
														array('value'=>4,'label'=>hm_lang('member')),
														array('value'=>5,'label'=>hm_lang('banned_account')),
														),
									'default_value'=>$args_use->user_role,
									);
						build_input_form($args);
						}
						
						?>
					</div>
				</div>
			</div>

			<div class="col-md-6 admin_user">
				<p class="page_action"><?php echo hm_lang('personal_information'); ?></p>
				<div class="row admin_user_box">
					<?php user_field( $args_use->id ); ?>
				</div>
			</div>
		</div>
		<?php
		if ( in_array($_SESSION['admin_user']['user_role'], array( 1, 2 )) ) {
		?>
		<div class="row">
			<div class="col-md-6 admin_user">
				<p class="page_action"><?php echo hm_lang('content_access'); ?></p>
				<div class="row admin_user_box">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo hm_lang('name_of_content_type'); ?></th>
								<th><?php echo hm_lang('add'); ?></th>
								<th><?php echo hm_lang('edit'); ?></th>
								<th><?php echo hm_lang('delete'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($hmcontent->hmcontent as $content){
								echo '<tr>';
									echo '<td>' . $content['content_name'] . '</td>';
									echo '<td>';
										$op_name = 'content_access[' . $content['content_key'] . '][add]';
										$args = array(
											'name'=>$op_name,
											'handle'=>false,
											'wrapper'=>false,
											'input_type'=>'select',
											'class'=>'select-role select-role-'.$content_access[$content['content_key']]['add'],
											'input_option'=>array(
																	array('value'=>'denied','label'=>hm_lang('denied')),
																	array('value'=>'allow','label'=>hm_lang('allow')),
																),
											'default_value'=>$content_access[$content['content_key']]['add'],
										);
										build_input_form($args);
									echo '</td>';
									echo '<td>';
										$op_name = 'content_access[' . $content['content_key'] . '][edit]';
										$args = array(
											'name'=>$op_name,
											'handle'=>false,
											'wrapper'=>false,
											'input_type'=>'select',
											'class'=>'select-role select-role-'.$content_access[$content['content_key']]['edit'],
											'input_option'=>array(
																	array('value'=>'denied','label'=>hm_lang('denied')),
																	array('value'=>'allow','label'=>hm_lang('allow')),
																	array('value'=>'owner_only','label'=>hm_lang('owner_only')),
																),
											'default_value'=>$content_access[$content['content_key']]['edit'],
										);
										build_input_form($args);
									echo '</td>';
									echo '<td>';
										$op_name = 'content_access[' . $content['content_key'] . '][delete]';
										$args = array(
											'name'=>$op_name,
											'handle'=>false,
											'wrapper'=>false,
											'input_type'=>'select',
											'class'=>'select-role select-role-'.$content_access[$content['content_key']]['delete'],
											'input_option'=>array(
																	array('value'=>'denied','label'=>hm_lang('denied')),
																	array('value'=>'allow','label'=>hm_lang('allow')),
																	array('value'=>'owner_only','label'=>hm_lang('owner_only')),
																),
											'default_value'=>$content_access[$content['content_key']]['delete'],
										);
										build_input_form($args);
									echo '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>

			<div class="col-md-6 admin_user">
				<p class="page_action"><?php echo hm_lang('taxonomy_access'); ?></p>
				<div class="row admin_user_box">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo hm_lang('name_of_content_type'); ?></th>
								<th><?php echo hm_lang('add'); ?></th>
								<th><?php echo hm_lang('edit'); ?></th>
								<th><?php echo hm_lang('delete'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							global $hmtaxonomy;
							foreach($hmtaxonomy->hmtaxonomy as $taxonomy){
								echo '<tr>';
									echo '<td>' . $taxonomy['taxonomy_name'] . '</td>';
									echo '<td>';
										$op_name = 'taxonomy_access[' . $taxonomy['taxonomy_key'] . '][add]';
										$args = array(
											'name'=>$op_name,
											'handle'=>false,
											'wrapper'=>false,
											'input_type'=>'select',
											'class'=>'select-role select-role-'.$taxonomy_access[$taxonomy['taxonomy_key']]['add'],
											'input_option'=>array(
																	array('value'=>'denied','label'=>hm_lang('denied')),
																	array('value'=>'allow','label'=>hm_lang('allow')),
																),
											'default_value'=>$taxonomy_access[$taxonomy['taxonomy_key']]['add'],
										);
										build_input_form($args);
									echo '</td>';
									echo '<td>';
										$op_name = 'taxonomy_access[' . $taxonomy['taxonomy_key'] . '][edit]';
										$args = array(
											'name'=>$op_name,
											'handle'=>false,
											'wrapper'=>false,
											'input_type'=>'select',
											'class'=>'select-role select-role-'.$taxonomy_access[$taxonomy['taxonomy_key']]['edit'],
											'input_option'=>array(
																	array('value'=>'denied','label'=>hm_lang('denied')),
																	array('value'=>'allow','label'=>hm_lang('allow')),
																	array('value'=>'owner_only','label'=>hm_lang('owner_only')),
																),
											'default_value'=>$taxonomy_access[$taxonomy['taxonomy_key']]['edit'],
										);
										build_input_form($args);
									echo '</td>';
									echo '<td>';
										$op_name = 'taxonomy_access[' . $taxonomy['taxonomy_key'] . '][delete]';
										$args = array(
											'name'=>$op_name,
											'handle'=>false,
											'wrapper'=>false,
											'input_type'=>'select',
											'class'=>'select-role select-role-'.$taxonomy_access[$taxonomy['taxonomy_key']]['delete'],
											'input_option'=>array(
																	array('value'=>'denied','label'=>hm_lang('denied')),
																	array('value'=>'allow','label'=>hm_lang('allow')),
																	array('value'=>'owner_only','label'=>hm_lang('owner_only')),
																),
											'default_value'=>$taxonomy_access[$taxonomy['taxonomy_key']]['delete'],
										);
										build_input_form($args);
									echo '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 admin_user">
				<p class="page_action"><?php echo hm_lang('media_access'); ?></p>
				<div class="row admin_user_box">
					<ol class="media_access_tree_sub_group media_tree_sub_group_of_0">
						<?php
						media_access_tree(0,$media_access);
						?>
					</ol>
				</div>
			</div>
		</div>
		
		<?php
		}
		?>


		<div class="col-md-12 admin_user">
			<div class="row admin_user_box">
				<div class="row add_user_noti"></div>
				<div class="form-group">
					<button name="submit" type="submit" class="btn btn-primary"><?php echo hm_lang('update'); ?></button>
				</div>
			</div>
		</div>

	</form>

</div>
