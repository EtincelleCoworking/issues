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
                    <VueTrix v-model="issue.description" placeholder="Enter content"
                             inputId="description"/>
                </b-form-group>

                <template slot="footer">
                    <b-button type="submit" variant="primary">Save</b-button>
                    <b-button @click="cancelEdit" variant="secondary">Cancel</b-button>
                </template>
            </b-card>
        </b-form>
        <div class="container" v-else>
            <b-button @click="enableEdit" variant="secondary" class="float-right">Edit</b-button>

            <h1>{{issue.title}}</h1>
            <div class="text-muted">Reported by {{issue.reporter.name}}
                on {{issue.created_at | moment("dddd, MMMM Do YYYY")}}<span
                    v-if="issue.created_at!=issue.updated_at">, last updated
                    on {{issue.updated_at | moment("dddd, MMMM Do YYYY")}}</span>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <b-card class="h-100">
                        <div v-html="issue.description"></div>
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
                    <div v-for="comment in issue.comments" v-if="issue.comments.length > 0">
                        <b-card class="mb-1">
                            <span v-html="comment.content"></span>
                            <template slot="footer">
                                <small class="text-muted">
                                    Posted by {{comment.reporter.name}}
                                    on {{comment.created_at | moment("MMMM Do YYYY")}} ({{comment.created_at | moment("from")}})
                                    <span v-if="comment.created_at!=comment.updated_at">, last updated
                                        on {{comment.updated_at | moment("MMMM Do YYYY")}} ({{comment.created_at | moment("from")}})</span>
                                </small>
                            </template>
                        </b-card>
                    </div>
                    <b-card header="Comments" v-else>
                        No comments.
                    </b-card>
                    <b-card header="Add a comment" class="mt-3">
                        <b-form @submit="addComment">
                            <b-form-textarea v-model="comment" placeholder="Enter something..."
                                             rows="3" max-rows="6"></b-form-textarea>
                            <!--
                            <VueTrix v-model="comment" placeholder="Enter content"/>
                            <editor-content :editor="comment_editor" class="form-control" />
                            -->
                        </b-form>
                        <template slot="footer">
                            <b-button type="submit" variant="primary">Save</b-button>
                        </template>
                    </b-card>


                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueTrix from "vue-trix";
    import {Editor, EditorContent} from 'tiptap';
    import {
        Blockquote, Bold, BulletList, Code, CodeBlock, CodeBlockHighlight, HardBreak, Heading, History,
        HorizontalRule, Italic, Link, ListItem, Mention, OrderedList, Table, TableHeader, TableCell,
        TableRow, TodoItem, TodoList, Strike, Underline
    } from 'tiptap-extensions';


    export default {
        props: ['issue'],
        mounted() {
            //console.log('Issue component mounted.')
            if (this.issue.id == 0) {
                this.editing = true;
            }
        },
        methods: {
            onSubmit(event) {
                event.preventDefault();
                this.updating = true;
                var _this = this;
                axios.post('/api/issues?api_token=' + app.api_token, this.issue)
                    .then(function (response) {
                        _this.updating = false;
                        _this.editing = false;
                        this.issue = response.data;
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
                axios.post('/api/issue/' + this.issue.id + '/comments?api_token=' + app.api_token, {content: this.comment})
                    .then(function (response) {
                        //_this.updating = false;
                        this.issue.comments = response.data.comments;
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
            }
        },
        components: {
            VueTrix,
            Editor, EditorContent
        },
        data() {
            return {
                updating: false,
                editing: false,
                comment: '',
                comment_editor: new Editor({
                    content: '',
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
            }
        },
        beforeDestroy() {
            // Always destroy your editor instance when it's no longer needed
            this.comment_editor.destroy()
        }
    }
</script>
