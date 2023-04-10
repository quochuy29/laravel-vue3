<template>
    <a-spin :spinning="spinning">
        <div>
            <a-tag color="#cd201f">Date</a-tag><br/><br/>
            <a-date-picker v-model:value="date" /><br/><br/>
            <div class="time-range">
                <a-space direction="vertical" class="disabled">
                    <a-tag color="#cd201f" class="disabled">Start time</a-tag>
                    <a-time-picker v-model:value="timeWork.startTime" value-format="HH:mm" format="HH:mm" disabled/>
                </a-space>
                <a-space direction="vertical" >
                    <a-tag color="#cd201f">End time</a-tag>
                    <a-time-picker v-model:value="timeWork.endTime" value-format="HH:mm" format="HH:mm" />
                </a-space>
            </div><br/>
            <a-tag color="#87d068">Duration: {{ duration }} day</a-tag><br/><br/>
            <a-tag color="#cd201f">Approver</a-tag><br/><br/>
            <a-select placeholder="Selected" style="width: 100px" v-model:value="approve">
                <a-select-option v-for="(item, index) in approver" :key="index" :value="`${item.approve_user_code}_${item.approve_user_name}`">{{ item.approve_user_name }}</a-select-option>
            </a-select><br/><br/>
            <a-tag color="#cd201f">Reason</a-tag><br/><br/>
            <a-textarea v-model:value="reason" placeholder="Textarea with clear icon" allow-clear />
        </div>
    </a-spin>
</template>

<script>
    import dayjs from 'dayjs';
    import { defineComponent, ref, toRaw } from 'vue';
    export default defineComponent({
        props: {
            time: [String, Object],
            approver: [Object, Array],
            requestData: [Array, Object],
            timeAutoFill: [String],
            startTime: [String]
        },
        data() {
            return {
                timeMain: dayjs(this.time).format('YYYY-MM-DD'),
                date: dayjs(this.time),
                approve: '',
                timeWork: {
                    startTime: (this.startTime !== '') ? this.startTime : dayjs('08:30', 'HH:mm').format('HH:mm'),
                    endTime: (this.timeAutoFill !== '' && this.timeAutoFill !== null) ? dayjs(this.timeAutoFill, 'HH:mm').format('HH:mm') : dayjs('17:30', 'HH:mm').format('HH:mm')
                },
                duration: 1,
                reason: ''
            }
        },
        async mounted() {
            if (this.timeAutoFill == '') {
                return;
            }
            const duration = await axios.get(`api/duration?start_time=${toRaw(this.timeWork.startTime)}&end_time=${toRaw(this.timeWork.endTime)}&type=2`);
            this.duration = duration.data;
        },
        watch: {
            timeWork: {
                deep:true,
                handler: async function (value) {
                    if (toRaw(value.endTime) == '') {
                        return false;
                    }

                    const duration = await axios.get(`api/duration?start_time=${toRaw(value.startTime)}&end_time=${toRaw(value.endTime)}&type=2`);
                    this.duration = duration.data;
                },
            }
        },
        setup() {
            const spinning = ref(false);

            return {
                spinning
            };
        }
    });
</script>

<style lang="css">
.ant-picker-time-panel-column::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgb(255, 255, 255);
	background-color: #F5F5F5;
}

.ant-picker-time-panel-column::-webkit-scrollbar
{
    width: 10px;
	background-color: #F5F5F5;
}

.ant-picker-time-panel-column::-webkit-scrollbar-thumb
{
	background-color: rgb(119, 118, 119);
	background-image: -webkit-gradient(linear, 0 0, 0 100%,
	                   color-stop(.5, rgba(119, 118, 119)),
					   color-stop(.5, transparent), to(transparent));
}

.time-range .ant-space {
    margin-right: 10px;
}

.ant-space .disabled {
    pointer-events: none;
    opacity: 0.45;
}
</style>