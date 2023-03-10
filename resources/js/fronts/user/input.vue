<template>
    <a-button @click="addTitle">+</a-button>
    <div v-if="dataId == null" v-for="(title, index) in titles" :maxid="index" class="list-schedule">
        <br/><a-date-picker v-model:value="title.date" /><br/>
        <br/><a-input-group compact>
            <a-select v-model:value="title.type" style="width: 100px">
                <a-select-option value="success">Success</a-select-option>
                <a-select-option value="warning">Warning</a-select-option>
                <a-select-option value="error">Error</a-select-option>
            </a-select>
            <a-input v-model:value="title.content" style="width: 50%;height:32px;" />
            <a-button @click="removeTitle(index)">-</a-button>
        </a-input-group><br/>
        <a-alert :id="`error_${index}`" class="off" message="Error" type="error" show-icon />
    </div>
    <div v-else v-for="(title, index) in data" :maxid="index" class="list-schedule">
        <br/><a-date-picker v-model:value="title.date" /><br/>
        <br/><a-input-group compact>
            <a-select v-model:value="title.type" style="width: 100px">
                <a-select-option value="success">Success</a-select-option>
                <a-select-option value="warning">Warning</a-select-option>
                <a-select-option value="error">Error</a-select-option>
            </a-select>
            <a-input v-model:value="title.content" style="width: 50%;height:32px;" />
            <a-button @click="removeTitle(index)">-</a-button>
        </a-input-group><br/>
        <a-alert :id="`error_${index}`" class="off" message="Error" type="error" show-icon />
    </div>
</template>

<script>
    import dayjs from 'dayjs';
    import _ from 'lodash';
    export default {
        props: {
            time: [String, Object],
            dataId: [Object, Array]
        },
        data() {
            return {
                timeMain: dayjs(this.time).format('YYYY-MM-DD'),
                titles: [
                    {
                        date: dayjs(this.time),
                        type: '', 
                        content: ''
                    },
                ],
                data: this.dataId
            }
        },
        methods: {
            handleErrors(errors) {
                if (errors === undefined) {
                    return false;
                }
                let errs = JSON.parse(errors.title);
                let classErr = [];
                let classAllErr = [];
                for (const err in errs) {
                    classErr.push(err);
                    document.querySelector(`#error_${err}`).classList.remove('off');
                    document.querySelector(`#error_${err}`).childNodes[1].children[0].innerHTML = this.getMessage(Object.values(errs[err]));
                }

                document.querySelectorAll('.list-schedule').forEach(el => {
                    classAllErr.push(el.attributes[0].value);
                })
                
                let difference = classAllErr.filter(x => !classErr.includes(x));

                for (const err of difference) {
                    document.querySelector(`#error_${err}`).classList.add('off');
                }
            },
            addTitle() {
                if (this.data !== null) {
                    this.data.push({date: '', type: '', content: ''});
                    return true;
                }
                this.titles.push({date: '', type: '', content: ''});
            },
            removeTitle(maxId) {
                const elLength = document.querySelectorAll('.list-schedule').length;
                if (elLength > 1) {
                    if (this.data !== null) {
                        this.data = this.data.filter((item, key) => key !== maxId)
                        return true;
                    }
                    this.titles = this.titles.filter((item, key) => key !== maxId)
                }
            },
            getMessage(message) {
                let c = '';
                for (const b of message) {
                    c += `${b} <br/> `;
                }

                return c.replace(/\<br\/>$/, '');
            }
        },
    }
</script>

<style lang="css">
.off {
    display: none;
}
</style>