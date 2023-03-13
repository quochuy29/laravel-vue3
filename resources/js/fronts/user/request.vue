<template>
    <div>
        <a-tag color="#cd201f">Date</a-tag><br/><br/>
        <a-date-picker v-model:value="date" /><br/><br/>
        <div class="time-range">
            <a-space direction="vertical">
                <a-tag color="#cd201f">Start time</a-tag>
                <a-time-picker v-model:value="timeWork.startTime" value-format="HH:mm" format="HH:mm"/>
            </a-space>
            <a-space direction="vertical">
                <a-tag color="#cd201f">End time</a-tag>
                <a-time-picker v-model:value="timeWork.endTime" value-format="HH:mm" format="HH:mm" />
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
</template>

<script>
    import dayjs from 'dayjs';
    import { defineComponent, ref, onBeforeMount, toRaw } from 'vue';
    export default defineComponent({
        props: {
            time: [String, Object]
        },
        data() {
            return {
                timeMain: dayjs(this.time).format('YYYY-MM-DD'),
                date: dayjs(this.time),
                value: '',
                timeWork: {
                    startTime: '',
                    endTime: ''
                },
                duration: 1
            }
        },
        watch: {
            timeWork: {
                deep:true,
                handler: function (value) {
                    if (toRaw(value.startTime) == '' && toRaw(value.endTime) == '') {
                        return false;
                    }
                    
                    const startTime = parseFloat(toRaw(value.startTime).replace(':', '.'));
                    const endTime = parseFloat(toRaw(value.endTime).replace(':', '.'));
                    if (startTime > 0 && endTime > 0) {
                        this.duration = ((endTime - startTime)) / 8;
                        if (startTime < 12 && endTime > 12) {
                            this.duration = ((endTime - startTime) - 1) / 8;
                        }
                        if (startTime > endTime) {
                            this.duration = 0;
                        }
                    }
                },
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
                approver,
                getApprover
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