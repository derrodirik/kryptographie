<template>
    <div>
        <b-tabs v-model="tabIndex" fill>
            <b-tab title="Verschlüsseln"></b-tab>
            <b-tab title="Entschlüsseln"></b-tab>
            <b-tab title="Cracken"></b-tab>
        </b-tabs>
        <b-form @submit.prevent="onSubmit">
            <b-form-group
                label="Text"
            >
                <b-form-textarea
                    id="text"
                    v-model="text"
                    required=""
                ></b-form-textarea>
            </b-form-group>
            <b-form-group
                label="Key"
                description="Gebe an um wie viele Stellen die Buchstaben verschoben werden sollen"
                v-if="tabIndex !== 2"
            >
                <b-form-input
                    id="key"
                    v-model="key"
                    type="number"
                    max="24"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                label="Ausgabe"
                v-if="encryptedResponse"
            >
                <b-form-textarea
                    id="return"
                    v-model="encryptedResponse"
                    v-if="tabIndex === 0"
                    readonly=""
                ></b-form-textarea>
                <b-form-textarea
                    id="return"
                    v-model="decryptedResponse"
                    v-if="tabIndex === 1"
                    readonly=""
                ></b-form-textarea>
                <b-form-textarea
                    id="return"
                    v-model="crackedResponse"
                    v-if="tabIndex === 2"
                    readonly=""
                ></b-form-textarea>
            </b-form-group>
            <b-button type="submit" variant="primary">
                <b-spinner small v-if="loading" variant="light"></b-spinner>
                Senden
            </b-button>

        </b-form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            text: null,
            encryptedResponse: null,
            decryptedResponse: null,
            crackedResponse: null,
            key: 12,
            error: null,
            loading: false,
            params: null,
            tabIndex: 0
        };
    },
    methods: {
        onSubmit() {
            if (this.tabIndex === 0) {
                this.fetchData('encrypt');
            } else if (this.tabIndex === 1) {
                this.fetchData('decrypt');
            } else if (this.tabIndex === 2) {
                this.fetchData('crack');
            }
        },
        fetchData(endpoint) {
            this.error = this.return = null;
            this.loading = true;
            axios
                .post('/api/caesar/' + endpoint, this.getData())
                .then(response => {
                    this.loading = false;
                    if (this.tabIndex === 0) {
                        this.encryptedResponse = response.data;
                    } else if (this.tabIndex === 1) {
                        this.decryptedResponse = response.data;
                    } else if (this.tabIndex === 2) {
                        this.crackedResponse = response.data;
                    }
                })
                .catch(error => {
                    this.loading = false;
                    this.error = error.response.data.message || error.message;
                });
        },
        getData() {
            return {
                text: this.text,
                key: this.key
            }
        },
    },
}
</script>