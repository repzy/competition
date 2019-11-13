<template>
    <div v-if="!isLoaded">
        <div class="text-center">
            <i class="fas fa-spinner fa-pulse fa-3x"></i>
        </div>
    </div>
    <div v-else class="mb-2">
        <CommentItem
            v-for="comment in comments"
            v-bind:comment="comment"
            v-bind:key="comment.id"
            v-bind:userEmail="userEmail"
        ></CommentItem>
        <div v-if="isUser">
            <CommentForm></CommentForm>
        </div>
        <div v-else-if="!hasComments" class="text-center text-muted">
            Не додано жодного коментаря.
        </div>
    </div>
</template>

<script>
    import CommentItem from "./CommentItem";
    import CommentForm from "./CommentForm";
    import EventBus from "./EventBus";

    export default {
        props: ['comments', 'userEmail', 'isLoaded'],
        components: {
            CommentItem,
            CommentForm
        },

        computed: {
            isUser: function () {
                return this.userEmail !== '';
            },
            hasComments: function() {
                return this.comments.length > 0;
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
