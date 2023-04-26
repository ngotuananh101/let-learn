import {postService} from '../../../services/class/post.service';

export default {
    namespaced: true,
    state: {
        posts: [],
    },
    mutations: {
        request(state) {
            state.status = {};
        },
        failure(state, error) {
            state.status = {};
        },
        setPosts(state, posts) {
            state.posts = posts;
        },
        commentAdded(state) {},
        commentLoaded(state, comments) {},
        postAdded(state) {},
        commentDeleted(state) {},
        setMorePost(state, posts) {
            state.posts.posts = state.posts.posts.concat(posts.posts);
        },
        postDeleted(state) {},
        postUpdated(state) {}
    },
    actions: {
        getPostsByClassId({commit}, classId) {
            commit('request');
            postService.getPostsByClassId(classId)
                .then(
                    posts => {
                        commit('setPosts', posts);
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        addComment({commit}, data) {
            commit('request');
            postService.addComment(data)
                .then(
                    post => {
                        commit('commentAdded');
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        loadComments({commit}, data) {
            commit('request');
            postService.loadComments(data)
                .then(
                    comments => {
                        commit('commentLoaded', comments);
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        addPost({commit}, data) {
            commit('request');
            postService.addPost(data)
                .then(
                    post => {
                        commit('postAdded');
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        deleteComment({commit}, data) {
            commit('request');
            postService.deleteComment(data)
                .then(
                    post => {
                        commit('commentDeleted');
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        loadMorePost({commit}, data) {
            commit('request');
            postService.loadMorePost(data)
                .then(
                    posts => {
                        commit('setMorePost', posts);
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        deletePost({commit}, data) {
            commit('request');
            postService.deletePost(data)
                .then(
                    post => {
                        commit('postDeleted');
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        updatePost({commit}, data) {
            commit('request');
            postService.updatePost(data)
                .then(
                    post => {
                        commit('postUpdated');
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        }
    }
}
