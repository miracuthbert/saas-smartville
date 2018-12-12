<template>
    <div>
        <template v-if="reply">
            <Reply :comment="reply"/>
        </template>

        <template v-else>
            <h3 class="mb-2">{{ meta.total }} {{ pluralize('Comment', meta.total) }}</h3>

            <NewComment class="mb-3" :endpoint="endpoint" v-if="user.authenticated"/>

            <template v-if="comments.length">
                <ul class="list-unstyled">
                    <Comment v-for="comment in comments"
                             :comment="comment"
                             :endpoint="endpoint"
                             :key="comment.id"/>
                </ul>
            </template>

            <p v-else>
                No comments<span v-if="!meta.total"> posted yet</span>.
            </p>

            <a href="#"
               class="btn btn-light btn-block shadow-none"
               @click.prevent="loadMore"
               v-if="meta.current_page < meta.last_page">
                Show more
            </a>
        </template>
    </div>
</template>

<script>
    import axios from 'axios'
    import Comment from './Comment'
    import NewComment from './NewComment'
    import Reply from './CommentReply'
    import Bus from '../../bus'

    export default {
        name: "comments",
        props: {
            endpoint: {
                required: true,
                type: String
            }
        },
        data() {
            return {
                comments: [],
                meta: {},
                reply: null
            }
        },
        components: {
            Comment,
            NewComment,
            Reply
        },
        mounted() {
            this.loadComments(1)

            Bus.$on('comment:stored', this.prependComment)
                .$on('comment:edited', this.editComment)
                .$on('comment:deleted', this.removeComment)
                .$on('comment:reply', this.setReplying)
                .$on('comment:reply-cancelled', this.cancelReplying)
                .$on('comment:replied', this.appendReply)
        },
        methods: {
            fetchComments(page = 1) {
                return axios.get(`${this.endpoint}?page=${page}`)
            },
            async loadComments(page = 1) {
                let response = await this.fetchComments(page)

                this.comments = response.data.data
                this.meta = response.data.meta
            },
            async fetchMeta() {
                let response = await this.fetchComments(this.meta.current_page)

                this.meta = response.data.meta
            },
            async loadMore() {
                let response = await this.fetchComments(this.meta.current_page + 1)

                this.comments.push(...response.data.data)
                this.meta = response.data.meta
            },
            async loadOneAfterDeletion() {
                if (this.comments.length === 0 && this.meta.last_page < this.meta.current_page) {
                    await this.loadComments(this.meta.last_page)

                    return
                }

                if (this.meta.current_page >= this.meta.last_page) {
                    return
                }

                let response = await this.fetchComments(this.meta.current_page)

                this.comments.push(response.data.data[response.data.data.length - 1])
                this.meta = response.data.meta

                Bus.$emit('comments:loaded', this.meta.total)
            },
            async prependComment(comment) {
                this.comments.unshift(comment)

                await this.fetchMeta()

                this.scrollToComment(comment)

                if(this.meta.current_page < this.meta.last_page) {
                    this.comments.pop()
                }
            },
            scrollToComment(comment) {
                setTimeout(() => {
                    VueScrollTo.scrollTo(`#comment-${comment.id}`, 500)
                }, 100)
            },
            editComment(comment) {
                if (comment.child) {
                    _.assign(
                        _.find(
                            this.comments,
                            {id: comment.parent_id}).children.find((child) => child.id == comment.id
                        ), comment
                    )

                    return
                }

                _.assign(_.find(this.comments, {id: comment.id}), comment)
            },
            removeComment(comment) {
                if (comment.child) {
                    let parentComment = _.find(this.comments, {id: comment.parent_id})

                    parentComment.children = parentComment.children.filter((child) => child.id !== comment.id)

                    return
                }

                this.comments = this.comments.filter((node) => node.id !== comment.id)

                this.meta.total--

                Bus.$emit('comment:removed', this.meta.total)

                this.loadOneAfterDeletion()
            },
            setReplying(comment) {
                this.reply = comment
            },
            cancelReplying() {
                this.reply = null
            },
            appendReply({comment, reply}) {
                _.find(this.comments, {id: comment.id}).children.push(reply)

                this.scrollToComment(comment)
            }
        }
    }
</script>

<style scoped>

</style>