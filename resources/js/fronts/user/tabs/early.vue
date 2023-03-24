<template>
    <a-spin :spinning="spinning">
        <div>
            <a-tag color="#cd201f">Date</a-tag><br/><br/>
            <a-date-picker v-model:value="date" /><br/><br/>
            <div class="time-range">
                <a-space direction="vertical">
                    <a-tag color="#cd201f">Start time</a-tag>
                    <a-time-picker v-model:value="timeWork.startTime" value-format="HH:mm" format="HH:mm"/>
                </a-space>
                <a-space direction="vertical" class="disabled">
                    <a-tag color="#cd201f" class="disabled">End time</a-tag>
                    <a-time-picker v-model:value="timeWork.endTime" value-format="HH:mm" format="HH:mm" disabled />
                </a-space>
            </div><br/>
            <a-tag color="#87d068">Duration: {{ duration }} day</a-tag><br/><br/>
            <a-tag color="#cd201f">Approver</a-tag><br/><br/>
            <a-select placeholder="Selected" style="width: 100px">
                <a-select-option v-for="(item, index) in approver" :key="index" :value="item.approve_user_code">{{ item.approve_user_name }}</a-select-option>
            </a-select><br/><br/>
            <a-tag color="#cd201f">Reason</a-tag><br/><br/>
            <a-textarea placeholder="Textarea with clear icon" allow-clear />
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
            timeAutoFill: [String]
        },
        data() {
            return {
                timeMain: dayjs(this.time).format('YYYY-MM-DD'),
                date: dayjs(this.time),
                timeWork: {
                    startTime: (this.timeAutoFill !== '') ? dayjs(this.timeAutoFill, 'HH:mm').format('HH:mm') : dayjs('08:30', 'HH:mm').format('HH:mm'),
                    endTime: dayjs('17:30', 'HH:mm').format('HH:mm')
                },
                duration: 1,
                latestValueTime: 17.30,
                earliestStartTime: 8.30
                
            }
        },
        watch: {
            timeWork: {
                deep:true,
                handler: async function (value) {
                    if (toRaw(value.startTime) == '') {
                        return false;
                    }

                    const duration = await axios.get(`api/duration?start_time=${toRaw(value.startTime)}&end_time=${toRaw(value.endTime)}&type=3`);
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