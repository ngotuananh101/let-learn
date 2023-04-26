import {postService} from '../../../services/forum/post.service';
import {commentService} from '../../../services/forum/comment.service';

export default {
    namespaced: true,
    state: {
        postData: {},
    },
    mutations: {
        request(state) {
            state.postData = {};
        },
        failure(state, error) {
            state.postData = {};
        },
        setPost(state, response) {
            state.postData = response;
        },
        postAdded(state, response) {
            state.postData = response;
        }
    },
    actions: {
        getPost({commit}, page) {
            commit('request');
            postService.loadPost(page)
                .then(
                    response => {
                        commit('setPost', response);
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        addPost({commit}, post) {
            commit('request');
            postService.addPost(post)
                .then(
                    response => {
                        commit('postAdded', response);
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        updatePost({commit}, post) {
            commit('request');
            return postService.updatePost(post)
                .then(
                    response => {
                        return response;
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        getPostById({commit}, id) {
            commit('request');
            return postService.getPostById(id)
                .then(
                    response => {
                        commit('setPost', response);
                        return response;
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        deletePost({commit}, post) {
            commit('request');
            return postService.deletePost(post)
                .then(
                    response => {
                        return response;
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        likePost({commit}, data) {
            commit('request');
            return postService.likePost(data)
                .then(
                    response => {
                        return response;
                    },
                    error => {
                        commit('failure', error);
                        return null;
                    }
                );
        },
        loadComments({commit}, data) {
            commit('request');
            return commentService.loadComments(data)
                .then(
                    response => {
                        return response;
                    },
                    error => {
                        commit('failure', error);
                        return null;
                    }
                );
        },
        addComment({commit}, data) {
            commit('request');
            return commentService.addComment(data)
                .then(
                    response => {
                        return response;
                    },
                    error => {
                        commit('failure', error);
                        return null;
                    }
                );
        },
        deleteComment({commit}, data) {
            commit('request');
            return commentService.deleteComment(data)
                .then(
                    response => {
                        return response;
                    },
                    error => {
                        commit('failure', error);
                        return null;
                    }
                );
        }
    }
}
