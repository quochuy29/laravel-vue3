<template>
    <a-table :columns="columns" :pagination="false" :data-source="myHistory" :loading="loading" @change="onChange" bordered>
        <template #bodyCell="{ column, record }">
        </template>
    </a-table>
    <a-pagination v-model:current="current" :total="page" :defaultPageSize="10" show-less-items @change="changePage" />
</template>

<script>
    import { defineComponent, ref, onBeforeMount } from "vue";
    import axios from "axios";

    export default defineComponent({
        setup() {
            const myHistory = ref([]);
            const loading = ref(true);
            const page = ref(10);
            const params = {
                search: {
                    page: 1
                }
            };
            const columns = [
                {
                    title: 'Transaction time',
                    dataIndex: 'created_at',
                    key: 'created_at'
                },
                {
                    title: 'Activity',
                    dataIndex: 'active',
                    key: 'active'
                },
                {
                    title: 'Amount',
                    dataIndex: 'amount',
                    key: 'amount'
                },
                {
                    title: 'Note',
                    dataIndex: 'note',
                    key: 'note'
                }
            ].filter(item => !item.hidden);
            const getHistory = async (params) => {
                loading.value = true;
                const res =  await axios.get('api/my-history', {params});
                page.value = (res.data.total < 10) ? 10 : res.data.total;
                console.log(res.data)
                myHistory.value = res.data.data;
                loading.value = !loading.value;
            };

            onBeforeMount(async () => {
                await getHistory(params);
            });

            const onChange = (pagination, filters, sorter) => {
                getHistory(params);
            };

            return {
                myHistory,
                current: ref(1),
                columns,
                getHistory,
                loading,
                onChange,
                params,
                page
            }
        },
        methods: {
            changePage(page) {
                this.params.page = page;
                this.getHistory(this.params);
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