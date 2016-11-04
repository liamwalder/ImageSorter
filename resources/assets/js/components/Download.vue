<template>
    <div class="download">
        <h1 v-if="downloaded">Files have been sorted and downloaded. Thank-you!</h1>
        <a v-bind:href="zipUrl" v-on:click="downloadZip()" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i> Download Ordered Files</a>
    </div>
</template>

<script>
    export default {

        data: function() {
            return {
                downloaded: false
            }
        },

        computed: {
            zipUrl () {
                return this.$store.state.zipUrl;
            },
        },

        methods: {
            downloadZip: function() {
                this.downloaded = true;
            }
        }
    }
</script>
