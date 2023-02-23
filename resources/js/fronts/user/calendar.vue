<template>
    <a-modal
        v-model:visible="modal2Visible"
        title="Vertically centered modal dialog"
        centered
        @ok="add">
        <abcssss ref="data" :time="time" :key="modal2Visible"></abcssss>
    </a-modal>
    <a-calendar v-model:value="value" @click="dua(value)" :key="modal2Visible">
        <template #dateCellRender="{ current }">
          <ul class="events">
            <li v-for="item in getListData(current)" :key="item.content">
              <a-badge :status="item.type" :text="item.content" />
            </li>
          </ul>
        </template>

        <template #monthCellRender="{ current }">
            <div v-if="getMonthData(current)" class="notes-month">
                <section>{{ getMonthData(current) }}</section>
                <span>Backlog number</span>
            </div>
        </template>
    </a-calendar>
</template>

<script>
import abcssss from './input.vue';
import { defineComponent, ref, onMounted } from 'vue';
import axios from 'axios';
import moment from 'moment';

export default defineComponent({
    components: {
        abcssss
    },
    data() {
        return {
            data: null,
            time: null,
            moment: moment,
            modal2Visible: false,
            title: []
        }
    },
    setup() {
        const value = ref();
        const data = ref([]);
        const getDataCalendar = async() => {
            const datas = await axios.get('api/calendar');
            data.value = datas.data;
        };
        onMounted(async() => {
            await getDataCalendar();
        });

        const getListData = (value, dataIp = {}) => {
            let listData;
            data.value.forEach((date) => {
                if (value.format('YYYY-MM-DD') == date.date) {
                    listData = JSON.parse(date.title);
                    if (Object.keys(dataIp).length > 0) {
                        dataIp.title.forEach((x) => {
                            listData.unshift(x);
                        })
                    }
                }
            });
            return listData || [];
        };

        const getMonthData = value => {
            if (value.month() === 8) {
                return 1394;
            }
        };

        return {
            value,
            getListData,
            getMonthData,
            getDataCalendar
        };
    },
    methods: {
        dua(vl) {
            if (vl == undefined) {
                const today = new Date();
                vl = moment(today).format('YYYY-MM-DD');
            }
            this.time = vl;
            this.modal2Visible = true;
        },
        async add() {
            const dataIp = {
                date: this.$refs.data.date.format('YYYY-MM-DD'),
                title: this.$refs.data.titles
            }
            const dataiP = JSON.parse(JSON.stringify(dataIp));
            const response = await axios.post('api/add', dataiP);

            if (response.status == 200) {
                this.modal2Visible = false;
                await this.getDataCalendar();
                await this.getListData(this.$refs.data.date, dataiP);
            }
        }
        
    },
});
</script>

<style scoped>
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
</style>
