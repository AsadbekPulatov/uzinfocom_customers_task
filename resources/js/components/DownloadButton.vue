<template>
    <button @click="onExport">Download</button>
</template>

<script>
import axios from "axios";

export default {
    name: "DownloadButton",
    data(){
        return {
            link: '',
        }
    },
    methods: {
        onExport() {
            axios.get('/download')
                .then(res => {
                    console.log(res)
                })
        }
    },
    mounted() {
        Echo.channel('export')
            .listen('ExportCustomerEvent', (response) => {
                alert('finished')
                this.link = response.link
                window.location.href = response.link
            })
    }
}
</script>

<style scoped>

</style>
