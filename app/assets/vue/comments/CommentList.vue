<template>
    <div class="mb-2">
        <CommentItem
            v-for="comment in comments"
            v-bind:comment="comment"
            v-bind:key="comment.id"
            v-bind:userEmail="userEmail"
        ></CommentItem>
        <CommentForm v-if="isUser"></CommentForm>
    </div>
</template>

<script>
    import CommentItem from "./CommentItem";
    import CommentForm from "./CommentForm";
    import EventBus from "./EventBus";

    export default {
        props: ['comments', 'userEmail'],
        components: {
            CommentItem,
            CommentForm
        },

        computed: {
            isUser: function () {
                return this.userEmail !== '';
            }
        },

        created: function() {
            EventBus.$on('commentSaved', (comment) => {
                if (comment.parent_id === undefined || comment.parent_id === null) {
                    this.comments.push(comment);
                }
            });
        },
    }
</script>
