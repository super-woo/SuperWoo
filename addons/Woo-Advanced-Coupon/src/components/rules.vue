<template>
  <div>
    <div v-if="loading" class="spinner is-active superwoo_coupon_spinner"></div>
    <div v-else>
      <div v-if="$root.superwoo_coupon_form.type != 'product'">
        <input type="hidden" name="rulesLength" :value="conditions.length" />
        <div class="superwoo_coupon-form">
          <label>
            <strong>Conditions Relationship</strong>
            <div class="superwoo_coupon-checkbox">
              <label>
                <input
                  name="superwoo_coupon_rule_relation"
                  type="radio"
                  value="match_all"
                  v-model="relation"
                /> Match All
              </label>
              <label>
                <input
                  name="superwoo_coupon_rule_relation"
                  type="radio"
                  value="match_any"
                  v-model="relation"
                /> Match Any
              </label>
            </div>
          </label>
        </div>
        <div
          class="superwoo_coupon-flex superwoo_coupon-filter superwoo_coupon-bulk-discount"
          v-for="(condition, index) in conditions"
          :key="'condition'+index"
        >
          <div class="superwoo_coupon-bulk-list">
            <div class="superwoo_coupon-form">
              <label :for="'superwoo_coupon_rule_type_'+index">
                <strong>Condition Type</strong>
              </label>
              <select
                :id="'superwoo_coupon_rule_type_'+index"
                :name="'superwoo_coupon_rule_type_'+index"
                v-model="condition.type"
              >
                <option
                  v-for="(type, index) in types"
                  :key="'type-'+index"
                  :value="type.value"
                >{{ type.label }}</option>
              </select>
            </div>
          </div>
          <div class="superwoo_coupon-bulk-list">
            <div class="superwoo_coupon-form">
              <label :for="'superwoo_coupon_rule_operator_'+index">
                <strong>count should be</strong>
              </label>
              <select
                :id="'superwoo_coupon_rule_operator_'+index"
                :name="'superwoo_coupon_rule_operator_'+index"
                v-model="condition.operator"
              >
                <option
                  v-for="(operator, index) in operators"
                  :key="'operator-'+index"
                  :value="operator.value"
                >{{ operator.label }}</option>
              </select>
            </div>
          </div>
          <div class="superwoo_coupon-bulk-list">
            <div class="superwoo_coupon-form">
              <label :for="'superwoo_coupon_rule_item_'+index">
                <strong>item count</strong>
              </label>
              <input
                type="number"
                :id="'superwoo_coupon_rule_item_'+index"
                :name="'superwoo_coupon_rule_item_'+index"
                placeholder="1"
                min="1"
                v-model="condition.item_count"
              />
            </div>
          </div>
          <div class="superwoo_coupon-bulk-list">
            <div class="superwoo_coupon-form">
              <label :for="'superwoo_coupon_rule_calculate_'+index">
                <strong>calculate item count</strong>
              </label>
              <select
                :id="'superwoo_coupon_rule_calculate_'+index"
                :name="'superwoo_coupon_rule_calculate_'+index"
                v-model="condition.calculate"
              >
                <option
                  v-for="(calculate, index) in calculates"
                  :key="'calculate-'+index"
                  :value="calculate.value"
                >{{ calculate.label }}</option>
              </select>
            </div>
          </div>
          <div class="superwoo_coupon-filter-close">
            <span @click="removeRule(index)" class="dashicons dashicons-no-alt"></span>
          </div>
        </div>
        <div class="superwoo_coupon_buttons">
          <button type="button" @click="AddRules" class="button-primary">Add Condition</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "superwoo_couponrules",
  data() {
    return {
      loading: true,
      relation: "match_all",
      types: [
        {
          label: "Subtotal",
          value: "cart_subtotal",
        },
        {
          label: "Line Item Count",
          value: "cart_line_items_count",
        },
      ],
      operators: [
        {
          label: "Less than ( < )",
          value: "less_than",
        },
        {
          label: "Less than or equal ( <= )",
          value: "less_than_or_equal",
        },
        {
          label: "Greater than or equal ( >= )",
          value: "greater_than_or_equal",
        },
        {
          label: "greater_than ( > )",
          value: "greater_than",
        },
      ],
      calculates: [
        {
          label: "Count all items in cart",
          value: "from_cart",
        },
        {
          label: "Only count items chosen in the filters set for this rule",
          value: "from_filter",
        },
      ],
      conditions: [
        // {
        //   type: "cart_subtotal",
        //   operator: "less_than",
        //   item_count: null,
        //   calculate: "from_cart",
        // },
      ],
    };
  },
  created() {
    this.getRules();
  },
  methods: {
    AddRules() {
      this.conditions.push({
        type: "cart_subtotal",
        operator: "less_than",
        item_count: null,
        calculate: "from_cart",
      });
    },
    removeRule(index) {
      this.conditions.splice(index, 1);
    },
    getRules() {
      this.loading = false;
      let formData = {
        action: "superwoo_coupon_get_rules",
        post_id: superwoo_coupon_post.id,
      };
      let root = this;
      axios
        .post(superwoo_coupon_helper_obj.ajax_url, Qs.stringify(formData))
        .then((response) => {
          if (response.data != [] && response.data != "") {
            root.relation = response.data.relation;
            root.conditions =
              response.data.rules == null ? [] : response.data.rules;
          }
          root.loading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getRules();
  },
};
</script>
