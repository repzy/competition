<template>
    <div class="media-body mb-3" >
        <div class="mb-4 mt-3">
            <div class="mb-1"><strong>{{comment.user}}</strong></div>
            <div class="mb-1" v-html="comment.text"></div>
            <span class="text-muted" style="cursor: pointer" v-on:click="toggleHidden">Відповісти</span>
            <div v-bind:class="{ 'd-none': isHidden }">
                <CommentForm v-bind:parent_id="comment.id"></CommentForm>
            </div>
            <div v-if="hasChildren" class="ml-5">
                <CommentItem
                    v-for = "childComment in comment.children"
                    v-bind:comment = "childComment"
                    v-bind:key = "childComment.id"
                ></CommentItem>
            </div>
        </div>
    </div>
</template>

<script>
    import CommentForm from "./CommentForm";
    import EventBus from "./EventBus";

    export default {
        name: 'CommentItem',
        props: ['comment'],
        components: {
            CommentForm,
        },

        data: function() {
            return {
                isHidden: true,
            }
        },

        methods: {
            toggleHidden() {
                this.isHidden = !this.isHidden;
            }
        },

        computed: {
            hasChildren: function() {
                return this.comment.children.length > 0
            }
        },

        created: function() {
            EventBus.$on('createComment', (comment) => {
                if (comment.parent_id === this.comment.id) {
                    this.comment.children.push(comment);
                }
                this.isHidden = true;
            });
        },
    }
</script>
