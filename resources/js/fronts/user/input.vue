<template>
    <a-button @click="addTitle">+</a-button>
    <a-date-picker v-model:value="date" style="margin-left:5px;" />
    <div v-for="(title, index) in titles" :maxid="index" class="list-schedule">
        <br/><a-input-group compact>
            <a-select v-model:value="title.type" style="width: 100px">
                <a-select-option value="success">Success</a-select-option>
                <a-select-option value="warning">Warning</a-select-option>
                <a-select-option value="error">Error</a-select-option>
            </a-select>
            <a-input v-model:value="title.content" style="width: 50%" />
            <a-button @click="removeTitle(index)">-</a-button>
        </a-input-group><br/>
    </div>
</template>

<script>
    import dayjs from 'dayjs';
    export default {
        props: {
            time: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                date: dayjs(this.time),
                titles: [
                    {
                        type: '', 
                        content: ''
                    }
                ]
            }
        },
        methods: {
            addTitle() {
                this.titles.push({type: '', content: ''});
            },
            removeTitle(maxId) {
                const elLength = document.querySelectorAll('.list-schedule').length;
                if (elLength > 1) {
                    document.querySelector(`[maxid="${maxId}"]`).remove();
                }
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>