<template>
    <div>
        <div class="file-upload">
            <input type="file" @change="storeUploadedImages($event)" id="upload" class="upload" data-multiple-caption="{count} files selected" multiple>
            <label for="upload">
                <figure>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                    </svg>
                </figure>
                <span>Choose image(s)…</span>
            </label>
        </div>
        <div class="toolbar">
            <div class="container-fluid">
                <p v-if="errorMessage != null" class="error">{{ errorMessage}}</p>
                <div v-show="imageCount > 0">
                    <p>{{ imageCount }} images successfully processed</p>
                    <div class="button-group">
                        <button type="button" class="btn btn-primary" v-on:click="uploadFiles()">Sort images <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                    </div>
                </div>
                <div class="button-group">
                    <button type="button" class="btn btn-primary" v-show="imageCount < 1" disabled>Sort images <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data: function() {
            return {
                imageCount: 0,
                chosenImages: [],
                errorMessage: null
            }
        },

        methods: {
            storeUploadedImages: function(event) {
                this.errorMessage = null;
                var formData = new FormData();
                for (var i = 0; i < event.target.files.length; i++) {
                    formData.append('images['+i+']', event.target.files[i]);
                }
                this.chosenImages = formData;
                this.imageCount = event.target.files.length;
            },
            uploadFiles: function() {
                this.$Progress.start();

                this.$http.post('/api/upload', this.chosenImages).then(
                    function(data, status, request) {
                        this.$store.state.uniqueId = data.body.unique_id;
                        this.$router.push({ name: 'order' });
                    },
                    function(data, status, request) {
                        this.$Progress.fail();
                        this.imageCount = 0;
                        this.chosenImages = [];
                        this.errorMessage = data.body[0];
                    }
                );

            }
        }
    }
</script>
