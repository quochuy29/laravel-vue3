<template>
    <div>
        aaaaaa
        <input type="text" name="title" class="title" id="title" v-model="dataInput">
        <button class="btn btn-success" @click="handleData" type="button"><span>Yup</span></button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                dataInput: null
            }
        },
        methods: {
            handleData() {
                this.$emit('handleData');
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>