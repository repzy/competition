<template>
    <div>
        <ckeditor :editor="editor" v-model="text" :config="editorConfig"></ckeditor>

        <button v-on:click="createComment" class="btn">
            <span v-if="isEditing">Редагувати</span>
            <span v-else>Додати</span>
        </button>
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

        props: ['parent_id', 'user', 'comment_id', 'text'],

        data: function() {
            return {
                text: '',
                editor: ClassicEditor,
                editorConfig: {
                    toolbar: ['undo', 'redo', '|', 'heading', '|', 'bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable' ],
                },
            }
        },

        computed: {
            isEditing: function() {
                return this.comment_id !== undefined || this.comment_id !== null;
            }
        },

        methods: {
            createComment: function() {
                if(!this.text.trim()) return;

                if (this.comment_id) {
                    let comment = { id: this.comment_id, user: this.user, text: this.text, children: [], parent_id: this.parent_id };

                    EventBus.$emit('commentEdited', comment);
                } else {
                    let comment = { user: this.user, text: this.text, children: [], parent_id: this.parent_id };

                    EventBus.$emit('commentCreated', comment);
                    this.clearForm();
                }
            },

            clearForm: function() {
                this.text = '';
            }
        }
    }
</script>