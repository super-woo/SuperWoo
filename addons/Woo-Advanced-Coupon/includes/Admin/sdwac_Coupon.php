<?php

namespace springdevs\WooAdvanceCoupon\Admin;

/**
 * Create WooCoupon Post Type
 */
class sdwac_Coupon
{

	function __construct()
	{
		add_action('init', [$this, 'sdwac_coupon_post_type'], 0);
		add_action('add_meta_boxes', array($this, "sdwac_coupon_metaboxes"));
		add_action('admin_enqueue_scripts', [$this, 'sdwac_coupon_enqueue_scripts']);
		add_action('save_post', [$this, 'sdwac_coupon_save_meta_post']);
		add_filter('post_row_actions', [$this, 'sdwac_coupon_post_row_actions'], 10, 2);
		add_filter('manage_woocoupon_posts_columns', [$this, 'sdwac_coupon_custom_columns']);
		add_action('manage_woocoupon_posts_custom_column', [$this, 'sdwac_coupon_custom_columns_data'], 10, 2);
	}

	public function sdwac_coupon_custom_columns($columns)
	{
		$columns['sdwac_coupon_type'] = __('Type', 'springdevs_wma');
		$new = array();
		$sdwac_coupon_type = $columns['sdwac_coupon_type'];
		unset($columns['sdwac_coupon_type']);

		foreach ($columns as $key => $value) {
			if ($key == 'date') {
				$new['sdwac_coupon_type'] = $sdwac_coupon_type;
			}
			$new[$key] = $value;
		}

		return $new;
	}

	public function sdwac_coupon_custom_columns_data($column, $post_id)
	{
		if ($column == "sdwac_coupon_type") {
			$sdwac_couponMain = get_post_meta($post_id, "sdwac_coupon_main", true);
			if ($sdwac_couponMain) {
				switch ($sdwac_couponMain["type"]) {
					case 'product':
						echo "<pre class='sdwac_coupon_pre_column'>Product Adjustment</pre>";
						break;
					case 'cart':
						echo "<pre class='sdwac_coupon_pre_column'>Cart Adjustment</pre>";
						break;
					case 'bulk':
						echo "<pre class='sdwac_coupon_pre_column'>Bulk Discount</pre>";
						break;

					default:
						break;
				}
			}
		}
	}

	public function sdwac_coupon_post_row_actions($unset_actions, $post)
	{
		global $current_screen;
		if ($current_screen->post_type != 'woocoupon')
			return $unset_actions;
		unset($unset_actions['inline hide-if-no-js']);
		return $unset_actions;
	}

	public function sdwac_coupon_enqueue_scripts()
	{
		wp_enqueue_script("sdwac_coupon_app");
		wp_enqueue_style("sdwac_coupon_app_css");
		wp_localize_script(
			'sdwac_coupon_app',
			'sdwac_coupon_helper_obj',
			array('ajax_url' => admin_url('admin-ajax.php'))
		);
		wp_localize_script(
			'sdwac_coupon_app',
			'sdwac_coupon_post',
			array('id' => get_the_ID())
		);
	}

	/**
	 * Register Custom Post Type
	 *
	 * @uses register_post_type()
	 **/
	public function sdwac_coupon_post_type()
	{
		$labels = array(
			'name'                  => _x('Woo Coupon\'s', 'Post Type General Name', 'springdevs_wma'),
			'singular_name'         => _x('Woo Coupon', 'Post Type Singular Name', 'springdevs_wma'),
			'menu_name'             => __('Woo Coupon\'s', 'springdevs_wma'),
			'name_admin_bar'        => __('Woo Coupon\'s', 'springdevs_wma'),
			'archives'              => __('Item Archives', 'springdevs_wma'),
			'attributes'            => __('Item Attributes', 'springdevs_wma'),
			'parent_item_colon'     => __('Parent Coupon:', 'springdevs_wma'),
			'all_items'             => __('Coupons', 'springdevs_wma'),
			'add_new_item'          => __('Add New Coupon', 'springdevs_wma'),
			'add_new'               => __('Add New', 'springdevs_wma'),
			'new_item'              => __('New Coupon', 'springdevs_wma'),
			'edit_item'             => __('Edit Coupon', 'springdevs_wma'),
			'update_item'           => __('Update Coupon', 'springdevs_wma'),
			'view_item'             => __('View Coupon', 'springdevs_wma'),
			'view_items'            => __('View Coupons', 'springdevs_wma'),
			'search_items'          => __('Search Coupon', 'springdevs_wma'),
			'not_found'             => __('Not found', 'springdevs_wma'),
			'not_found_in_trash'    => __('Not found in Trash', 'springdevs_wma'),
			'featured_image'        => __('Featured Image', 'springdevs_wma'),
			'set_featured_image'    => __('Set featured image', 'springdevs_wma'),
			'remove_featured_image' => __('Remove featured image', 'springdevs_wma'),
			'use_featured_image'    => __('Use as featured image', 'springdevs_wma'),
			'insert_into_item'      => __('Insert into item', 'springdevs_wma'),
			'uploaded_to_this_item' => __('Uploaded to this item', 'springdevs_wma'),
			'items_list'            => __('Items list', 'springdevs_wma'),
			'items_list_navigation' => __('Items list navigation', 'springdevs_wma'),
			'filter_items_list'     => __('Filter items list', 'springdevs_wma'),
		);
		$args = array(
			'label'                 => __('Woo Coupon', 'springdevs_wma'),
			'description'           => __('Advanced Coupon Maker By springdevs_wma', 'springdevs_wma'),
			'labels'                => $labels,
			'supports'              => array('title'),
			'taxonomies'            => array('title'),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => "springdevs-addons",
			'menu_position'         => 50,
			'menu_icon'             => 'dashicons-nametag',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => false,
			'rewrite'               => false,
			'capability_type'       => 'page',
		);
		register_post_type('woocoupon', $args);
	}

