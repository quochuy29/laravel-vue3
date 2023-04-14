<template>
    <a-spin :spinning="loadData">
        <a-modal
            v-model:visible="visChooseAction"
            title="Bố mày là admin"
            centered
            @ok="createEvent">
            <div class="choose-act">
                <a-button style="margin-right: 10px;" @click="createEventAct" type="primary">Event</a-button>
                <a-button @click="createRequestAct" type="primary" danger>Request</a-button>
            </div>
        </a-modal>
        <a-modal
            v-model:visible="visRequest"
            title="Vertically centered modal dialog"
            centered
            width="1000px"
            height="600px"
            @ok="createRequest">
            <request ref="dataRq" v-on:off-ovl="offOvl" :requestData="requestData" :time="time" :key="visRequest"></request>
        </a-modal>
        <a-modal
            v-model:visible="modal2Visible"
            title="Vertically centered modal dialog"
            centered
            @ok="createEvent">
            <a-spin :spinning="dataAccess">
                <event ref="data" :dataId.sync="dataId" :time="time" :key="modal2Visible"></event>
            </a-spin>
        </a-modal>
        <div class="calendar">
            <a-calendar v-model:value="value" @select="openCreateEvent(value)" :key="modal2Visible" style="width:75%;">
                <template #dateCellRender="{ current }">
                    <ul class="events">
                        <li v-if="requestData[current.format('YYYY-MM-DD').toString()]" v-for="request in requestData[current.format('YYYY-MM-DD').toString()]" :key="request.unpaid_flag">
                            <a-tag v-if="request.early_flag == 1" color="#faad14">Early Arrival {{request.early_time}}m</a-tag><br/>
                            <a-tag v-if="request.late_flag == 1" color="#faad14">Late Arrival {{request.late_time}}m</a-tag><br/>
                            <a-tag v-if="request.unpaid_flag == 1" color="#ff4d4f">Unpaid {{request.unpaid_leave}} day</a-tag><br/>
                        </li>
                        <li v-if="monthData[current.format('YYYY-MM-DD').toString()]" v-for="item in monthData[current.format('YYYY-MM-DD').toString()]" :key="item.content" :title="item.content">
                            <a-badge :status="item.type" :text="item.content" />
                        </li>
                    </ul>
                </template>
            </a-calendar>
            <inforRequestMember></inforRequestMember>
        </div>
    </a-spin>
</template>

<script>
import event from './event.vue';
import request from './request.vue';
import inforRequestMember from './infor-request-member.vue';
import { defineComponent, ref, onMounted, onBeforeMount, createVNode } from 'vue';
import axios from 'axios';
import moment from 'moment';
import _ from 'lodash';
import dayjs from 'dayjs';
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';
import { Modal } from 'ant-design-vue';

