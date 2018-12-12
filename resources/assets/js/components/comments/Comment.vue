<template>
    <li class="media mt-4 mb-4"
        :id="`comment-${comment.id}`">
        <div class="media-body">
            <p class=" mb-2">
                <strong>{{ comment.user.name }}</strong>
                <template v-if="comment.child">replied</template>
                <timeago :since="comment.created_at.datetime" :auto-update="60">timestamp</timeago>
                <small class="text-muted" v-if="comment.edited">(Edited)</small>
            </p>

            <!-- Body -->
            <article class="mb-3">
                <template v-if="!editing">
                    <div v-html="body" v-highlightjs></div>
                </template>

                <template v-else>
                    <CommentEdit :comment="comment"/>
                </template>
            </article>

            <!-- Meta & options -->
            <ul class="list-inline" v-if="links && user.authenticated && !editing">
                <template v-if="!comment.child">
                    <li class="list-inline-item" v-if="!closed || !closing || !deleting">
                        <a href="#" @click.prevent="reply">Reply</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" @click.prevent="replies = !replies">
                            {{ repliesCount }} {{ pluralize('Reply', repliesCount) }}
                        </a>
                    </li>
                </template>

                <template v-if="comment.owner && !closed || !closing || !deleting">
                    <li class="list-inline-item">
                        <a href="#" @click.prevent="editing = true">Edit</a>
                    </li>

                    <li class="list-inline-item">
                        <a href="#" @click.prevent="destroy" v-if="!deleting">Delete</a>
                        <span v-else>Deleting...</span>
                    </li>
                </template>
            </ul>

            <template v-if="comment.children && replies">
                <template v-if="comment.children.length">
                    <ul class="list-unstyled ml-4">
                        <comment v-for="child in comment.children" :key="child.id" :comment="child"/>
                    </ul>

                    <p class="ml-4 mt-2">
                        <a href="#" @click.prevent="replies = !replies">Hide replies</a>
                    </p>
                </template>

                <template v-else>
                    <p>No replies.</p>
                </template>
            </template>
        </div>
    </li>
</template>

<script>
    import axios from 'axios'
    import Comment from './Comment'
    import CommentEdit from './CommentEdit'
    import Bus from '../../bus'
    import marked from 'marked'

    export default {
        name: "comment",
        props: {
            endpoint: {
                required: false,
                type: String
            },
            comment: {
                require: true,
                type: Object
            },
            links: {
                default: true,
                type: Boolean
            }
        },
        data() {
            return {
                editing: false,
                deleting: false,
                closing: false,
                replies: false,
                closed: null
            }
        },
        computed: {
            repliesCount() {
                if (this.comment.children) {
                    return this.comment.children.length
                }

                return 0
            },
            body() {
                return marked(this.comment.body, { sanitize: true })
            }
        },
        components: {
            Comment,
            CommentEdit
        },
        mounted() {
            Bus.$on('comment:closing', this.commentClosing)
                .$on('comment:closed', this.closeComment)
                .$on('comment:editing-cancelled', this.cancelEditing)
        },
        methods: {
            async destroy() {
                try {
                    this.deleting = true

                    let response = await axios.delete(`/comments/${this.comment.id}`)

                    toastr.success(`Comment deleted successfully.`)

                    Bus.$emit('comment:deleted', this.comment)
                } catch (e) {
                    // optional: log error to file, db...

                    toastr.danger(`Something went wrong! Failed deleting comment.`)
                } finally {
                    this.deleting = false
                }
            },
            commentClosing(comment, status) {
                if (comment.id === this.comment.id) {
                    this.closing = status
                }
            },
            closeComment(comment) {
                if (comment.id === this.comment.id) {
                    this.closed = comment.closed
                }
            },
            updateComment(comment) {
                if (comment.id === this.comment.id) {
                    this.editing = false
                }
            },
            cancelEditing(comment) {
                if (comment.id === this.comment.id) {
                    this.editing = false
                }
            },
            reply() {
                Bus.$emit('comment:reply', this.comment)
            }
        }
    }
</script>

<style scoped>

</style>