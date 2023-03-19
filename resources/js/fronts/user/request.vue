<template>
    <a-tabs v-model:activeKey="activeKey" @change="checkType">
        <a-tab-pane key="1" tab="Edit Timesheet"><edit ref="edit" :time="time" :approver="approver"></edit></a-tab-pane>
        <a-tab-pane key="2" tab="Late"><late :approver="approver"></late></a-tab-pane>
        <a-tab-pane key="3" tab="Early"><early :time="time" :approver="approver"></early></a-tab-pane>
        <a-tab-pane key="4" tab="Overtime"><overtime :time="time" :approver="approver"></overtime></a-tab-pane>
        <a-tab-pane key="5" tab="Onsite"><onsite :time="time" :approver="approver"></onsite></a-tab-pane>
        <a-tab-pane key="6" tab="Dayoff"><dayoff :time="time" :approver="approver"></dayoff></a-tab-pane>
    </a-tabs>
</template>

<script>
    import { defineComponent, ref, onBeforeMount, toRaw } from 'vue';
    import dayoff from './tabs/dayoff.vue';
    import late from './tabs/late.vue';
    import early from './tabs/early.vue';
    import edit from './tabs/edit-timesheet.vue';
    import overtime from './tabs/overtime.vue';
    import onsite from './tabs/onsite.vue';
    import axios from 'axios';
    export default defineComponent({
        props: {
            time: [String, Object]
        },
        components: {
            dayoff,
            late,
            early,
            edit,
            overtime,
            onsite
        },
        data() {
            return {
                type: 1,
                data: null
            }
        },
        setup() {
            const approver = ref({});
            const getApprover = async () => {
                const res = await axios.get("api/approver");
                approver.value = res.data;
            };

            onBeforeMount(async() => {
                await getApprover();
            });

            return {
                activeKey: ref('1'),
                approver,
            };
        },
        methods: {
            checkType() {
                this.type = this.activeKey;
            },
            async createRequest() {
                let dataRequest = this.$refs.edit;
                const data = {
                    'time_request': dataRequest.timeWork,
                    'approver': dataRequest.approve,
                    'duration': dataRequest.duration,
                    'date': dataRequest.date,
                    'reason': dataRequest.reason,
                    'type': this.type
                }

                const res = await axios.post('api/create-request', data);
            }
        },
    });
</script>