	/**
	 * create Meta Box
	 *
	 * @uses add_meta_box
	 **/
	public function sdwac_coupon_metaboxes()
	{
		// sdwac_coupon Discount Type
		add_meta_box(
			'sdwac_coupon_type_box',
			'Coupon Type',
			[$this, 'sdwac_coupon_type_screen'],
			'woocoupon',
			'normal',
			'default'
		);

		// sdwac_coupon Filter
		add_meta_box(
			'sdwac_coupon_filter_box',
			'Coupon Filters',
			[$this, 'sdwac_coupon_filter_screen'],
			'woocoupon',
			'normal',
			'default'
		);

		// sdwac_coupon Discount
		add_meta_box(
			'sdwac_coupon_discount_box',
			'Coupon Discounts',
			[$this, 'sdwac_coupon_discount_screen'],
			'woocoupon',
			'normal',
			'default'
		);

		// sdwac_coupon Rules
		add_meta_box(
			'sdwac_coupon_rules_box',
			'Coupon Rules (optional)',
			[$this, 'sdwac_coupon_rules_screen'],
			'woocoupon',
			'normal',
			'default'
		);
	}

	/**
	 * Screen of Type Box
	 **/
	public function sdwac_coupon_type_screen()
	{
		$nonce = wp_create_nonce('sdwac_coupon_without_ajax');
?>
		<supertype :nonce='<?php echo json_encode($nonce); ?>' />
	<?php
	}

	/**
	 * Screen of Filter Box
	 **/
	public function sdwac_coupon_filter_screen()
	{
		$nonce = wp_create_nonce('sdwac_coupon_with_ajax');
	?>
		<superfilter :nonce='<?php echo json_encode($nonce); ?>' />
	<?php
	}

	/**
	 * Screen of Discount Box
	 */
	public function sdwac_coupon_discount_screen()
	{
	?>
		<superdiscount />
	<?php
	}

	/**
	 * Screen of Rules Box
	 */
	public function sdwac_coupon_rules_screen()
	{
	?>
		<superrules />
<?php
	}

	/**
	 * save post meta
	 **/
	public function sdwac_coupon_save_meta_post($post_id)
	{
		if (!isset($_POST["sdwac_coupon_type"])) {
			return;
		}

		if (!wp_verify_nonce($_POST["sdwac_coupon_main_nonce"], "sdwac_coupon_without_ajax")) {
			wp_die(__('Sorry !! You cannot permit to access.', 'springdevs_wma'));
		}

		$type = $_POST["sdwac_coupon_type"];

		if (isset($_POST["sdwac_coupon_discount_label"])) {
			$discount_label = $_POST["sdwac_coupon_discount_label"];
		} else {
			$discount_label = null;
		}

		$main = [
			"type" => $type,
			"label" => $discount_label
		];

		$discount_type = $_POST["sdwac_coupon_discount_type"];
		$discount_value = $_POST["sdwac_coupon_discount_value"] ? $_POST["sdwac_coupon_discount_value"] : 0;

		if (isset($_POST["discountLength"]) && $type == "bulk") {
			$discountLength = $_POST["discountLength"];
			$sdwac_coupon_discount = [];
			for ($i = 0; $i < $discountLength; $i++) {
				array_push($sdwac_coupon_discount, [
					"min" => $_POST["sdwac_coupon_discount_min_" . $i],
					"max" => $_POST["sdwac_coupon_discount_max_" . $i],
					"type" => $_POST["sdwac_coupon_discount_type_" . $i],
					"value" => $_POST["sdwac_coupon_discount_value_" . $i] ? $_POST["sdwac_coupon_discount_value_" . $i] : 0
				]);
			}
		} else {
			$sdwac_coupon_discount = [
				"type" => $discount_type,
				"value" => $discount_value
			];
		}

		$rulesLength = $_POST["rulesLength"];
		$sdwac_coupon_rules = [];
		if ($rulesLength == 0) {
			$sdwac_coupon_rules = null;
		} else {
			for ($i = 0; $i < $rulesLength; $i++) {
				array_push($sdwac_coupon_rules, [
					"type" => $_POST["sdwac_coupon_rule_type_" . $i],
					"operator" => $_POST["sdwac_coupon_rule_operator_" . $i],
					"item_count" => $_POST["sdwac_coupon_rule_item_" . $i],
					"calculate" => $_POST["sdwac_coupon_rule_calculate_" . $i]
				]);
			}
		}

		if ($type == "product") {
			$sdwac_coupon_rules = null;
		}

		$rules = [
			"relation" => $_POST["sdwac_coupon_rule_relation"] ? $_POST["sdwac_coupon_rule_relation"] : "match_all",
			"rules" => $sdwac_coupon_rules
		];

		$filters = get_post_meta($post_id, "sdwac_coupon_filters", true);

		if (!$filters) {
			$sdwac_coupon_filters = [[
				"type" => "all_products",
				"lists" => "inList",
				"items" => []
			]];
			update_post_meta($post_id, "sdwac_coupon_filters", $sdwac_coupon_filters);
		}

		update_post_meta($post_id, "sdwac_coupon_main", $main);
		update_post_meta($post_id, "sdwac_coupon_discounts", $sdwac_coupon_discount);
		update_post_meta($post_id, "sdwac_coupon_rules", $rules);
	}
}
