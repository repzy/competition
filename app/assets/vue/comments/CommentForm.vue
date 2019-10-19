<template>
    <div>
        <editor
                v-model="text"
                api-key="no-api-key"
                :init="{
                    height: 200,
                    menubar: false,
                    plugins: [
                        'table'
                    ],
                    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | fontsizeselect'
                }"
        ></editor>
        <button v-on:click="createComment">Add</button>
    </div>
</template>

<script>
    import EventBus from "./EventBus";
    import Editor from '@tinymce/tinymce-vue';

    export default {
        components: {
            'editor': Editor
        },

        props: ['parent_id'],

        data: function() {
            return {
                id: Math.floor(Math.random() * 100),
                user: 'dmytro.kuzmenko@bigcommerce.com',
                text: '',
                children: []
            }
        },

        methods: {
            createComment: function() {
                if(!this.text.trim()) return;

                let comment = { id: this.id, user: this.user, text: this.text, children: this.children, parent_id: this.parent_id };

                EventBus.$emit('createComment', comment);
                this.clearForm();
            },

            clearForm: function() {
                this.text = '';
            }
        }
    }
</script>