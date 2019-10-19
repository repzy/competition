<template>
    <div class="mb-2">
        <CommentItem
            v-for="comment in comments"
            v-bind:comment="comment"
            v-bind:key="comment.id"
        ></CommentItem>
        <CommentForm></CommentForm>
    </div>
</template>

<script>
    import CommentItem from "./CommentItem";
    import CommentForm from "./CommentForm";
    import EventBus from "./EventBus";

    export default {
        props: ['comments'],
        components: {
            CommentItem,
            CommentForm
        },

        created: function() {
            EventBus.$on('createComment', (comment) => {
                if (comment.parent_id === undefined || comment.parent_id === null) {
                    this.comments.push(comment);
                }
            });
        },
    }
</script>
