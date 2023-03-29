<template>
    <a-tabs v-model:activeKey="activeKey">
        <a-tab-pane key="1" tab="Edit Timesheet"><edit ref="edit" :time="time" :approver="approver"></edit></a-tab-pane>
        <a-tab-pane key="2" tab="Late"><late :requestData="requestData" :timeAutoFill="timeLateAutoFill" ref="late" :time="time" :approver="approver"></late></a-tab-pane>
        <a-tab-pane key="3" tab="Early"><early :requestData="requestData" :timeAutoFill="timeEarlyAutoFill" ref="early" :time="time" :approver="approver"></early></a-tab-pane>
        <a-tab-pane key="4" tab="Overtime"><overtime ref="over" :time="time" :approver="approver"></overtime></a-tab-pane>
        <a-tab-pane key="5" tab="Onsite"><onsite ref="onsite" :time="time" :approver="approver"></onsite></a-tab-pane>
        <a-tab-pane key="6" tab="Dayoff"><dayoff ref="off" :time="time" :approver="approver"></dayoff></a-tab-pane>
    </a-tabs>
</template>

<script>
    import { defineComponent, ref, onBeforeMount, toRaw } from 'vue';
    import dayjs from 'dayjs';
    import dayoff from './tabs/dayoff.vue';
    import late from './tabs/late.vue';
    import early from './tabs/early.vue';
    import edit from './tabs/edit-timesheet.vue';
    import overtime from './tabs/overtime.vue';
    import onsite from './tabs/onsite.vue';
    import axios from 'axios';
    export default defineComponent({
        props: {
            time: [String, Object],
            requestData: [Array, Object]
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
        setup(props) {
            const approver = ref({});
            const dataTab = (toRaw(props.requestData)[dayjs(props.time).format('YYYY-MM-DD')]) ? toRaw(props.requestData)[dayjs(props.time).format('YYYY-MM-DD')][0] : '';
            const key = (dataTab !== '') ? (dataTab.late_flag == 1) ? ref('2') : ref('1') || (dataTab.early_flag == 1) ? ref('3') : ref('1') : ref('1');
            const timeLateAutoFill = (dataTab !== '' && dataTab.late_flag == 1) ? dataTab.checkin : '';
            const timeEarlyAutoFill = (dataTab !== '' && dataTab.early_flag == 1) ? dataTab.checkout : '';
            const getApprover = async () => {
                const res = await axios.get("api/approver");
                approver.value = res.data;
            };

            onBeforeMount(async() => {
                await getApprover();
            });

            return {
                activeKey: key,
                approver,
                timeLateAutoFill,
                timeEarlyAutoFill,
            };
        },
        methods: {
            getDataRequest() {
                let dataRequest = null;
                switch (this.activeKey) {
                    case '1':
                        dataRequest = this.$refs.edit;
                        break;
                    case '2':
                        dataRequest = this.$refs.late;
                        break;
                    case '3':
                        dataRequest = this.$refs.early;
                        break;
                    case '4':
                        dataRequest = this.$refs.over;
                        break;
                    case '5':
                        dataRequest = this.$refs.onsite;
                        break;
                    case '6':
                        dataRequest = this.$refs.off;
                        break;
                    default: 
                        dataRequest = null;
                        break;
                }

                return dataRequest;
            },
            async createRequest() {
                const dataRequest = this.getDataRequest();
                const data = {
                    'time_request': dataRequest.timeWork,
                    'approver': dataRequest.approve,
                    'duration': dataRequest.duration,
                    'date': dataRequest.date,
                    'reason': dataRequest.reason,
                    'type': this.activeKey
                }
console.log(dataRequest);
                try {
                    const res = await axios.post('api/create-request', data);
                    this.$emit('off-ovl');
                } catch (error) {
                    console.log(error);                    
                }
            }
        },
    });
</script>
