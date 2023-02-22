<template>
    <abcssss v-if="isOpen" @handleData="handleData" ref="data"></abcssss>
    <a-calendar v-model:value="value" @click="dua(value)">
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
import { Dayjs } from 'dayjs';
import axios from 'axios';

export default defineComponent({
    components: {
        abcssss
    },
    data() {
        return {
            isOpen: false,
            data: null,
            time: null
        }
    },
    setup() {
        const value = ref();
        const data = ref([]);
        onMounted(async () => {
            const datas = await axios.get('api/calendar');
            data.value = datas.data;
        });
        const getListData = value => {
            let listData;
            data.value.forEach((date) => {
                if (value.format('YYYY-MM-DD') == date.date) {
                    listData = JSON.parse(date.title);
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
            getMonthData
        };
    },
    methods: {
        dua(vl) {
            console.log(this.getListData(vl));
            this.time = vl;
            this.isOpen = true;
        },
        async handleData() {
            const dataIp = {
                date: this.time.format('YYYY-MM-DD'),
                title: this.$refs.data.dataInput
            }

            const response = await axios.post('api/add', dataIp);
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
