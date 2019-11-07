<template>
    <div class="media-body mb-3" >
        <div class="mb-4 mt-3">
            <div class="row align-items-center mb-1">
                <div class="col-auto">
                    <i class="far fa-id-badge"></i>
                </div>
                <strong>{{comment.user}}</strong>
            </div>

            <div v-if="isEditing">
                <CommentForm
                    v-bind:parent_id = "comment.id"
                    v-bind:comment_id = "comment.id"
                    v-bind:text = "comment.text"
                    v-bind:user = "comment.user"
                ></CommentForm>
            </div>
            <div v-else>
                <div class="mb-1" v-html="comment.text"></div>
            </div>

            <div v-if="isUser">
                <span class="text-muted" style="cursor: pointer" v-on:click="toggleHidden">Відповісти</span>
                <span class="text-muted" style="cursor: pointer" v-on:click="toggleEdit" v-if="isOwner">Редагувати</span>
                <div v-bind:class="{ 'd-none': isHidden }">
                    <CommentForm v-bind:parent_id="comment.id"></CommentForm>
                </div>
            </div>
            <div v-if="hasChildren" class="ml-5">
                <CommentItem
                    v-for = "childComment in comment.children"
                    v-bind:comment = "childComment"
                    v-bind:key = "childComment.id"
                    v-bind:userEmail="userEmail"
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
        props: ['comment', 'userEmail'],
        components: {
            CommentForm,
        },

        data: function() {
            return {
                isHidden: true,
                isEditing: false,
            }
        },

        methods: {
            toggleHidden() {
                this.isHidden = !this.isHidden;
            },
            toggleEdit() {
                this.isEditing = !this.isEditing;
            }
        },

        computed: {
            hasChildren: function() {
                return this.comment.children.length > 0
            },
            isUser: function () {
                return this.userEmail !== '';
            },
            isOwner: function () {
                return this.userEmail === this.comment.user;
            }
        },

        created: function() {
            EventBus.$on('commentSaved', (comment) => {
                if (comment.parent_id === this.comment.id) {
                    this.comment.children.push(comment);
                }
                this.isHidden = true;
            });
            EventBus.$on('commentUpdated', (comment) => {
                if (this.comment.id === comment.id) {
                    this.comment.text = comment.text;
                }
                this.isEditing = false;
            });
        },
    }
</script>
