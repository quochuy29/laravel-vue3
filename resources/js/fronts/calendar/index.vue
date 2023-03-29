<template>
    <div>
        <a-button>Add</a-button>
        <button @click="test">dop</button>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        mounted() {
        },
        methods: {
            async test() {
                const a = await axios.get('api/test/abc');
                console.log(a, 2);
            }
        }
    }
</script>

<style lang="css" scoped>

</style>