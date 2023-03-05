<template>
    <a-modal
        v-model:visible="modal2Visible"
        title="Vertically centered modal dialog"
        centered
        @ok="createEvent">
        <abcssss ref="data" :dataId.sync="dataId" :time="time" :key="modal2Visible"></abcssss>
    </a-modal>
    <a-calendar v-model:value="value" @select="openCreateEvent(value)" :key="modal2Visible" style="width:90%">
        <template #dateCellRender="{ current }">
          <ul class="events">
            <li v-for="item in monthData[current.format('YYYY-MM-DD').toString()]" :key="item.content">
              <a-badge :status="item.type" :text="item.content" />
            </li>
          </ul>
        </template>
    </a-calendar>
</template>

<script>
import abcssss from './input.vue';
import { defineComponent, ref, onMounted, onBeforeMount } from 'vue';
import axios from 'axios';
import moment from 'moment';
import _ from 'lodash';
import dayjs from 'dayjs';

export default defineComponent({
    components: {
        abcssss
    },
    data() {
        return {
            time: null,
            moment: moment,
            modal2Visible: false,
            title: [],
            dataId: null
        }
    },
    setup() {
        const value = ref();
        const monthData = ref({});
        const getDataCalendar = async () => {
            const res = await axios.get("api/calendar");
            monthData.value = res.data;
        };
        
        onBeforeMount(async() => {
            await getDataCalendar();
        });
        
        onMounted(() => {
            document.querySelector('.ant-radio-button-wrapper:not(.ant-radio-button-wrapper-checked)').remove();
        });

        return {
            value,
            getDataCalendar,
            monthData
        };
    },
    async serverPrefetch() {
        await this.getDataCalendar();
    },
    methods: {
        openCreateEvent(value) {
            const year =  document.querySelector('.ant-radio-button-wrapper:not(.ant-radio-button-wrapper-checked)');
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

            if (this.monthData[convertDate] !== undefined) {
                this.dataId = _.cloneDeep(this.monthData[convertDate]).map(v => ({...v, 'date': dayjs(v.date)}));
            }
            this.time = value;
            this.modal2Visible = true;
        },
        async createEvent() {
            let title = null;
            if (this.$refs.data.dataId == null) {
                title = this.$refs.data.titles.map(v => ({...v, 'date': v.date.format('YYYY-MM-DD')}));
            } else {
                title = this.$refs.data.data.map(v => ({...v, 'date': v.date.format('YYYY-MM-DD')}));
            }
            
            const dataIp = {
                action: this.$refs.data.dataId == null ? 'add' : 'update',
                title: title
            }

            const dataiP = JSON.parse(JSON.stringify(dataIp));
            try {
                const response = await axios.post('api/add', dataiP);
                dataiP.title.forEach(el => {
                    let date = moment(el.date).format('YYYY-MM-DD');
                    if (this.monthData[date] === undefined) {
                        this.monthData[date] = [];
                    }

                    this.monthData[date] = [{ type: el.type, content: el.content }, ...this.monthData[date]]
                });
                this.modal2Visible = false;
            } catch (error) {
                this.$refs.data.handleErrors(error.response.data.errors);
            }
        },
        
    },
});
</script>

<style>
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
</style>