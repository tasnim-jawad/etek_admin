<template lang="">
    <div class="custom_drop_down">
        <input type="hidden" :id="name" :name="name" :value="`[${selected_ids}]`">
        <div class="selected_list" @click="show_list = true">
            <div v-for="item in selected" :key="item.id" :id="item.id" class="selected_item">
                <div class="label">
                    {{ item.title }}
                    -
                    {{ item.customer_sales_price }}
                </div>
                <div @click.prevent="remove_item(item)" class="remove">
                    <i class="fa fa-close"></i>
                </div>
            </div>
        </div>
        <div class="drop_down_items" v-if="show_list">
            <div class="drop_down_data_search">
                <input @keyup="search_item($event)"
                    class="form-control"
                    placeholder="search.."
                    id="table_search_box" type="search">

                <button type="button"
                    @click.prevent="show_list = false"
                    class="btn btn-outline-danger">
                    <i class="fa fa-close"></i>
                </button>
            </div>
            <ul class="option_list custom_scroll">
                <li class="option_item" v-for="item in all.data" :key="item.id">
                    <label :for="`drop_item_${item.id}`">
                        <div class="check_box">
                            <input @change="set_selected(item, $event)"
                                :checked="is_selected(item)"
                                type="checkbox" :id="`drop_item_${item.id}`"
                                class="form-check-input ml-0">
                        </div>
                        <div class="label">
                            {{ item.id }}
                            <span> - {{ item.title }}</span>
                            <span> - {{ item.customer_sales_price }} TK</span>
                        </div>
                    </label>
                </li>
            </ul>
            <div class="drop_down_footer data_list">
                <pagination
                    :data="all"
                    :get_data="get_all"
                    :set_paginate="set_paginate"
                    :set_page="set_page" />
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions, mapState, mapWritableState } from 'pinia';
import { store } from '../../setup/store';
import debounce from '../../helpers/debounce';

export default {
    props: {
        name: {
            type: String,
            default: 'selected_' + (parseInt(Math.random() * 1000)),
        },
        callback: {
            type: Function,
            default: () => '',
        },
        default_data: {
            type: Array,
            default: [],
        }
    },
    created: function () {
        if (!this.all?.data?.length) {
            this.get_all();
        }
    },
    watch: {
        selected: {
            handler: function(newv){
                this.callback(JSON.parse(JSON.stringify(newv)));
            },
            deep: true,
        }
    },
    data: () => ({
        selected: [],
        show_list: false,
    }),
    methods: {
        ...mapActions(store, [
            'get_all',
            'set_paginate',
            'set_page',
        ]),
        search_item: debounce(async function (event) {
            let value = event.target.value;
            this.search_key = value;
            this.only_latest_data = true;
            await this.get_all();
            this.only_latest_data = false;
        }, 300),
        set_selected: function (item, event) {
            if (event.target.checked) {
                this.selected.push(item);
            } else {
                this.selected = this.selected.filter(i => i.id != item.id);
            }
        },
        is_selected: function (item) {
            return this.selected.find((i) => i.id == item.id);
        },
        remove_item: function (item) {
            this.selected = this.selected.filter(i => i.id != item.id);
        }
    },
    computed: {
        ...mapState(store, [
            'all',
        ]),
        ...mapWritableState(store, [
            'search_key',
        ]),
        selected_ids: function () {
            return this.selected.map(i => i.id).join(',');
        }
    }
}
</script>
<style lang="">

</style>
