<template>
    <v-row>
        <template style="margin: 50px 0">
            <v-col :cols="4">
                <v-text-field
                    v-model="datatable.search"
                    label="Search"
                    single-line
                    hide-details
                    :readonly="datatable.fetchingData"
                    @keyup.enter="handleSearchEnter"
                >
                    <template v-slot:append-outer>
                        <v-btn icon @click="loadData('search')">
                            <v-icon>search</v-icon>
                        </v-btn>
                    </template>
                </v-text-field>
            </v-col>

            <v-col :cols="4" v-for="(filter, index) in datatable.filters" :key="index">
                <v-autocomplete
                    v-model="filter.model"
                    :items="filter.items"
                    :item-value="filter.itemValue"
                    :item-text="filter.itemText"
                    :label="filter.label"
                    :multiple="filter.multiple"
                    @change="($event) => {
                        if (filter.hasOwnProperty('changed')) {
                            filter.changed($event)
                        }
                    }"
                >
                    <template v-slot:item="{item}">
                        {{ typeof filter.itemText  === 'function' ? filter.itemText(item) :  item[filter.itemText] }}
                    </template>
                </v-autocomplete>
            </v-col>
        </template>

        <v-col :cols="12">
            <v-data-table
                :show-expand="showExpand"
                :loading="datatable.fetchingData"
                :headers="datatable.headers"
                :items="datatable.data"
                :server-items-length="datatable.total"
                disable-sort
                disable-filtering
                hide-default-footer
            >
                <template v-slot:item.actions="{ item }" v-if="actions.enabled">
                    <v-btn icon
                           :color="datatable.icons[button]['color']"
                           v-for="(button, index) in actions.buttons"
                           :key="index"
                           @click="$emit(button, item)"
                    >
                        <v-icon>{{ datatable.icons[button]['icon'] }}</v-icon>
                    </v-btn>
                </template>

                <template v-else v-slot:item.actions="{item}">
                    <slot name="item.actions" :item="item"/>
                </template>

                <template v-slot:item.status="{item}">
                    <Status
                        :status="item.status"
                    />
                </template>

                <template v-for="slot in Object.keys($slots).map(s => `item.${s}`)" v-slot:[slot]="{item}">
                    <slot-renderer
                        :item="item"
                        :node="$slots[slot.replace('item.', '')]"
                    />
                </template>

                <template v-slot:expanded-item="{ headers, item }" v-if="showExpand">
                    <td :colspan="headers.length" v-if="!datatable.fetchingData">
                        <slot-renderer
                            :item="item"
                            :node="$slots['expand']"
                        />
                    </td>
                </template>
            </v-data-table>

            <v-row class="text-left pt-2" v-if="enablePagination">
                <v-col :md="10" sm="12">
                    <v-pagination
                        v-model="datatable.currentPage"
                        :length="datatable.paginationLength"
                        next-icon="chevron_right"
                        prev-icon="chevron_left"
                        total-visible="10"
                    />
                </v-col>

                <v-col :md="2" :sm="12">
                    <span style="font-size: 20px; margin-top: 12px">
                        Total: {{ this.datatable.total }}
                    </span>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    import Status from '../Components/Status';
    import Vue from "vue";
    import {Inertia} from "@inertiajs/inertia";

    Vue.component('slot-renderer', {
        props: {
            node: {
                required: true,
            },
            item: {
                required: true,
                type: Object
            }
        },
        render(createElement) {
            this.node.map(items => {
                if (items.componentOptions && items.componentOptions.propsData) {
                    items.componentOptions.propsData.item = this.item
                }
            })

            return createElement('div', {}, this.node)
        }
    })

    export default {
        name: "XDatatable",
        components: {
            Status
        },
        props: {
            showExpand: {
                required: false,
                type: Boolean,
                default: false
            },
            enableSearch: {
                required: false,
                type: Boolean,
                default: true
            },
            enablePagination: {
                required: false,
                type: Boolean,
                default: true
            },
            ajax: {
                required: true,
                type: Object
            },
            actions: {
                required: false,
                type: Object,
                default: () => ({
                    enabled: false,
                    buttons: []
                })
            },
            headers: {
                required: true,
                type: Array
            },
            filters: {
                required: false,
                type: Array,
                default: () => ([])
            },
            pagination: {
                required: false,
                type: Object,
                default: () => ({}),
            }
        },
        async beforeMount() {
            this.datatable.fetchingData = true

            this.headers.map(header => {
                this.datatable.headers.push({
                    text: header.text,
                    value: header.value,
                    align: 'center',
                    filterable: header.filterable || false,
                    orderable: header.orderable || false,
                    divider: true,
                    class: 'datatable-header'
                })
            })

            if (this.actions.enabled) {
                this.datatable.headers.push({
                    text: 'Actions',
                    align: 'center',
                    filterable: false,
                    value: 'actions',
                    orderable: false,
                    divider: true,
                    class: 'datatable-header'
                })
            }

            this.datatable.filters = [...this.filters]

            const params = new URLSearchParams(window.location.search)

            if (params.has('page') && params.get('page')) {
                this.datatable.currentPage = Number(params.get('page'))
            }

            this.datatable.fetchingData = false

            await this.loadData();
        },
        data: function () {
            return {
                posting: false,
                modelToDelete: {},
                confirmDeleteDialog: false,
                datatable: {
                    data: [],
                    headers: [],
                    filters: null,
                    search: '',
                    total: 0,
                    fetchingData: false,
                    currentPage: 1,
                    paginationLength: 0,
                    searchApplied: false,
                    icons: {
                        edit: {
                            icon: 'edit',
                            color: 'green',
                        },
                        delete: {
                            icon: 'delete',
                            color: 'red',
                        },
                        block: {
                            icon: 'block',
                            color: "red",
                        },
                        view: {
                            icon: 'visibility',
                            color: 'primary'
                        },
                        discount: {
                            icon: 'local_offer',
                            color: 'secondary'
                        }
                    }
                }
            }
        },
        methods: {
            handleSearchEnter() {
                if (this.datatable.search) {
                    this.loadData('search');
                }
            },
            loadData(target = '') {
                if (this.datatable.fetchingData) {
                    return
                }

                let postData = {
                    filters: [],
                }

                if (target === 'search') {
                    this.datatable.searchApplied = true

                    this.datatable.currentPage = 1
                }

                this.datatable.fetchingData = true;

                let query = {
                    page: this.datatable.currentPage,
                    search: this.datatable.search,
                    ...(this.ajax.query || {})
                }

                query = Object.keys(query)
                    .map(key => `${key}=${query[key]}`)
                    .join('&');

                this.datatable.filters.map(filter => {
                    if ((Array.isArray(filter.model))) {
                        if (filter.model.length > 0) {
                            postData.filters.push({
                                name: filter.name,
                                value: filter.model
                            })
                        }
                    } else {
                        if (filter.model) {
                            postData.filters.push({
                                name: filter.name,
                                value: filter.model
                            })
                        }
                    }
                });

                return axios.post(`${this.ajax.url}?${query}`, postData)
                    .then(response => {
                        this.datatable.total = response.data.total;
                        this.datatable.data = response.data.data;
                        this.datatable.paginationLength = response.data.last_page

                        if (response.data.last_page < this.datatable.currentPage) {
                            this.datatable.currentPage = response.data.last_page
                        }
                    }).finally(() => this.datatable.fetchingData = false)
            }
        },
        watch: {
            'datatable.search': function (newValue) {
                if (!newValue && !this.datatable.fetchingData) {
                    this.datatable.searchApplied = false
                    this.loadData()
                }
            },
            'datatable.currentPage': function (newVal, oldVal) {
                if (!oldVal) {
                    return
                }

                try {
                    Inertia.get(window.location.pathname, {page: Number(newVal)}, {
                        preserveState: true,
                        preserveScroll: true,
                        onSuccess: () => {
                            this.loadData()
                        }
                    })
                } catch (e) {

                }
            },
            'datatable.filters': {
                deep: true,
                handler(oldValue) {
                    if (!oldValue) {
                        return
                    }

                    this.loadData()
                }
            },
            'filters': {
                deep: true,
                handler() {
                    this.datatable.fetchingData = true

                    // only map items
                    this.datatable.filters.forEach(f => {
                        f.items = this.filters.find(filter => filter.name === f.name)?.items || []
                    })

                    this.datatable.fetchingData = false
                }
            }
        }
    }
</script>
