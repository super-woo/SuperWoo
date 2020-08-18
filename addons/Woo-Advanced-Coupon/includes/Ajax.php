<?php

namespace superwoo_coupon\superwoo_coupon_Coupon;

/**
 * Ajax Handler
 */
class Ajax
{

	function __construct()
	{
		add_action('wp_ajax_superwoo_coupon_product_search', [$this, 'superwoo_coupon_product_search']);
		add_action('wp_ajax_superwoo_coupon_get_filters', [$this, 'superwoo_coupon_get_filters']);
		add_action('wp_ajax_superwoo_coupon_save_filters', [$this, 'superwoo_coupon_save_filters']);
		add_action('wp_ajax_superwoo_coupon_get_main', [$this, 'superwoo_coupon_get_main']);
		add_action('wp_ajax_superwoo_coupon_get_discounts', [$this, 'superwoo_coupon_get_discounts']);
		add_action('wp_ajax_superwoo_coupon_get_rules', [$this, 'superwoo_coupon_get_rules']);
		add_action('wp_ajax_superwoo_coupon_get_woocoupons', [$this, 'superwoo_coupon_get_woocoupons']);
		add_action('wp_ajax_superwoo_coupon_get_superwoo_coupon_panel', [$this, 'superwoo_coupon_get_superwoo_coupon_panel']);
	}

	public function superwoo_coupon_get_superwoo_coupon_panel()
	{
		$post_id = $_POST["post_id"];
		$post_meta = get_post_meta($post_id, "superwoo_coupon_coupon_panel", true);
		wp_send_json($post_meta);
	}

	public function superwoo_coupon_get_woocoupons()
	{
		$args = [
			"post_type" => "woocoupon",
			'post_status' => 'publish'
		];
		$posts = get_posts($args);
		$filter_Posts = [];
		foreach ($posts as $post) {
			array_push($filter_Posts, [
				"label" => $post->post_title,
				"value" => $post->ID
			]);
		}
		wp_send_json($filter_Posts);
	}

	public function superwoo_coupon_get_rules()
	{
		$post_id = $_POST["post_id"];
		$post_meta = get_post_meta($post_id, "superwoo_coupon_coupon_rules", true);
		wp_send_json($post_meta);
	}

	public function superwoo_coupon_get_discounts()
	{
		$post_id = $_POST["post_id"];
		$post_meta = get_post_meta($post_id, "superwoo_coupon_coupon_discounts", true);
		wp_send_json($post_meta);
	}

	public function superwoo_coupon_get_main()
	{
		$post_id = $_POST["post_id"];
		$post_meta = get_post_meta($post_id, "superwoo_coupon_coupon_main", true);
		$discount_type = [
			"product" => ["label" => "Product Adjustment", "has_label" => false],
			"cart" => ["label" => "Cart Adjustment", "has_label" => true],
			"bulk" => ["label" => "Bulk Discount", "has_label" => true]
		];
		$data = [
			"post_meta" => $post_meta,
			"discount_type" => apply_filters("superwoo_coupon_discount_type", $discount_type)
		];
		wp_send_json($data);
	}

	public function superwoo_coupon_product_search()
	{
		$args = [
			"post_type" => "product",
			'post_status' => 'publish',
			"s" => $_POST["queryData"]
		];
		$posts = get_posts($args);
		$filter_Posts = [];
		foreach ($posts as $post) {
			array_push($filter_Posts, [
				"label" => $post->post_title,
				"value" => $post->ID
			]);
		}

		if (isset($_POST["option"])) {
			foreach ($_POST["option"] as $option) {
				$filter_Posts = array_filter($filter_Posts, function ($post) use ($option) {
					return ($post["value"] != $option["value"]);
				});
			}
		}

		wp_send_json($filter_Posts);
	}

	public function superwoo_coupon_get_filters()
	{
		$post_id = $_POST["post_id"];
		$post_meta = get_post_meta($post_id, "superwoo_coupon_filters", true);
		$filters_data = [
			["label" => "All Products", "value" => "all_products", "has_item" => false, "items" => null],
			[
				"label" => "Products", "value" => "products", "has_item" => true,
				"items" => ["action" => "superwoo_coupon_product_search", "label" => "Select Products"]
			]
		];
		$send_data = [
			"post_meta" => $post_meta,
			"filters_data" => apply_filters("superwoo_coupon_filters", $filters_data)
		];
		wp_send_json($send_data);
	}

	public function superwoo_coupon_save_filters()
	{
		if (!wp_verify_nonce($_POST["superwoo_coupon_nonce"], "superwoo_coupon_with_ajax")) {
			wp_die(__('Sorry !! You cannot permit to access.', 'superwoo_coupon'));
		}
		$post_id = $_POST["post_id"];
		$superwoo_couponfilters = [];
		foreach ($_POST["superwoo_couponfilters"] as $superwoo_coupon_filter) {
			if (!isset($superwoo_coupon_filter["items"])) {
				$superwoo_coupon_filter["items"] = [];
			}
			array_push($superwoo_couponfilters, $superwoo_coupon_filter);
		}
		update_post_meta($post_id, "superwoo_coupon_filters", $superwoo_couponfilters);
		wp_send_json(["message" => "Updated SuccessFully", "status" => "success"]);
	}
}
