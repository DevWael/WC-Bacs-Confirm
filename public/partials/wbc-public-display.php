<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/devwael
 * @since      1.0.0
 *
 * @package    Wbc
 * @subpackage Wbc/public/partials
 */

$image = get_post_meta( $order_id, 'wbc_bank_confirmation', true );
if ( $image ) {
	return null;
}
?>
<style>
    .wbc_form {
        width: 500px;
        max-width: 100%;
        margin: 0 auto;
        text-align: center;
    }

    .image_preview {
        width: 500px;
        max-width: 100%;
        height: 250px;
        border: 3px dashed #eee;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image_preview img {
        width: 100%;
        height: auto;
        max-height: 100%;
        display: none;
    }
</style>
<form class="wbc_form" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ) ?>" method="POST"
      enctype="multipart/form-data">
    <h1>
		<?php esc_html_e( 'Upload payment confirmation image', 'wbc' ); ?>
    </h1>
    <input type="hidden" name="action" value="wbc_bacs_confirm">
	<?php wp_nonce_field() ?>
    <input type="hidden" name="order_id" id="wbc_order_id" value="<?php echo esc_attr( $order_id ); ?>">
    <input type="file" class="wbc_image" name="wbc_image" value="" accept="image/*">
    <div class="image_preview">
        <img src="" class="img_preview__image" alt="Preview">
        <span class="img_preview__default_text"><?php esc_html_e( 'Image Preview', 'wbc' ); ?></span>
    </div>
    <br>
    <button class="go_now button alt" type="submit"><?php esc_html_e( 'Send', 'wbc' ); ?></button>
</form>

<script>
    (function ($) {
        let input_file = $('.wbc_image'),
            preview_image = $('.img_preview__image'),
            preview_text = $('.img_preview__default_text');

        input_file.on('change', function (e) {
            let f = this.files[0];

            //here I CHECK if the FILE SIZE is bigger than 2 MB (numbers below are in bytes)
            if (f.size > 2097152 || f.fileSize > 2097152) {
                //show an alert to the user
                alert(wbc_object.max_file_size_msg);
                //reset file upload control
                this.value = null;
            }

            let file = e.target.files[0];
            if (file) {
                let reader = new FileReader();
                preview_text.hide();
                preview_image.show();
                reader.onload = function (event) {
                    preview_image.attr("src", event.target.result)
                };
                reader.readAsDataURL(file);
            } else {
                preview_image.attr('src', '');
                preview_image.hide();
                preview_text.show();
            }
        });


        $('.wbc_form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: wbc_object.ajax_url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // console.log(response);
                    if (response.success) {
                        $('.wbc_form').html(wbc_object.request_received_msg);
                    } else {
                        //show the toastr js message
                        // toastr.error(response.data.msg);
                    }
                }
            });
        });

    })(jQuery);
</script>