<template>
    <div>
        <ckeditor :editor="editor" v-model="commentText" :config="editorConfig"></ckeditor>

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
                commentText: this.text,
                editor: ClassicEditor,
                editorConfig: {
                    toolbar: ['undo', 'redo', '|', 'heading', '|', 'bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable' ],
                },
            }
        },

        computed: {
            isEditing: function() {
                return this.comment_id !== undefined;
            }
        },

        methods: {
            createComment: function() {
                if(!this.commentText.trim()) return;

                if (this.comment_id) {
                    let comment = { id: this.comment_id, user: this.user, text: this.commentText, children: [], parent_id: this.parent_id };

                    EventBus.$emit('commentEdited', comment);
                } else {
                    let comment = { user: this.user, text: this.commentText, children: [], parent_id: this.parent_id };

                    EventBus.$emit('commentCreated', comment);
                    this.clearForm();
                }
            },

            clearForm: function() {
                this.commentText = '';
            }
        }
    }
</script>