<template>
  <div>
    <div v-if="loading" class="spinner is-active superwoo_coupon_spinner"></div>
    <div v-else>
      <div
        class="superwoo_coupon-flex superwoo_coupon-filter"
        v-for="(superwoo_couponfilter, index) in superwoo_couponfilters"
        :key="'superwoo_couponfilter-' + index"
      >
        <div class="superwoo_coupon-col-3">
          <div class="superwoo_coupon-form">
            <label for="superwoo_coupon_filter_type">
              <strong>Type</strong>
            </label>
            <select
              id="superwoo_coupon_filter_type"
              name="superwoo_coupon_filter_type[]"
              v-model="superwoo_couponfilter.type"
            >
              <option
                v-for="(filterType, index) in filterTypes"
                :key="'filterType-' + index"
                :value="filterType.value"
              >{{ filterType.label }}</option>
            </select>
          </div>
        </div>
        <div class="superwoo_coupon-filter-list" v-if="checkItemsAvaiable(superwoo_couponfilter.type)">
          <div class="superwoo_coupon-form">
            <label for="superwoo_coupon_filter_lists">
              <strong>Lists Type</strong>
            </label>
            <select
              id="superwoo_coupon_filter_lists"
              name="superwoo_coupon_filter_lists[]"
              v-model="superwoo_couponfilter.lists"
            >
              <option
                v-for="(ListsType, index) in ListsTypes"
                :key="'ListsType-' + index"
                :value="ListsType.value"
              >{{ ListsType.label }}</option>
            </select>
          </div>
        </div>
        <div class="superwoo_coupon-col-3" v-if="checkItemsAvaiable(superwoo_couponfilter.type)">
          <div class="superwoo_coupon-form">
            <label for="superwoo_coupon_filter_products">
              <strong>{{ getItemsLabel(superwoo_couponfilter.type) }}</strong>
            </label>
            <customSelect
              v-on:selectOptions="selectOptions"
              :multiData="{
                options: [],
                searchable: true,
                placeholder: 'Enter 3 words',
                search_action: getItemsAction(superwoo_couponfilter.type),
              }"
              :defaultOption="superwoo_couponfilter.items"
              :multiName="index"
            ></customSelect>
          </div>
        </div>
        <div
          v-if="superwoo_couponfilters.length > 1"
          @click="removeFilter(index)"
          class="superwoo_coupon-filter-close"
        >
          <span class="dashicons dashicons-no-alt"></span>
        </div>
      </div>
      <div class="superwoo_coupon_buttons">
        <button type="button" @click="update" class="button-primary">Save</button>
        <button type="button" @click="cloneFilter" class="button-primary">Add Filter</button>
      </div>
    </div>
  </div>
</template>

<script>
import customSelect from "./helpers/customSelect";
export default {
  name: "superwoo_couponfilter",
  props: ["nonce"],
  data() {
    return {
      loading: true,
      filterTypes: [],
      ListsTypes: [
        { label: "In List", value: "inList" },
        { label: "Not In List", value: "noList" },
      ],
      superwoo_couponfilters: [
        {
          type: "all_products",
          lists: "inList",
          items: [],
        },
      ],
    };
  },
  created() {
    this.getFilters();
  },
  methods: {
    checkItemsAvaiable(filter_type) {
      let result = false;
      this.filterTypes.forEach((element) => {
        if (element.value == filter_type) {
          result = element.has_item;
        }
      });
      return result;
    },
    getItemsLabel(filter_type) {
      let label;
      this.filterTypes.forEach((element) => {
        if (element.value == filter_type) {
          label = element.items.label;
        }
      });
      return label;
    },
    getItemsAction(filter_type) {
      let action;
      this.filterTypes.forEach((element) => {
        if (element.value == filter_type) {
          action = element.items.action;
        }
      });
      return action;
    },
    selectOptions(value) {
      this.superwoo_couponfilters[value.name].items = value.selectOption;
    },
    cloneFilter() {
      this.superwoo_couponfilters.push({
        type: "all_products",
        lists: "inList",
        items: [],
      });
    },
    removeFilter(index) {
      this.superwoo_couponfilters.splice(index, 1);
    },
    update() {
      let formData = {
        action: "superwoo_coupon_save_filters",
        superwoo_couponfilters: this.superwoo_couponfilters,
        superwoo_coupon_nonce: this.nonce,
        post_id: superwoo_coupon_post.id,
      };
      axios
        .post(superwoo_coupon_helper_obj.ajax_url, Qs.stringify(formData))
        .then((response) => {
          this.$toasted.show(response.data.message, {
            position: "top-center",
            duration: 3000,
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getFilters() {
      this.loading = true;
      let formData = {
        action: "superwoo_coupon_get_filters",
        post_id: superwoo_coupon_post.id,
      };
      let root = this;
      axios
        .post(superwoo_coupon_helper_obj.ajax_url, Qs.stringify(formData))
        .then((response) => {
          if (
            response.data.post_meta != [] &&
            response.data.post_meta != null
          ) {
            root.superwoo_couponfilters = response.data.post_meta;
          }
          response.data.filters_data.forEach((element) => {
            root.filterTypes.push(element);
          });
          root.loading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  components: {
    customSelect,
  },
};
</script>