export default defineComponent({
    components: {
        event,
        request,
        inforRequestMember
    },
    data() {
        return {
            time: null,
            moment: moment,
            modal2Visible: false,
            title: [],
            dataId: null,
            visRequest: false,
            visChooseAction: false,
        }
    },
    setup() {
        const value = ref();
        const monthData = ref({});
        const loadData = ref(true);
        const dataAccess = ref(false);
        const requestData = ref({});
        const getDataCalendar = async () => {
            const res = await axios.get("api/calendar");
            monthData.value = res.data;
        };

        const listCalendarUser = async () => {
            const res = await axios.get("api/get-list-calendar-user");
            requestData.value = res.data;
        }
        
        onBeforeMount(async() => {
            await getDataCalendar();
            await listCalendarUser();
            loadData.value = !loadData.value;
        });
        
        onMounted(() => {
            document.querySelector('.ant-radio-button-wrapper:not(.ant-radio-button-wrapper-checked)').remove();
        });

        return {
            value,
            getDataCalendar,
            monthData,
            loadData,
            dataAccess,
            requestData
        };
    },
    async serverPrefetch() {
        await this.getDataCalendar();
    },
    methods: {
        createEventAct () {
            this.visRequest = false;
            this.modal2Visible = true;
        },
        createRequestAct () {
            const dateChoose = dayjs(this.time);
            const dateNow = dayjs();
            if (dateChoose.diff(dateNow, 'hour') > 0) {
                Modal.confirm({
                    title: 'NOT FOUND TIMESHEET',
                    icon: createVNode(ExclamationCircleOutlined),
                    content: '',
                    okText: 'OK',
                    cancelText: 'Cancel',
                    centered: true
                });
                return false;
            };
            this.modal2Visible = false;
            this.visRequest = true;
        },
        openCreateEvent(value) {
            const year =  document.querySelector('.ant-radio-button-wrapper:not(.ant-radio-button-wrapper-checked)');
            this.dataId = null;
            let convertDate = '';
            if (year !== null) {
                year.remove();
            }

            if (value === undefined) {
                value = moment(Date.now()).format('YYYY-MM-DD 12:00:00');
                convertDate = moment(Date.now()).format('YYYY-MM-DD');
            } else {
                convertDate = value.format('YYYY-MM-DD');
            }

            if (this.monthData[convertDate] !== undefined && this.monthData[convertDate].length > 0) {
                this.dataId = _.cloneDeep(this.monthData[convertDate]).map(v => ({...v, 'date': dayjs(v.date)}));
            }
            this.time = value;
            this.visChooseAction = true;
        },
        handleData() {
            let title = this.$refs.data.titles;
            if (this.$refs.data.dataId !== null) {
                title = this.$refs.data.data;
            }

            return title.map(v => ({...v, 'date': v.date !== '' ? v.date.format('YYYY-MM-DD') : ''}));
        },
        async createEvent() {
            this.dataAccess = true;
            const dataIp = {
                timeMain: this.$refs.data.timeMain,
                title: this.handleData()
            }

            try {
                const response = await axios.post('api/save-event', dataIp);
                if (response.data.update.length > 0) {
                    response.data.update.forEach(el => {
                        let date = moment(el.date).format('YYYY-MM-DD');
                        if (this.monthData[date] === undefined) {
                            this.monthData[date] = [];
                        }

                        this.monthData[date] = JSON.parse(el.title);
                    })
                }

                if (response.data.insert.length > 0) {
                    response.data.insert.forEach(el => {
                        let date = moment(el.date).format('YYYY-MM-DD');
                        if (this.monthData[date] === undefined) {
                            this.monthData[date] = [];
                        }

                        this.monthData[date] = [{ type: el.type, content: el.content }, ...this.monthData[date]]
                    });
                }
                this.modal2Visible = false;
                this.dataAccess = false;
            } catch (error) {
                this.$refs.data.handleErrors(error.response.data.errors);
                this.dataAccess = false;
            }
        },
        createRequest() {
            this.$refs.dataRq.createRequest();
        },
        offOvl() {
            this.visChooseAction = false;
            this.visRequest = false;
            this.modal2Visible = false;
        }
    },
});
</script>

<style>
.ant-modal {
    height: auto!important;
}

.events {
    list-style: none;
    margin: 0;
    padding: 0;
}

.events .ant-badge-status {
    overflow: hidden;
    white-space: nowrap;
    width: 100%;
    text-overflow: ellipsis;
    font-size: 12px;
}

.notes-month {
    text-align: center;
    font-size: 28px;
}

.notes-month section {
    font-size: 28px;
}

.ant-picker-calendar-date-content::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgb(255, 255, 255);
	background-color: #F5F5F5;
}

.ant-picker-calendar-date-content::-webkit-scrollbar
{
    width: 10px;
	background-color: #F5F5F5;
}

.ant-picker-calendar-date-content::-webkit-scrollbar-thumb
{
	background-color: rgb(119, 118, 119);
	background-image: -webkit-gradient(linear, 0 0, 0 100%,
	                   color-stop(.5, rgba(119, 118, 119)),
					   color-stop(.5, transparent), to(transparent));
}

.choose-act {
    display: flex;
    justify-content: center;
}

.events .ant-tag {
    margin: 0 auto;
    width: 100%;
    overflow-x: hidden;
}

.calendar {
    display: flex;
    column-gap: 10px;
}
</style>