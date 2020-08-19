<?php

namespace superwoo_coupon\superwoo_coupon_Coupon\Admin;

/**
 * Create WooCoupon Post Type
 */
class superwoo_coupon_Coupon
{

	function __construct()
	{
		add_action('init', [$this, 'superwoo_coupon_post_type'], 0);
		add_action('add_meta_boxes', array($this, "superwoo_coupon_metaboxes"));
		add_action('admin_enqueue_scripts', [$this, 'superwoo_coupon_enqueue_scripts']);
		add_action('save_post', [$this, 'superwoo_coupon_save_meta_post']);
		add_filter('post_row_actions', [$this, 'superwoo_coupon_post_row_actions'], 10, 2);
		add_filter('manage_woocoupon_posts_columns', [$this, 'superwoo_coupon_custom_columns']);
		add_action('manage_woocoupon_posts_custom_column', [$this, 'superwoo_coupon_custom_columns_data'], 10, 2);
	}

	public function superwoo_coupon_custom_columns($columns)
	{
		$columns['superwoo_coupon_type'] = __('Type', 'superwoo_coupon');
		$new = array();
		$superwoo_coupon_type = $columns['superwoo_coupon_type'];
		unset($columns['superwoo_coupon_type']);

		foreach ($columns as $key => $value) {
			if ($key == 'date') {
				$new['superwoo_coupon_type'] = $superwoo_coupon_type;
			}
			$new[$key] = $value;
		}

		return $new;
	}

	public function superwoo_coupon_custom_columns_data($column, $post_id)
	{
		if ($column == "superwoo_coupon_type") {
			$superwoo_couponMain = get_post_meta($post_id, "superwoo_coupon_coupon_main", true);
			if($superwoo_couponMain){ 
				switch ( $superwoo_couponMain["type"]) {
					case 'product':
						echo "<pre class='superwoo_coupon_pre_column'>Product Adjustment</pre>";
						break;
					case 'cart':
						echo "<pre class='superwoo_coupon_pre_column'>Cart Adjustment</pre>";
						break;
					case 'bulk':
						echo "<pre class='superwoo_coupon_pre_column'>Bulk Discount</pre>";
						break;

					default:
						break;
				}
			}
		}
	}

	public function superwoo_coupon_post_row_actions($unset_actions, $post)
	{
		global $current_screen;
		if ($current_screen->post_type != 'woocoupon')
			return $unset_actions;
		unset($unset_actions['inline hide-if-no-js']);
		return $unset_actions;
	}

	public function superwoo_coupon_enqueue_scripts()
	{
		wp_enqueue_script("superwoo_coupon_app");
		wp_enqueue_style("superwoo_coupon_app_css");
		wp_localize_script(
			'superwoo_coupon_app',
			'superwoo_coupon_helper_obj',
			array('ajax_url' => admin_url('admin-ajax.php'))
		);
		wp_localize_script(
			'superwoo_coupon_app',
			'superwoo_coupon_post',
			array('id' => get_the_ID())
		);
	}

