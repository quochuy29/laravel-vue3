<template>
    <a-spin :spinning="spinning">
        <div>
            <div class="time-range">
                <a-space direction="vertical">
                    <a-tag color="#cd201f">Start time</a-tag>
                    <a-date-picker show-time v-model:value="timeWork.startTime" value-format="YYYY-MM-DD HH:mm" format="YYYY-MM-DD HH:mm"/>
                </a-space>
                <a-space direction="vertical">
                    <a-tag color="#cd201f">End time</a-tag>
                    <a-date-picker show-time v-model:value="timeWork.endTime" value-format="YYYY-MM-DD HH:mm" format="YYYY-MM-DD HH:mm"/>
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
            approver: [Object, Array]
        },
        data() {
            return {
                timeMain: dayjs(this.time).format('YYYY-MM-DD'),
                date: dayjs(this.time),
                approve: '',
                timeWork: {
                    startTime: `${dayjs(this.time).format('YYYY-MM-DD')} 08:30`,
                    endTime: `${dayjs(this.time).format('YYYY-MM-DD')} 17:30`
                },
                reason: '',
                duration: 1
            }
        },
        watch: {
            timeWork: {
                deep:true,
                handler: async function (value) {
                    if (toRaw(value.startTime) == '' && toRaw(value.endTime) == '') {
                        return false;
                    }

                    const duration = await axios.get(`api/duration?start_time=${toRaw(value.startTime)}&end_time=${toRaw(value.endTime)}&type=6`);
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
</style>