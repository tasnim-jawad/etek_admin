import { mapActions, mapWritableState } from "pinia";
import { store } from "..";

async function set_selected_managed_items (index, value){
    let state = mapWritableState(store, [
        'selected_managed_items'
    ]);

    let temp = state.selected_managed_items.get();
    temp = JSON.parse(JSON.stringify(temp));
    temp[index] = value;

    state.selected_managed_items.set(temp);
}

export default set_selected_managed_items;
