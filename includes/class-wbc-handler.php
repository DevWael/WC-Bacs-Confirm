<?php


class Wbc_Handler {

	private function admin_email() {
		return get_bloginfo( 'admin_email' );
	}

	public function hide_bank_confirmations( $query ) {
		if ( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] == 'attachment' ) {
			//if in the admin panel
			if ( $query->is_admin ) {
				$meta_query = array(
					array(
						'key'     => 'wbc_bank_attachment',
						'compare' => 'NOT EXISTS',
					),
				);
				$query->set( 'meta_query', $meta_query );
			}

			return $query;
		}

		return $query;
	}

	public function service() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'] ) ) {
			wp_send_json_error( array(
				'code' => 1,
				'msg'  => esc_html__( 'Cheating!', 'wbc' )
			) );
		}


		if ( ! isset( $_POST['order_id'] ) || empty( $_POST['order_id'] ) ) {
			wp_send_json_error( array(
				'code' => 2,
				'msg'  => esc_html__( 'Order id is required', 'wbc' )
			) );
		}

		$user_id  = get_current_user_id();
		$order_id = sanitize_text_field( $_POST['order_id'] );
		$order    = wc_get_order( $order_id );

		if ( $user_id == $order->get_customer_id() ) {
			$upload_result = media_handle_upload( 'wbc_image', 0 );
			if ( is_wp_error( $upload_result ) ) {
				wp_send_json_error( array(
					'code' => 2,
					'msg'  => $upload_result->get_error_message()
				) );
			} else {
				update_post_meta( $upload_result, 'wbc_bank_attachment', 'yes' );
				update_post_meta( $order_id, 'wbc_bank_confirmation', $upload_result );
				$subject = __( 'Order Confirmation Received', 'wbc' );
				$message = __( 'Customer has uploaded the payment confirmation image on order no:', 'wbc' ) . ' #' . $order_id;
				$this->notify_admin( $subject, $message );
				wp_send_json_success( array(
					'code' => 1,
					'msg'  => esc_html__( 'Thank you, We have received your request', 'wbc' )
				) );
			}
		} else {
			if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'] ) ) {
				wp_send_json_error( array(
					'code' => 1,
					'msg'  => esc_html__( 'Cheating!', 'wbc' )
				) );
			}
		}
	}

	public function order_confirmation_meta_box() {
		global $post;
		if ( $post->post_type == 'shop_order' ) {
			$order = wc_get_order( $post->ID );
			if ( $order->get_payment_method() == 'bacs' ) {
				add_meta_box(
					'order_confirm',
					__( 'Order Confirmation', 'wbc' ),
					array( $this, 'order_confirmation_content' ),
					'shop_order'
				);
			}

		}

	}

	public function order_confirmation_content( $post ) {
		$image_id = get_post_meta( $post->ID, 'wbc_bank_confirmation', true );
		if ( $image_id ) {
			echo wp_get_attachment_image( $image_id, 'full', false, array( 'class' => 'wbc_confirm_image' ) );
		} else {
			esc_html_e( 'Customer didnt uploaded the payment confirmation image', 'wbc' );
		}
	}

	private function notify_admin( $subject, $message ) {
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html' . "\r\n";

		$email = wp_mail( $this->admin_email(), $subject, $message, $headers );
	}
}
