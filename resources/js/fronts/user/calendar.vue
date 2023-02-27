<template>
    <a-modal
        v-model:visible="modal2Visible"
        title="Vertically centered modal dialog"
        centered
        @ok="add">
        <abcssss ref="data" :time="time" :key="modal2Visible"></abcssss>
    </a-modal>
    <a-calendar v-model:value="value" @select="dua(value)" :key="modal2Visible">
        <template #dateCellRender="{ current }">
          <ul class="events">
            <li v-for="item in getListData(current.format('YYYY-MM-DD').toString())" :key="item.content">
              <a-badge :status="item.type" :text="item.content" />
            </li>
          </ul>
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
            title: [],
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
            document.querySelector('.ant-radio-button-wrapper:not(.ant-radio-button-wrapper-checked)').remove();
            await getDataCalendar();
        });

        const getListData = (value) => {
            let listData;
            listData = data.value[value];
            
            return listData || [];
        };

        return {
            value,
            getListData,
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
                title: this.$refs.data.titles
            }
            const dataiP = JSON.parse(JSON.stringify(dataIp));
            const response = await axios.post('api/add', dataiP);

            if (response.status == 200) {
                this.modal2Visible = false;
                await this.getDataCalendar();
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