<template>
    <div>
        <form @submit.prevent="submitHandler">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="text-capitalize">{{ param_id ? 'Update' : 'Create' }} new {{ setup.prefix }}</h5>
                    <div>
                        <router-link v-if="item.slug" class="btn btn-outline-info mr-2 btn-sm"
                            :to="{ name: `Details${route_prefix}`, params: {id: item.slug} }">
                            Details {{ route_prefix }}
                        </router-link>
                        <router-link class="btn btn-outline-warning btn-sm" :to="{ name: `All${route_prefix}` }">
                            All {{ route_prefix }}
                        </router-link>
                    </div>
                </div>
                <div class="card-body card_body_fixed_height">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="row" v-if="order" style="row-gap: 30px;">
                                <div class="col-md-4">
                                    <label>
                                        Customer Type
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select v-model="cutomer_type" name="customer_type" id="customer_type" class="form-control">
                                        <option value="customer" selected>customer</option>
                                        <option value="retailer">retailer</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>
                                        Date
                                    </label>
                                    <input type="date" :value="new Date().toISOString().substring(0,10)"  class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>
                                        Select Customer
                                        <span class="text-danger">*</span>
                                    </label>
                                    <UserDropDown :callback="get_user_info" :name="`customer_ids`" :multiple="false"/>
                                </div>
                                <div class="col-md-6">
                                    <label>
                                        Billing Address
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea type="text" :value="user_info.address" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label>
                                        Shipping Address
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea type="text" :value="user_info.address" class="form-control"></textarea>
                                </div>
                            </div>

                            <hr class="my-4"/>
                            <div class="row justify-content-center">
                                <div class="col-lg-12">
                                    <!-- <input type="search" class="form-control" placeholder="Search Products"> -->
                                     <ProductDropDown :name="`product_ids`"/>
                                </div>
                            </div>
                            <hr class="my-4"/>

                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table text-nowrap">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th class="text-center">Discount</th>
                                                <th>D.Price</th>
                                                <th style="width: 120px;" class="text-center">Qty</th>
                                                <th class="text-right" style="width: 120px;">
                                                    Total
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(product,index) in selected_items" :key="product.id">
                                                <td>
                                                    <i @click.prevent="remove_data(index)" class="fa fa-trash btn btn-outline-danger"></i>
                                                </td>
                                                <td>
                                                    {{ product.title }}
                                                </td>
                                                <td>
                                                    {{ product.customer_sales_price }}
                                                </td>
                                                <td class="text-center">
                                                    <div style="gap: 10px;" class="d-flex justify-content-center align-items-center">
                                                        <input type="text"
                                                            @click="$event.target.select()"
                                                            v-model="product.discount_amount"
                                                            class="form-control text-center"
                                                            style="max-width: 80px;">
                                                        <span>
                                                            %
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ calculate_price(product) }}
                                                </td>
                                                <td class="text-center">
                                                    <input v-model="product.qty" type="text"
                                                        @click="$event.target.select()"
                                                        class="form-control text-center"
                                                        style="max-width: 80px;">
                                                </td>
                                                <td class="text-right">
                                                    {{ product.qty * calculate_price(product) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="text-end text-right">
                                            <tr>
                                                <td colspan="5" class="border-0"></td>
                                                <td class="border-top-md">Sub Total</td>
                                                <td class="border-top-md">{{ sub_total }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="border-0"></td>
                                                <td>Other Charge</td>
                                                <td>
                                                    <input type="text" v-model="other_charge" class="form-control text-right" style="width: 80px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="border-0"></td>
                                                <td class="border-0">
                                                    <select class="form-control" v-model="discount_on_total_type">
                                                        <option value="fixed">Fixed</option>
                                                        <option value="percentage">Percentage</option>
                                                    </select>
                                                </td>
                                                <td>Discount On Total</td>
                                                <td>
                                                    <input type="text" v-model="discount_on_total" class="form-control text-right" style="width: 80px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="border-0"></td>
                                                <td>Total</td>
                                                <td class="text-info font-weight-bold">
                                                    {{ total_price }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <hr class="my-4" />

                            <div class="form-group mt-5 text-center">
                                <button type="submit" class="btn btn-light btn-square px-5">
                                    <i class="icon-login"></i>
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-light btn-square px-5">
                        <i class="icon-lock"></i>
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { mapActions, mapState, mapWritableState } from 'pinia'
import { store } from './setup/store';
import { store as product_store } from '../../ProductManagement/Product/setup/store';
import setup from "./setup";
import form_fields from "./setup/form_fields";
import ProductDropDown from '../../ProductManagement/Product/components/dropdown/DropDownElManaged.vue'
import UserDropDown from '../../UserManagement/users/components/dropdown/DropDownEl.vue'

export default {
    components: {
        ProductDropDown,
        UserDropDown,
    },
    data: () => ({
        setup,
        route_prefix: '',
        form_fields,
        param_id: null,
        order:{},
        cutomer_type: 'customer',
        user_info: {},

        other_charge: 100,
        discount_on_total: 0,
        discount_on_total_type: 'fixed',
    }),
    created: async function () {
        axios.get('https://ctgcomputer.com/api/orders/66')
            .then(res=>{
                this.order = res.data;
            })
        let id = this.param_id = this.$route.params.id;
        this.route_prefix = setup.route_prefix;

        this.reset_fields();

        if (id) {
            this.set_fields(id);
        }
    },
    methods: {
        ...mapActions(store, {
            create: 'create',
            update: 'update',
            details: 'details',
        }),
        get_selected_product: function(values){
            // this.selected_items = values;
            if(this.selected_items.length != values.length){
                this.values.forEach(i=>{
                    let check = this.default_data.find(si=>si.id == i.id);
                    if(!check){
                        this.remove_item(i)
                    }
                });
            }
            console.log(values);
        },
        reset_fields: function () {
            this.form_fields.forEach((item) => {
                item.value = "";
            });
        },
        set_fields: async function (id) {
            this.param_id = id;
            await this.details(id);
            if (this.item) {
                this.form_fields.forEach((field, index) => {
                    Object.entries(this.item).forEach((value) => {
                        if (field.name == value[0]) {
                            this.form_fields[index].value = value[1];
                        }
                    });
                });
            }
        },

        submitHandler: async function ($event) {
            if (this.param_id) {
                let response = await this.update($event);
                if ([200, 201].includes(response.status)) {
                    window.s_alert("data updated");
                    this.$router.push({ name: `Details${this.route_prefix}` });
                }
            } else {
                let response = await this.create($event);
                if ([200, 201].includes(response.status)) {
                    window.s_alert("data created");
                    this.$router.push({ name: `All${this.route_prefix}` });
                }
            }
        },

        calculate_price: function(item){
            let discount_amount = this.validatePercentageInput(item.discount_amount);
            item.discount_amount = discount_amount;
            if(discount_amount > 0){
                return item.customer_sales_price - (item.customer_sales_price * (discount_amount/100))
            }else{
                return item.customer_sales_price
            }
        },

        validatePercentageInput: function(inputValue) {
            const percentage = Number(inputValue);

            // Check if the input is a number, an integer, and within the range of 0-100
            if (
                !isNaN(percentage) && // Check if it's a number
                Number.isInteger(percentage) && // Check if it's an integer
                percentage >= 0 && // Check if it's greater than or equal to 0
                percentage <= 100 // Check if it's less than or equal to 100
            ) {
                return percentage;
            } else {
                return 0;
            }
        },

        remove_data: function(index){
            this.selected_items.splice(index,1);
        },

        get_user_info: function(item){
            console.log(item);
            if(item.length){
                var info = {
                    name: item[0].name,
                    email: item[0].email,
                    phone_number: item[0].phone_number,
                };
                if(item[0]?.user_address?.length){
                    let address = item[0]?.user_address[0];
                    info = {
                        ...info,
                        address: address.address,
                        division: address.division?.name,
                        district: address.district?.name,
                        station: address.station?.name,
                    }
                }

                this.user_info = info;
            }else{
                this.user_info = {};
            }

        }
    },

    computed: {
        ...mapState(store, {
            item: "item",
        }),
        ...mapState(product_store, {
            selected_items: (state)=>{
                return state.selected_managed_items?.product_ids || [];
            },
        }),
        sub_total: function(){
            let that = this;
            return this.selected_items?.reduce(function(t, i){
                t += (i.qty * that.calculate_price(i));
                return t;
            },0);
        },
        total_price: function(){
            let dis = 0;
            let total = this.sub_total + this.other_charge;
            if(this.discount_on_total > 0){
                if(this.discount_on_total_type == "percentage"){
                    if(this.discount_on_total < 0) this.discount_on_total = 0;
                    if(this.discount_on_total > 100) this.discount_on_total = 100;

                    dis = total * this.discount_on_total / 100;

                    if(dis < 0) dis = 0;
                }
                if(this.discount_on_total_type == "fixed"){
                    dis = this.discount_on_total
                }
            }
            return total - dis;
        }
    },
}
</script>
