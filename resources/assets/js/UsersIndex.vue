<template>
    <div class="users">
        <div class="loading" v-if="loading">
            <p>Loading...</p>
        </div>

        <div class="error" v-if="error">
            {{error}}
            <button @click="fetchData">Try Again</button>
        </div>

        <ul v-if="users">
            <li v-for="{name, email, email_verified_at} in users">
                <strong>Name: </strong> {{name}}
                <strong>E-Mail: </strong> {{email}}
<!--                <strong>Verified: </strong> {{email_verified_at}}-->
            </li>
        </ul>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            loading: false,
            users: null,
            error: null
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            this.error = this.users = null;
            this.loading = true;
            axios
                .get('/api/users')
                .then(response => {
                    this.users = response.data;
                    this.loading = false;
                }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });;

        }
    }
}
</script>
