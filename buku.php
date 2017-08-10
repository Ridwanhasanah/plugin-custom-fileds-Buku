<?php
/*
  Plugin Name: Buku (Custome Fields)
  Plugin URI: https://www.facebook.com/ridwan.hasanah3
  Description: Plugin Custome Fields
  Version: 1.0
  Author: Ridwan Hasanah
  Author URI: https://www.facebook.com/ridwan.hasanah3
*/


  add_action('add_meta_boxes','rh_meta_box_add' );

  function rh_meta_box_add(){
  	add_meta_box(
  		'rh_custom_fields_buku', //Meta box ID
  		'Data Buku', //Title of the meta box.
  		'rh_custom_fields_buku_form',//fucntion utk menampilkan form
  		'post',   //screen
  		'normal', //The context within the screen where the boxes should display. 
  		'high'); //The priority within the context where the boxes should show ('high', 'low').
  }


  function rh_custom_fields_buku_form(){
  	$data = get_post_custom(get_the_ID());

  	if (!is_null($data['rh-custom-fields-buku'])) {
  		extract(unserialize($data['rh-custom-fields-buku'][0]));
  	}
  	wp_nonce_field('rh_custom_fields_nonce','rh_buku_nonce' );
  	?>
  	<table>
  		<tr>
  			<td><label for="kode">Kode</label></td>
  			<td><input type="text" name="kode" id="kode" value="<?php echo $kode; ?>"/></td>
  		</tr>
  		<tr>
  			<td><label for="judul">Judul</label></td>
  			<td><input type="text" name="judul" id="judul" value="<?php echo $judul; ?>"/></td>
  		</tr>
  		<tr>
  			<td><label for="gambar">URL Gambar</label></td>
  			<td><input type="text" name="gambar" id="gambar" value="<?php echo $gambar; ?>"/></td>
  		</tr>
  		<tr>
  			<td><label for="format">Format</label></td>
  			<td><select name="format" id="format">
  				<option value="Hard Cover" <?php if ($format == 'Hard Cover') echo "selected";?>>Hard Cover</option>
  				<option value="Soft Cover" <?php if ($format == 'Soft Cover') echo "selected";?>>Soft Cover</option>
  				<option value="Kindle" <?php if ($format == 'Kindle') echo "selected";?>>Kindle</option>
  			</select>
  			</td>
  		</tr>
  		<tr>
  			<td></td>
  			<td>
  				<input type="checkbox" name="tersedia" id="tersedia" value="Tersedia" <?php if ($tersedia == 'Tersedia')echo 'checked';?>/>
  				<label for="tersedia">Tersedia</label>
  			</td>
  		</tr>
  	</table>
  	<?php

  }

add_action(
	'save_post', //save_post adalah proses penyimpanan post
	'rh_custom_fields_buku_simpan' ); //function penyimpanan

function rh_custom_fields_buku_simpan(){
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post' ) ) return;
	if (!isset($_POST['rh_buku_nonce']) || !wp_verify_nonce($_POST['rh_buku_nonce'],'rh_custom_fields_nonce' ) ) return;

	$custome_fields['kode']     = $_POST['kode'];
	$custome_fields['judul']    = $_POST['judul'];
	$custome_fields['gambar']   = $_POST['gambar'];
	$custome_fields['format']   = $_POST['format']; 
	$custome_fields['tersedia'] = $_POST['tersedia'];

	update_post_meta(get_the_ID(),'rh-custom-fields-buku', $custome_fields);
}

add_filter('the_content', 'rh_custom_fields_buku_tampil' );

function rh_custom_fields_buku_tampil($content){

	if (!is_singular('post')) {
		return $content;
	}

	$data = get_post_custom(get_the_ID() );

	if (!empty($data['rh-custom-fields-buku'][0])) {
		extract(unserialize($data['rh-custom-fields-buku'][0]));

		if (!empty($judul)) {
			$data_buku = '<div><div style="float: left; width: 160px; margin: 5px;">';
			$data_buku .= '<img src="'.$gambar.'"></div>';
			$data_buku .= '<strong>'.$judul.'</strong>';
			$data_buku .= '<br>ISBN: '.$kode;
			$data_buku .= '<br>FOrmat: '.$format;
			$data_buku .= '<br>Ketersediaan: '.$tersedia;
			$data_buku .= '<div style="clear: both;"></div>';

			return $data_buku . $content;
		}else{
			return $content;
		}
	}else{
		return $content;
	}
}
?>