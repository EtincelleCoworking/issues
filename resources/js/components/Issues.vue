<template>
    <b-card no-body>
        <b-tabs card>
            <b-tab title="Open issues" active>
                <b-card-text v-if="issues.length === 0">No Issues</b-card-text>
                <table class="table table-striped table-bordered" v-else>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="issue in issues">
                        <td>{{issue.id}}</td>
                        <td><a :href="'/issue/' + issue.id">{{issue.title}}</a></td>
                        <td>
                            <img :src="issue.reporter.avatar" class="rounded" :title="issue.reporter.name"/>
                        </td>
                        <td>{{issue.status}}</td>
                        <td>{{issue.created_at | moment("dddd, MMMM Do YYYY")}}</td>
                        <td>{{issue.updated_at | moment("dddd, MMMM Do YYYY")}}</td>
                    </tr>
                    </tbody>
                </table>
            </b-tab>
            <b-tab title="Closed issues">
                <b-card-text>No Issues</b-card-text>
            </b-tab>
        </b-tabs>


    </b-card>
</template>

<script>
    export default {
        props: ['issues'],
        mounted() {
            console.log('Issues component mounted.')
        },
        data() {
            return {}
        },
        beforeDestroy() {
        },
    }
</script>
