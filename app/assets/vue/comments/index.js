import Vue from "vue";
import CommentList from "./CommentList";
import EventBus from "./EventBus";

new Vue({
    el: '#comment-section',
    template: '<CommentList v-bind:comments="commentNodes" v-bind:userEmail="userEmail" v-bind:isLoaded="isLoaded"></CommentList>',
    components: {
        CommentList
    },
    data: function() {
        return {
            commentNodes: [],
            commentListUrl: '',
            commentSaveUrl: '',
            userEmail: '',
            isLoaded: false,
        }
    },

    methods: {
        fetchFromServer: function() {
            fetch(this.commentListUrl)
                .then(response => response.json())
                .then(data => this.commentNodes = data)
                .then(() => this.isLoaded = true);
        },
        saveToServer: function(comment) {
            fetch(this.commentSaveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify(comment)
            })
                .then(response => response.json())
                .then(data => EventBus.$emit('commentSaved', data))
            ;
        }
    },

    created: function() {
        let dataset = document.querySelector(this.$options.el).dataset;
        this.commentListUrl = dataset.commentListUrl;
        this.commentSaveUrl = dataset.commentSaveUrl;
        this.userEmail = dataset.userEmail;

        EventBus.$on('commentCreated', (comment) => {
            this.saveToServer(comment);
        });
    },

    mounted() {
        this.fetchFromServer();
    },
});
