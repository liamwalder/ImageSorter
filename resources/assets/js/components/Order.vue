<template>
    <div class="row images">
        <div class="image-holder">
            <div class="image-container" v-for="image in images">
                <img v-bind:src="image.path" v-on:click="selected(image)" width="25%" class="selectable-image"/>
                <div class="overlay" v-show="image.selection > 0" v-on:click="unselected(image)">
                    <span>{{ image.selection }}</span>
                </div>
            </div>
        </div>
        <div class="toolbar">
            <div class="container-fluid">
                <p v-show="orderedStrippedImages.length < images.length">Current Selection: {{ nextSelection }}</p>
                <p v-show="orderedStrippedImages.length >= images.length">All images ordered!</p>
                <div class="button-group">
                    <input type="checkbox" id="checkbox" v-model="checked" /> <label for="checkbox">Overwrite filename with order (eg. 01.jpg)</label>
                    <button type="button" class="btn btn-secondary" v-on:click="reset()">Reset</button>
                    <button type="button" class="btn btn-primary" v-on:click="finish()">Finish sorting <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data: function() {
            return {
                images: [],
                checked: false,
                nextSelection: 1,
                imageSelection: 1,
                orderedImages: [],
                keysWithNoImage: [],
                orderedStrippedImages: []
            }
        },

        computed: {
            uniqueId () {
                return this.$store.state.uniqueId
            }
        },

        created: function() {
            var vm = this;
            this.$http.get('/api/order/' + vm.uniqueId).then((response) => {
                $.each(response.data, function(key, image) {
                    vm.images.push(image);
                });
                this.$Progress.finish();
            }, (response) => {});
        },

        methods: {
            selected: function(image) {
                this.orderedImages[this.imageSelection-1] = image.name;
                this.orderedStrippedImages = this.orderedImages.filter(Boolean);
                Vue.set(image, 'selection', this.imageSelection);
                this.workOutNextSelection(true);
            },
            unselected: function(image) {
                this.orderedImages[image.selection-1] = '';
                this.orderedStrippedImages = this.orderedImages.filter(Boolean);
                this.keysWithNoImage.push(image.selection);
                Vue.set(image, 'selection', 0);
                this.workOutNextSelection(false);
            },
            workOutNextSelection(chosen) {
                if (this.keysWithNoImage.length == 0) {
                    this.nextSelection = this.orderedImages.length + 1;
                    this.imageSelection = this.orderedImages.length + 1;

                } else if (this.keysWithNoImage.length == 1) {
                    this.keysWithNoImage.sort();
                    this.nextSelection = this.keysWithNoImage[0];
                    this.imageSelection = this.keysWithNoImage[0];
                    if (chosen == true) {
                        this.keysWithNoImage.splice(0, 1);
                    }

                    this.workOutNextSelection(false);

                } else {
                    this.keysWithNoImage.sort();
                    this.nextSelection = this.keysWithNoImage[0];
                    this.imageSelection = this.keysWithNoImage[0];
                    if (chosen == true) {
                        this.keysWithNoImage.splice(0, 1);
                    }

                    this.nextSelection = this.keysWithNoImage[0];
                    this.imageSelection = this.keysWithNoImage[0];
                }

            },
            finish() {
                this.$Progress.start();
                var postData = {
                    uniqueId: this.uniqueId,
                    images: this.orderedImages,
                    rewrite: this.checked
                }

                this.$http.post('/api/order', postData).then((response) => {
                    this.$store.state.zipUrl = response.body.zip_url;
                    this.$router.push({ name: 'download' });
                    this.$Progress.finish();
                }, (response) => {});
            },
            reset() {
                this.checked = false;
                this.nextSelection = 1;
                this.imageSelection = 1;
                this.orderedImages = [];
                this.keysWithNoImage = [];
                this.orderedStrippedImages = [];
            }
        }
    }
</script>
