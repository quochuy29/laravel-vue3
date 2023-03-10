<template>
    <div>
        <a-tag color="#cd201f">Date</a-tag><br/><br/>
        <a-date-picker v-model:value="time" /><br/><br/>
        <a-tag color="#cd201f">Start time to End time</a-tag><br/><br/>
        <a-time-range-picker format="HH:mm"/><br/><br/>
        <a-tag color="#87d068">Duration: 1 day</a-tag><br/><br/>
        <a-tag color="#cd201f">Approver</a-tag><br/><br/>
        <a-select placeholder="Selected" style="width: 100px">
            <a-select-option value="success">Success</a-select-option>
            <a-select-option value="warning">Warning</a-select-option>
            <a-select-option value="error">Error</a-select-option>
        </a-select><br/><br/>
        <a-tag color="#cd201f">Reason</a-tag><br/><br/>
        <a-textarea placeholder="Textarea with clear icon" allow-clear />
    </div>
</template>

<script>
    import dayjs from 'dayjs';
    export default {
        props: {
            time: [String, Object]
        },
        data() {
            return {
                timeMain: dayjs(this.time).format('YYYY-MM-DD'),
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>