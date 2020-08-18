<template>
  <div>
    <div v-if="loading" class="spinner is-active superwoo_coupon_spinner"></div>
    <div v-else>
      <div class="superwoo_coupon-flex superwoo_coupon-filter" v-if="$root.superwoo_coupon_form.type !== 'bulk'">
        <div class="superwoo_coupon-col-3">
          <div class="superwoo_coupon-form">
            <label for="superwoo_coupon_discount_type">
              <strong>Discount Type</strong>
            </label>
            <select
              id="superwoo_coupon_discount_type"
              name="superwoo_coupon_discount_type"
              v-model="discounts.type"
            >
              <option value="percentage">Percentage discount</option>
              <option value="fixed">Fixed discount</option>
            </select>
          </div>
        </div>
        <div class="superwoo_coupon-filter-list">
          <div class="superwoo_coupon-form">
            <label for="superwoo_coupon_discount_value">
              <strong>Value</strong>
            </label>
            <input
              type="text"
              id="superwoo_coupon_discount_value"
              name="superwoo_coupon_discount_value"
              placeholder="0.00"
              v-model="discounts.value"
            />
          </div>
        </div>
      </div>
      <div v-else>
        <div
          class="superwoo_coupon-flex superwoo_coupon-filter superwoo_coupon-bulk-discount"
          v-for="(superwoo_couponDiscount,index) in superwoo_couponDiscounts"
          :key="'superwoo_couponDiscount-'+index"
        >
          <input type="hidden" name="discountLength" :value="superwoo_couponDiscounts.length" />
          <div class="superwoo_coupon-bulk-list">
            <div class="superwoo_coupon-form">
              <label :for="'superwoo_coupon_discount_min_'+index">
                <strong>Min</strong>
              </label>
              <input
                type="text"
                :id="'superwoo_coupon_discount_min_'+index"
                v-model="superwoo_couponDiscount.min"
                :name="'superwoo_coupon_discount_min_'+index"
                placeholder="Min"
              />
            </div>
          </div>
          <div class="superwoo_coupon-bulk-list">
            <div class="superwoo_coupon-form">
              <label :for="'superwoo_coupon_discount_max_'+index">
                <strong>Max</strong>
              </label>
              <input
                type="text"
                :id="'superwoo_coupon_discount_max_'+index"
                v-model="superwoo_couponDiscount.max"
                :name="'superwoo_coupon_discount_max_'+index"
                placeholder="Max"
              />
            </div>
          </div>
          <div class="superwoo_coupon-bulk-list">
            <div class="superwoo_coupon-form">
              <label :for="'superwoo_coupon_discount_type_'+index">
                <strong>Type</strong>
              </label>
              <select
                :id="'superwoo_coupon_discount_type_'+index"
                v-model="superwoo_couponDiscount.type"
                :name="'superwoo_coupon_discount_type_'+index"
              >
                <option value="percentage">Percentage discount</option>
                <option value="fixed">Fixed discount</option>
              </select>
            </div>
          </div>
          <div class="superwoo_coupon-bulk-list">
            <div class="superwoo_coupon-form">
              <label :for="'superwoo_coupon_discount_value_'+index">
                <strong>Value</strong>
              </label>
              <input
                type="text"
                :id="'superwoo_coupon_discount_value_'+index"
                v-model="superwoo_couponDiscount.value"
                :name="'superwoo_coupon_discount_value_'+index"
                placeholder="0.00"
              />
            </div>
          </div>
          <div class="superwoo_coupon-filter-close" v-if="superwoo_couponDiscounts.length > 1">
            <span @click="removeRange(index)" class="dashicons dashicons-no-alt"></span>
          </div>
        </div>
        <div class="superwoo_coupon_buttons">
          <button @click="AddRange" type="button" class="button-primary">Add Range</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "superwoo_coupondiscount",
  data() {
    return {
      loading: true,
      discounts: {
        type: "percentage",
        value: null,
      },
      superwoo_couponDiscounts: [
        {
          min: null,
          max: null,
          type: "percentage",
          value: null,
        },
      ],
    };
  },
  created() {
    this.getDiscounts();
  },
  methods: {
    AddRange() {
      this.superwoo_couponDiscounts.push({
        min: null,
        max: null,
        type: "percentage",
        value: null,
      });
    },
    removeRange(index) {
      this.superwoo_couponDiscounts.splice(index, 1);
    },
    getDiscounts() {
      this.loading = true;
      let formData = {
        action: "superwoo_coupon_get_discounts",
        post_id: superwoo_coupon_post.id,
      };
      let root = this;
      axios
        .post(superwoo_coupon_helper_obj.ajax_url, Qs.stringify(formData))
        .then((response) => {
          if (response.data != [] && response.data != "") {
            if (root.$root.superwoo_coupon_form.type === "bulk") {
              root.superwoo_couponDiscounts = response.data;
            } else {
              root.discounts = response.data;
            }
          }
          root.loading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getDiscounts();
  },
};
</script>
