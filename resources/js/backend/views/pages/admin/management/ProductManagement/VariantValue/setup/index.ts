import app_config from '../../../../app_config';
import setup_type from './setup_type';

const prefix: string = 'VariantValue';
const setup: setup_type = {
    prefix,
    permission: [`admin`,`super_admin`],

    api_host: app_config.api_host,
    api_version: app_config.api_version,
    api_end_point: 'product-varient-values',

    module_name: 'variant_value',
    store_prefix: 'variant_value',
    route_prefix: `VariantValue`,
    route_path: `variant-values`,

    select_fields: ['id', 'title', 'product_varient_group_id', 'product_varient_id','value','value2','slug', 'created_at', 'status'],
    sort_by_cols: ['id', 'title', 'created_at', 'status'],

    layout_title: prefix + ' Management',
    page_title: `${prefix} Management`,
    all_page_title: 'All ' + prefix,
    details_page_title: 'Details ' + prefix,
    create_page_title: 'Create ' + prefix,
    edit_page_title: 'Edit ' + prefix,
};

export default setup;
