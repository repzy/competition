import Vue from "vue";
import CommentList from "./CommentList";

new Vue({
    el: '#comment-section',
    template: '<CommentList v-bind:comments="commentNodes"></CommentList>',
    components: {
        CommentList
    },
    data: function() {
        return {
            commentNodes: [],
        }
    },

    methods: {
        fetchFromServer: function() {
            fetch('/distance/2/comments')
                .then(response => response.json())
                .then(data => this.commentNodes = data);
        },
    },

    mounted() {
        this.fetchFromServer();
    }
});
