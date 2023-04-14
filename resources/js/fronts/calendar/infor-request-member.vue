<template>
    <div class="infor-request-member" style="width:25%;">
        <a-card style="height: 50%;">
            <a-skeleton active :loading="loading" :paragraph="{ rows: 4 }">
                <p>{{ member.name }}</p>
                <p>{{ member.email }} - D1</p>
                <p>Member since: {{ member.since }}</p>
                <p>Annual Leave: {{ member.quotas }} days (Up to 26/3)</p>
                <p>Overtime Leave: {{ member.duration }} days (Up to 26/3)</p>
                <p>Requested Leave: {{ member.requested_leave - 5 }} day (In month)</p>
                <p>Unrequested Leave: {{ member.unrequested_leave }} days (In month)</p>
            </a-skeleton>
        </a-card>
        <a-card style="height: 50%;">
            <p>Request From My Member</p>
        </a-card>
    </div>
</template>

<script>
    import { defineComponent, onBeforeMount, ref } from "vue";
    import axios from "axios";

    export default defineComponent({
        setup() {
            const member = ref({});
            const loading = ref(true);
            const getMemberRequest = async () => {
                const res = await axios.get('api/infor');
                member.value = res.data;
                loading.value = false;
            };

            onBeforeMount(() => {
                getMemberRequest();
            });

            return {
                member, 
                loading
            };
        }
    });
</script>

<style lang="less" scoped>
.infor-request-member {
    text-align: left;
    background: #fff;
}
.ant-card-body {
    p {
        word-wrap: break-word;
    }
}
</style>