import { defineStore } from "pinia";
import { initialState } from "./initial_state";

/** async actions */
import all from "./async_actions/all";
import get_all_categories from "./async_actions/get_all_categories";
import create from "./async_actions/create";
import details from "./async_actions/details";
import update from "./async_actions/update";
import deactive from "./async_actions/deactive";
import restore from "./async_actions/restore";
import destroy from "./async_actions/destroy";

/** actions */
import set_filter_criteria from "./actions/set_filter_criteria";
import set_item from "./actions/set_item";
import set_only_latest_data from "./actions/set_only_latest";
import set_page from "./actions/set_page";
import set_paginate from "./actions/set_paginate";
import set_show_details_canvas from "./actions/set_show_details_canvas";
import set_show_filter_canvas from "./actions/set_show_filter_canvas";
import set_status from "./actions/set_status";
import set_selected_managed_items from "./actions/set_selected_managed_items";

import setup from "..";

export const store = defineStore(setup.store_prefix, {
    state: () => initialState,
    getters: {},
    actions: {
        /* async actions */
        get_all: all,
        create: create,
        update: update,
        details: details,
        deactive: deactive,
        restore: restore,
        destroy: destroy,
        get_all_categories: get_all_categories,

        /* actions */
        set_page,
        set_paginate,
        set_show_details_canvas,
        set_item,
        set_show_filter_canvas,
        set_filter_criteria,
        set_status,
        set_only_latest_data,
        set_selected_managed_items,
    },
});
