<template>
    <div>
        <ckeditor :editor="editor" v-model="text" :config="editorConfig"></ckeditor>

        <button v-on:click="createComment" class="btn">Add</button>
    </div>
</template>

<script>
    import EventBus from "./EventBus";
    import CKEditor from '@ckeditor/ckeditor5-vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        components: {
            'ckeditor': CKEditor.component,
        },

        props: ['parent_id', 'user'],

        data: function() {
            return {
                text: '',
                editor: ClassicEditor,
                editorConfig: {
                    toolbar: ['undo', 'redo', '|', 'heading', '|', 'bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable' ],
                },
            }
        },

        methods: {
            createComment: function() {
                if(!this.text.trim()) return;

                let comment = { user: this.user, text: this.text, children: [], parent_id: this.parent_id };

                EventBus.$emit('commentCreated', comment);
                this.clearForm();
            },

            clearForm: function() {
                this.text = '';
            }
        }
    }
</script>