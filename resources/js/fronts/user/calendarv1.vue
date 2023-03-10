<template>
  <a-modal
    v-model:visible="showOvl"
    :title="selectedDate"
    :confirm-loading="confirmLoading"
    @cancel="handleInput()"
    @ok="handleOk(selectedDate)"
  >
    <a-select v-model:value="type" style="width: 100%;">
      <a-select-option value="error" selected>error</a-select-option>
      <a-select-option value="warning">warning</a-select-option>
      <a-select-option value="success">success</a-select-option>
    </a-select>
    <a-input v-model:value="content" placeholder="new" />
  </a-modal>
  <a-calendar
    v-model:value="value"
    :key="reRenderCal"
    :valueFormat="'YYYY-MM-DD'"
    @select="dmHuy(value)"
  >
    <template #dateCellRender="{ current }">
      <ul class="events" :class="current.format('YYYY-MM-DD').toString()">
        <li v-for="item in monthData[current.format('YYYY-MM-DD').toString()]" :key="item.id">
          <a-badge :status="item.type" :text="item.content" />
        </li>
      </ul>
    </template>
  </a-calendar>
</template>
<script>
import { defineComponent, ref, onMounted } from 'vue';
export default {
  setup() {
    const monthData = ref({});
    const value = null;
    const getDataCalendar = async () => {
      const res = await axios.get("api/calendar");
      monthData.value = res.data;
    };
    getDataCalendar()
    return {
      monthData,
      value,
    };
  },
  data() {
    return {
      showOvl: false,
      confirmLoading: false,
      selectedDate: "",
      type: "",
      content: "",
      reRenderCal: false
    };
  },
  async mounted() {
  },
  methods: {
    dmHuy: function(v) {
      if (v === undefined) {
        v = Date.now()
          .format("YYYY-MM-DD")
          .toString();
      }
      this.selectedDate = v;
      this.showOvl = true;
    },
    handleOk: function(date) {
      if (this.type === "" || this.content === "") {
        alert("empty field(s)");
        return;
      }

      if (this.monthData[date] === undefined) {
        this.monthData[date] = [];
      }
      this.monthData[date] = [
        { type: this.type, content: this.content },
        ...this.monthData[date]
      ];
      this.showOvl = false;
      this.reRenderCal = !this.reRenderCal;
      this.type = this.content = "";
    },
    handleInput: function() {
      this.type = this.content = "";
    }
  }
};
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
</style>