	/**
	 * Register Custom Post Type
	 *
	 * @uses register_post_type()
	 **/
	public function superwoo_coupon_post_type()
	{
		$labels = array(
			'name'                  => _x('Woo Coupon\'s', 'Post Type General Name', 'superwoo_coupon'),
			'singular_name'         => _x('Woo Coupon', 'Post Type Singular Name', 'superwoo_coupon'),
			'menu_name'             => __('Woo Coupon\'s', 'superwoo_coupon'),
			'name_admin_bar'        => __('Woo Coupon\'s', 'superwoo_coupon'),
			'archives'              => __('Item Archives', 'superwoo_coupon'),
			'attributes'            => __('Item Attributes', 'superwoo_coupon'),
			'parent_item_colon'     => __('Parent Coupon:', 'superwoo_coupon'),
			'all_items'             => __('Coupons', 'superwoo_coupon'),
			'add_new_item'          => __('Add New Coupon', 'superwoo_coupon'),
			'add_new'               => __('Add New', 'superwoo_coupon'),
			'new_item'              => __('New Coupon', 'superwoo_coupon'),
			'edit_item'             => __('Edit Coupon', 'superwoo_coupon'),
			'update_item'           => __('Update Coupon', 'superwoo_coupon'),
			'view_item'             => __('View Coupon', 'superwoo_coupon'),
			'view_items'            => __('View Coupons', 'superwoo_coupon'),
			'search_items'          => __('Search Coupon', 'superwoo_coupon'),
			'not_found'             => __('Not found', 'superwoo_coupon'),
			'not_found_in_trash'    => __('Not found in Trash', 'superwoo_coupon'),
			'featured_image'        => __('Featured Image', 'superwoo_coupon'),
			'set_featured_image'    => __('Set featured image', 'superwoo_coupon'),
			'remove_featured_image' => __('Remove featured image', 'superwoo_coupon'),
			'use_featured_image'    => __('Use as featured image', 'superwoo_coupon'),
			'insert_into_item'      => __('Insert into item', 'superwoo_coupon'),
			'uploaded_to_this_item' => __('Uploaded to this item', 'superwoo_coupon'),
			'items_list'            => __('Items list', 'superwoo_coupon'),
			'items_list_navigation' => __('Items list navigation', 'superwoo_coupon'),
			'filter_items_list'     => __('Filter items list', 'superwoo_coupon'),
		);
		$args = array(
			'label'                 => __('Woo Coupon', 'superwoo_coupon'),
			'description'           => __('Advanced Coupon Maker By superwoo_coupon', 'superwoo_coupon'),
			'labels'                => $labels,
			'supports'              => array('title'),
			'taxonomies'            => array('title'),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => "superwoo-addons",
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
	public function superwoo_coupon_metaboxes()
	{
		// superwoo_coupon Discount Type
		add_meta_box(
			'superwoo_coupon_type_box',
			'Coupon Type',
			[$this, 'superwoo_coupon_type_screen'],
			'woocoupon',
			'normal',
			'default'
		);

		// superwoo_coupon Filter
		add_meta_box(
			'superwoo_coupon_filter_box',
			'Coupon Filters',
			[$this, 'superwoo_coupon_filter_screen'],
			'woocoupon',
			'normal',
			'default'
		);

		// superwoo_coupon Discount
		add_meta_box(
			'superwoo_coupon_discount_box',
			'Coupon Discounts',
			[$this, 'superwoo_coupon_discount_screen'],
			'woocoupon',
			'normal',
			'default'
		);

		// superwoo_coupon Rules
		add_meta_box(
			'superwoo_coupon_rules_box',
			'Coupon Rules (optional)',
			[$this, 'superwoo_coupon_rules_screen'],
			'woocoupon',
			'normal',
			'default'
		);
	}

	/**
	 * Screen of Type Box
	 **/
	public function superwoo_coupon_type_screen()
	{
		$nonce = wp_create_nonce('superwoo_coupon_without_ajax');
?>
		<supertype :nonce='<?php echo json_encode($nonce); ?>' />
	<?php
	}

	/**
	 * Screen of Filter Box
	 **/
	public function superwoo_coupon_filter_screen()
	{
		$nonce = wp_create_nonce('superwoo_coupon_with_ajax');
	?>
		<superfilter :nonce='<?php echo json_encode($nonce); ?>' />
	<?php
	}

	/**
	 * Screen of Discount Box
	 */
	public function superwoo_coupon_discount_screen()
	{
	?>
		<superdiscount />
	<?php
	}

	/**
	 * Screen of Rules Box
	 */
	public function superwoo_coupon_rules_screen()
	{
	?>
		<superrules />
<?php
	}

	/**
	 * save post meta
	 **/
	public function superwoo_coupon_save_meta_post($post_id)
	{
		if (!isset($_POST["superwoo_coupon_coupon_type"])) {
			return;
		}

		if (!wp_verify_nonce($_POST["superwoo_coupon_main_nonce"], "superwoo_coupon_without_ajax")) {
			wp_die(__('Sorry !! You cannot permit to access.', 'superwoo_coupon'));
		}

		$type = $_POST["superwoo_coupon_coupon_type"];

		if (isset($_POST["superwoo_coupon_discount_label"])) {
			$discount_label = $_POST["superwoo_coupon_discount_label"];
		} else {
			$discount_label = null;
		}

		$main = [
			"type" => $type,
			"label" => $discount_label
		];

		$discount_type = $_POST["superwoo_coupon_discount_type"];
		$discount_value = $_POST["superwoo_coupon_discount_value"] ? $_POST["superwoo_coupon_discount_value"] : 0;

		if (isset($_POST["discountLength"]) && $type == "bulk") {
			$discountLength = $_POST["discountLength"];
			$superwoo_coupon_discount = [];
			for ($i = 0; $i < $discountLength; $i++) {
				array_push($superwoo_coupon_discount, [
					"min" => $_POST["superwoo_coupon_discount_min_" . $i],
					"max" => $_POST["superwoo_coupon_discount_max_" . $i],
					"type" => $_POST["superwoo_coupon_discount_type_" . $i],
					"value" => $_POST["superwoo_coupon_discount_value_" . $i] ? $_POST["superwoo_coupon_discount_value_" . $i] : 0
				]);
			}
		} else {
			$superwoo_coupon_discount = [
				"type" => $discount_type,
				"value" => $discount_value
			];
		}

		$rulesLength = $_POST["rulesLength"];
		$superwoo_coupon_rules = [];
		if ($rulesLength == 0) {
			$superwoo_coupon_rules = null;
		} else {
			for ($i = 0; $i < $rulesLength; $i++) {
				array_push($superwoo_coupon_rules, [
					"type" => $_POST["superwoo_coupon_rule_type_" . $i],
					"operator" => $_POST["superwoo_coupon_rule_operator_" . $i],
					"item_count" => $_POST["superwoo_coupon_rule_item_" . $i],
					"calculate" => $_POST["superwoo_coupon_rule_calculate_" . $i]
				]);
			}
		}

		if ($type == "product") {
			$superwoo_coupon_rules = null;
		}

		$rules = [
			"relation" => $_POST["superwoo_coupon_rule_relation"] ? $_POST["superwoo_coupon_rule_relation"] : "match_all",
			"rules" => $superwoo_coupon_rules
		];

		$filters = get_post_meta($post_id, "superwoo_coupon_filters", true);

		if (!$filters) {
			$superwoo_coupon_filters = [[
				"type" => "all_products",
				"lists" => "inList",
				"items" => []
			]];
			update_post_meta($post_id, "superwoo_coupon_filters", $superwoo_coupon_filters);
		}

		update_post_meta($post_id, "superwoo_coupon_coupon_main", $main);
		update_post_meta($post_id, "superwoo_coupon_coupon_discounts", $superwoo_coupon_discount);
		update_post_meta($post_id, "superwoo_coupon_coupon_rules", $rules);
	}
}
