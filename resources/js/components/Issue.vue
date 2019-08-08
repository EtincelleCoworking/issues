<template>
    <div>
        <b-form @submit="onSubmit" v-if="editing">
            <b-alert v-if="updating" variant="info">Sauvegarde en cours...</b-alert>
            <b-card>
                <b-form-group label="Title" label-for="title">
                    <b-form-input v-model="issue.title"
                                  id="title" type="text" required
                                  placeholder="Enter title"
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Description" label-for="description">
                    <!--
                    <editor-menu-bar :editor="editor" v-slot="{ commands, isActive, focused }">
                        <div
                            class="menubar is-hidden"
                            :class="{ 'is-focused': focused }"
                        >

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.bold() }"
                                @click="commands.bold"
                            >
                                <icon name="bold" />
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.italic() }"
                                @click="commands.italic"
                            >
                                <icon name="italic" />
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.strike() }"
                                @click="commands.strike"
                            >
                                <icon name="strike" />
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.underline() }"
                                @click="commands.underline"
                            >
                                <icon name="underline" />
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.code() }"
                                @click="commands.code"
                            >
                                <icon name="code" />
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.paragraph() }"
                                @click="commands.paragraph"
                            >
                                <icon name="paragraph" />
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                                @click="commands.heading({ level: 1 })"
                            >
                                H1
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                                @click="commands.heading({ level: 2 })"
                            >
                                H2
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                                @click="commands.heading({ level: 3 })"
                            >
                                H3
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.bullet_list() }"
                                @click="commands.bullet_list"
                            >
                                <icon name="ul" />
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.ordered_list() }"
                                @click="commands.ordered_list"
                            >
                                <icon name="ol" />
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.blockquote() }"
                                @click="commands.blockquote"
                            >
                                <icon name="quote" />
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.code_block() }"
                                @click="commands.code_block"
                            >
                                <icon name="code" />
                            </button>

                        </div>
                    </editor-menu-bar>
                    -->
                    <editor-content :editor="editor" class="form-control" style="height: 100%"/>
                </b-form-group>

                <template slot="footer">
                    <b-button type="submit" variant="primary">Save</b-button>
                    <b-button @click="cancelEdit" variant="secondary">Cancel</b-button>
                </template>
            </b-card>
        </b-form>
        <div class="container" v-else>
            <!--
            <b-button @click="enableEdit" variant="secondary" class="float-right">Edit</b-button>
            -->
            <h1>{{issue.title}}</h1>
            <div class="row mb-2">
                <div class="col-8">
                    <b-card class="h-100">
                        <div v-html="issue.description"></div>
                    </b-card>
                </div>
                <div class="col-4">
                    <b-card header="Reporter" class="mb-1">
                        <div class="mb-2">
                            <img :src="issue.reporter.avatar" class="rounded float-right"/>
                            <small class="text-muted">Reported by</small>
                            <br />
                            {{issue.reporter.name}}
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Created</small>
                            <br />
                            {{issue.created_at | moment("dddd, MMMM Do YYYY")}}
                            <small>({{issue.created_at | moment("from")}})</small>
                        </div>
                        <div class="mb-2"v-if="issue.created_at!=issue.updated_at">
                            <small class="text-muted">Last update</small>
                            <br />
                            {{issue.updated_at | moment("dddd, MMMM Do YYYY")}}
                            <small>({{issue.updated_at | moment("from")}})</small>
                        </div>
                    </b-card>
                </div>
                <!--
                <div class="col-4">
                    <b-card header="Workflow" class="mb-1">
                        <ul>
                            <li>
                                <a href="#">Take responsibility</a>
                            </li>
                        </ul>
                    </b-card>
                </div>
                -->
            </div>
            <div class="row" v-if="issue.id > 0">
                <div class="col-12">
                    <b-card header="Comments">
                        <div v-for="comment in issue.comments" v-if="issue.comments.length > 0" class="row">
                            <template v-if="isRequester(comment)">
                                <div class="col-2"></div>
                                <div class="col-10">
                                    <b-card class="mb-2 comment"
                                            :class="{'text-right': isRequester(comment)}"
                                            bg-variant="info" text-variant="white"
                                            footer-text-variant="text"
                                    >
                                        <span v-html="comment.content"></span>
                                        <template slot="footer">
                                            <small class="text-muted">

                                                Posted by {{comment.reporter.name}}
                                                on {{comment.created_at | moment("MMMM Do YYYY")}}
                                                ({{comment.created_at | moment("from")}})
                                                <span v-if="comment.created_at!=comment.updated_at">
                                                    , last updated
                                                    <br/>on {{comment.updated_at | moment("MMMM Do YYYY")}}
                                                    ({{comment.updated_at | moment("from")}})
                                                </span>
                                                <img :src="comment.reporter.avatar" class="rounded"/>
                                            </small>
                                        </template>
                                    </b-card>
                                </div>
                            </template>
                            <template v-else>
                                <div class="col-10">
                                    <b-card class="mb-2 comment">
                                        <span v-html="comment.content"></span>
                                        <template slot="footer">
                                            <small class="text-muted">
                                                <img :src="comment.reporter.avatar" class="rounded mr-1"/>
                                                Posted by {{comment.reporter.name}}
                                                on {{comment.created_at | moment("MMMM Do YYYY")}}
                                                ({{comment.created_at | moment("from")}})
                                                <span v-if="comment.created_at!=comment.updated_at">
                                                    , last updated
                                                    on {{comment.updated_at | moment("MMMM Do YYYY")}}
                                                    ({{comment.updated_at | moment("from")}})
                                                </span>
                                            </small>
                                        </template>
                                    </b-card>
                                </div>
                            </template>

                        </div>
                        <p v-else>
                            No comments.
                        </p>
                        <b-form @submit="addComment">
                            <b-card header="Add a comment" class="mt-3">
                                <editor-content :editor="comment_editor" class="form-control" style="height: 100%"/>
                                <template slot="footer">
                                    <b-button type="submit" variant="primary">Save</b-button>
                                </template>
                            </b-card>
                        </b-form>
                    </b-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueTrix from "vue-trix";
    import {Editor, EditorMenuBar, EditorContent} from 'tiptap';
    import {
        Blockquote, Bold, BulletList, Code, CodeBlock, CodeBlockHighlight, HardBreak, Heading, History,
        HorizontalRule, Italic, Image, Link, ListItem, Mention, OrderedList, Table, TableHeader, TableCell,
        TableRow, TodoItem, TodoList, Strike, Underline
    } from 'tiptap-extensions';


    export default {
        props: ['issue', 'current_user'],
        mounted() {
            //console.log('Issue component mounted.')
            if (this.issue.id == 0) {
                this.editing = true;
            }
            this.editor.setContent(this.issue.description);
        },
        methods: {
            onSubmit(event) {
                event.preventDefault();
                this.updating = true;
                var _this = this;
                axios.post('/api/issues?api_token=' + this.current_user.api_token, this.issue)
                    .then(function (response) {
                        _this.updating = false;
                        _this.editing = false;
                        this.issue = response.data;
                        window.location.href = '/issue/' + this.issue.id;
                    })
                    .catch(function (error) {
                        console.log(error);
                        _this.updating = false;
                    });
                return false;
            },
            addComment(event) {
                event.preventDefault();
                //this.updating = true;
                var _this = this;
                axios.post('/api/issue/' + this.issue.id + '/comments?api_token=' + this.current_user.api_token, {content: this.comment})
                    .then(function (response) {
                        //_this.updating = false;
                        _this.issue.comments = response.data.comments;
                        _this.comment = '<p>&nbsp;</p>';
                    })
                    .catch(function (error) {
                        console.log(error);
                        //      _this.updating = false;
                    });
                return false;
            },
            enableEdit() {
                this.editing = true;
            },
            cancelEdit() {
                this.editing = false;
            },
            isRequester(comment) {
                return (this.issue.reporter.is_customer === comment.reporter.is_customer);
            }
        },
        components: {
            //  VueTrix,
            Editor, EditorMenuBar, EditorContent
        },
        data() {
            return {
                updating: false,
                editing: false,
                comment: '',
                editor: new Editor({
                    onUpdate: ({getHTML}) => {
                        this.issue.description = getHTML()
                    },
                    content: '<p>&nbsp;</p>',
                    extensions: [
                        new Blockquote(),
                        new Bold(),
                        new BulletList(),
                        new Code(),
                        new CodeBlock(),
                        new CodeBlockHighlight(),
                        //new Collaboration(),
                        new HardBreak(),
                        new Heading({
                            levels: [1, 2, 3],
                        }),
                        new History(),
                        new HorizontalRule(),
                        new Italic(),
                        new Image(),
                        new Link(),
                        new ListItem(),
                        new Mention(),
                        new OrderedList(),
                        new Table(),
                        new TableHeader(),
                        new TableCell(),
                        new TableRow(),
                        new TodoItem(),
                        new TodoList(),
                        new Strike(),
                        new Underline()
                    ]
                }),
                comment_editor: new Editor({
                    onUpdate: ({getHTML}) => {
                        this.comment = getHTML()
                    },
                    content: '<p>&nbsp;</p>',
                    extensions: [
                        new Blockquote(),
                        new Bold(),
                        new BulletList(),
                        new Code(),
                        new CodeBlock(),
                        new CodeBlockHighlight(),
                        //new Collaboration(),
                        new HardBreak(),
                        new Heading({
                            levels: [1, 2, 3],
                        }),
                        new History(),
                        new HorizontalRule(),
                        new Italic(),
                        new Image(),
                        new Link(),
                        new ListItem(),
                        new Mention(),
                        new OrderedList(),
                        new Table(),
                        new TableHeader(),
                        new TableCell(),
                        new TableRow(),
                        new TodoItem(),
                        new TodoList(),
                        new Strike(),
                        new Underline()
                    ]
                })
            }
        },
        beforeDestroy() {
            // Always destroy your editor instance when it's no longer needed
            this.editor.destroy()
            this.comment_editor.destroy()
        }
    }
</script>

<style>
    .comment img {
        max-width: 100%;
    }

    .comment .card-footer {
        padding: 0.25rem 0.25rem;
    }
</style>
