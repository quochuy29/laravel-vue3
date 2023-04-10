<template>
    <a-row :gutter="[8,8]">
        <a-col :span="4">
            <a-date-picker v-model:value="month" picker="month" format="MMM" />
        </a-col>
        <a-col :span="4">
            <a-date-picker v-model:value="year" picker="year" format="YYYY" />
        </a-col>
    </a-row>
    
    <a-table :columns="columns" :pagination="false" :data-source="myRequest" :loading="loading" @change="onChange" bordered>
        <template #bodyCell="{ column, record }">
            <template v-if="column.key === 'request_type_name'">
                <span>
                    {{ `${record.request_type_name}  (${record.request_type_code})` }}
                </span>
            </template>
            <template v-if="(column.key === 'request_code') ? 'style: display: none;' : ''">
            </template>
            <template v-if="column.key === 'action'">
                <a-dropdown v-if="!record.status">
                    <button class="ant-btn action-request ant-dropdown-trigger ant-dropdown-open ant-btn-icon-only" type="button">
                        <span role="img" aria-label="ellipsis" class="anticon anticon-ellipsis">
                            <svg focusable="false" class="" data-icon="ellipsis" width="1em" height="1em" fill="currentColor" aria-hidden="true" viewBox="64 64 896 896">
                                <path d="M176 511a56 56 0 10112 0 56 56 0 10-112 0zm280 0a56 56 0 10112 0 56 56 0 10-112 0zm280 0a56 56 0 10112 0 56 56 0 10-112 0z"></path>
                            </svg>
                        </span>
                    </button>
                    <template #overlay>
                        <a-menu>
                            <a-menu-item key="1" @click="approveAct('approve', record.request_code)" >
                                Apporver
                            </a-menu-item>
                            <a-menu-item key="2" @click="approveAct('pending',record.request_code)">
                                Pending
                            </a-menu-item>
                            <a-menu-item key="3" @click="approveAct('reject',record.request_code)">
                                Reject
                            </a-menu-item>
                            <a-menu-item key="4" @click="approveAct('cancel',record.request_code)">
                                Cancelled
                            </a-menu-item>
                        </a-menu>
                    </template>
                </a-dropdown>
                <Common-approve v-if="record.status == 'Approve'" />
                <Common-reject v-if="record.status == 'Reject'" />
                <Common-pending v-if="record.status == 'Pending'" />
                <Common-cancel v-if="record.status == 'Cancelled'" />
            </template>
        </template>
    </a-table>
    <a-pagination v-model:current="current" :total="13" show-less-items @change="changePage" />
</template>

<script>
    import { defineComponent, ref, onBeforeMount } from "vue";
    import axios from "axios";
    import dayjs from 'dayjs';

    export default defineComponent({
        watch: {
            month: {
                deep:true,
                handler: async function (value) {
                    this.params.search.month = value.format('MM');
                    this.getRequest(this.params);
                },
            },

            year: {
                deep:true,
                handler: async function (value) {
                    this.params.search.year = value.format('YYYY');
                    this.getRequest(this.params);
                },
            }
        },
        setup() {
            const myRequest = ref([]);
            const loading = ref(true);
            const month = ref(dayjs());
            const year = ref(dayjs());
            const params = {
                search: {
                    month: month.value.format('MM'),
                    year: year.value.format('YYYY'),
                    page: 1
                }
            };
            const columns = [
                {
                    title: 'Start time',
                    dataIndex: 'start_time',
                    key: 'start_time',
                    sorter: true
                },
                {
                    title: 'End time',
                    dataIndex: 'end_time',
                    key: 'end_time',
                    sorter: true
                },
                {
                    title: 'Request type',
                    dataIndex: 'request_type_name',
                    key: 'request_type_name'
                },
                {
                    title: 'Approver',
                    dataIndex: 'user_approve_name',
                    key: 'user_approve_name',
                    sorter: true
                },
                {
                    title: 'Status',
                    dataIndex: 'status',
                    key: 'status'
                },
                {
                    title: 'Reason',
                    dataIndex: 'reason',
                    key: 'reason'
                },
                {
                    title: 'Approver note',
                    dataIndex: 'reason',
                    key: 'approver_note'
                },
                {
                    title: 'Request at',
                    dataIndex: 'request_at',
                    key: 'request_at',
                    sorter: true
                },
                {
                    dataIndex: 'request_code',
                    key: 'request_code',
                    hidden: true
                },
                {
                    title: 'Action',
                    key: 'action'
                }
            ].filter(item => !item.hidden);
            const getRequest = async (params) => {
                loading.value = true;
                const res =  await axios.get('api/my-request', {params});
                myRequest.value = res.data.data;
                loading.value = !loading.value;
            };

            onBeforeMount(async () => {
                await getRequest(params);
            });

            const onChange = (pagination, filters, sorter) => {
                params['field'] = sorter.field;
                params['order'] = sorter.order == 'descend' ? 'desc' : 'asc';

                getRequest(params);
            };

            return {
                myRequest,
                current: ref(1),
                columns,
                getRequest,
                loading,
                onChange,
                month,
                year,
                params
            }
        },
        methods: {
            changePage(page) {
                this.params.page = page;
                this.getRequest(this.params);
            },
            async approveAct(type,code) {
                try {
                    const res = axios.put(`api/approve-request/${type}/${code}`);
                } catch (error) {
                    
                }
            }
        },
    });
</script>

<style lang="less">
.ant-pagination {
    padding: 10px 10px 0 0;
    display: flex;
    justify-content: flex-end;
    li {
        border-radius: 50%;
        .ant-pagination-item-link {
            border-radius: 50%;
        }
    }
}
.ant-table-wrapper {
    padding: 0 10px;
}
.ant-row {
    margin: 0 !important;
    padding: 10px;
    .ant-picker {
        width: 100%;
    }
}
.action-request {
    width: 32px;
    height: 32px;
}
</style>