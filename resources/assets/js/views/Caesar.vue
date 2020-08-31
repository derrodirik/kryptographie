<template>
    <div>
        <b-tabs v-model="tabIndex" fill>
            <b-tab title="Verschlüsseln"></b-tab>
            <b-tab title="Entschlüsseln"></b-tab>
            <b-tab title="Crack"></b-tab>
            <b-tab title="Brute Force"></b-tab>
        </b-tabs>
        <div class="mt-4">
            <div v-if="error">
                <b-alert variant="danger" show="">{{ error }}</b-alert>
            </div>
            <b-form @submit.prevent="onSubmit">
                <b-form-group
                    label="Eingabe"
                >
                    <b-form-textarea
                        id="text"
                        v-model="text"
                        required=""
                        max-rows="10"
                        maxlength="3000"
                    ></b-form-textarea>
                    <div class="text-secondary text-right font-weight-lighter">
                        <small>{{ text.length }} / 3000</small>
                    </div>
                </b-form-group>
                <b-form-group
                    label="Key"
                    description="Gebe an um wie viele Stellen die Buchstaben verschoben werden sollen"
                    v-if="tabIndex == 0 || tabIndex == 1"
                >
                    <b-form-input
                        id="key"
                        v-model="key"
                        type="number"
                        min="1"
                        max="26"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Ausgabe"
                    v-if="encryptedResponse && tabIndex === 0"
                >
                    <b-form-textarea
                        id="return"
                        v-model="encryptedResponse"
                        readonly=""
                        max-rows="10"
                    ></b-form-textarea>
                    <b-link
                        class="text-muted"
                        v-clipboard:copy="encryptedResponse"
                        v-clipboard:success="onCopy"
                        v-clipboard:error="onCopyError"
                        @mouseleave="copyLeaved"
                    >{{ copyState }}
                    </b-link>
                </b-form-group>
                <b-form-group
                    label="Ausgabe"
                    v-if="decryptedResponse && tabIndex === 1"
                >
                    <b-form-textarea
                        id="return"
                        v-model="decryptedResponse"
                        readonly=""
                        max-rows="10"
                    ></b-form-textarea>
                    <b-link
                        class="text-muted"
                        v-clipboard:copy="decryptedResponse"
                        v-clipboard:success="onCopy"
                        v-clipboard:error="onCopyError"
                        @mouseleave="copyLeaved"
                    >{{ copyState }}
                    </b-link>
                </b-form-group>
                <b-form-group
                    label="Ausgabe"
                    v-if="crackedResponse && tabIndex === 2"
                >
                    <b-form-textarea
                        id="return"
                        v-model="crackedResponse"
                        readonly=""
                        max-rows="10"
                    ></b-form-textarea>
                    <b-link
                        class="text-muted"
                        v-clipboard:copy="crackedResponse"
                        v-clipboard:success="onCopy"
                        v-clipboard:error="onCopyError"
                        @mouseleave="copyLeaved"
                    >{{ copyState }}
                    </b-link>
                </b-form-group>
                <label v-if="tries && tabIndex == 3">Versuche:</label>
                <div v-for="(item, index) in tries">
                    <b-form-group v-if="tries && tabIndex == 3">
                        <b-input-group v-if="index !== 0">
                            <b-form-input
                                v-model="tries[index]"
                                type="text"
                            ></b-form-input>
                            <b-input-group-append>
                                <b-button variant="outline-info" @click="toDecrypt(index)">
                                    <font-awesome-icon :icon="['far', 'arrow-right']"/>
                                </b-button>
                            </b-input-group-append>
                        </b-input-group>
                    </b-form-group>
                </div>
                <div class="text-right">
                    <b-button id="setExampleText" variant="secondary" @click="setExampleText">Beispiel Text einfügen
                    </b-button>
                    <b-button type="submit" variant="primary">
                        <b-spinner small v-if="loading" variant="light"></b-spinner>
                        Senden
                    </b-button>
                </div>
            </b-form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            text: "",
            encryptedResponse: null,
            decryptedResponse: null,
            crackedResponse: null,
            tries: null,
            key: 12,
            exampleText: 'Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die Blindtexte. Abgeschieden wohnen sie in Buchstabhausen an der Küste des Semantik, eines großen Sprachozeans. Ein kleines Bächlein namens Duden fließt durch ihren Ort und versorgt sie mit den nötigen Regelialien. Es ist ein paradiesmatisches Land, in dem einem gebratene Satzteile in den Mund fliegen. Nicht einmal von der allmächtigen Interpunktion werden die Blindtexte beherrscht – ein geradezu unorthographisches Leben. Eines Tages aber beschloß eine kleine Zeile Blindtext, ihr Name war Lorem Ipsum, hinaus zu gehen in die weite Grammatik. Der große Oxmox riet ihr davon ab, da es dort wimmele von bösen Kommata, wilden Fragezeichen und hinterhältigen Semikoli, doch das Blindtextchen ließ sich nicht beirren. Es packte seine sieben Versalien, schob sich sein Initial in den Gürtel und machte sich auf den Weg. Als es die ersten Hügel des Kursivgebirges erklommen hatte, warf es einen letzten Blick zurück auf die Skyline seiner Heimatstadt Buchstabhausen, die Headline von Alphabetdorf und die Subline seiner eigenen Straße, der Zeilengasse. Wehmütig lief ihm eine rhetorische Frage über die Wange, dann setzte es seinen Weg fort. Unterwegs traf es eine Copy. Die Copy warnte das Blindtextchen, da, wo sie herkäme wäre sie',
            error: null,
            loading: false,
            params: null,
            tabIndex: 0,
            copyState: 'Kopieren'
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
            } else if (this.tabIndex === 3) {
                this.bruteForce();
            }
        },
        async bruteForce() {
            this.loading = true;
            let tries = [];
            let text = this.text;
            let i;
            for (i = 0; i < 25; i++) {
                tries[i] = await this.fetchData('decrypt', {'text': text.substring(0, 50), key: i}, false, true);
            }
            this.tries = tries;
            this.loading = false;
        },
        fetchData(endpoint, data = this.getData(), setLoading = true, returnData = false) {
            this.error = this.return = null;
            let $return;
            if (setLoading)
                this.loading = true;
            $return = axios
                .post('/api/caesar/' + endpoint, data)
                .then(response => {
                    if (returnData)
                        return response.data;

                    if (setLoading)
                        this.loading = false;
                    if (endpoint === "encrypt") {
                        this.encryptedResponse = response.data;
                        return;
                    } else if (endpoint === "decrypt") {
                        this.decryptedResponse = response.data;
                        return;
                    } else if (endpoint === "crack") {
                        this.crackedResponse = response.data;
                        return;
                    }
                })
                .catch(error => {
                    if (setLoading)
                        this.loading = false;
                    this.error = error.response.data.message || error.message;
                });
            return $return;
        },
        onCopy() {
            this.copyState = "Kopiert!";
        },
        onCopyError() {
            this.copyState = "Kopieren nicht möglich";
        },
        copyLeaved() {
            this.copyState = "Kopieren";
        },
        getData() {
            return {
                text: this.text,
                key: this.key
            }
        },
        setExampleText() {
            this.text = this.exampleText;
        },
        toDecrypt(key) {
            this.key = key;
            this.tabIndex = 1;
            this.onSubmit();
            this.tries = null;
        },
    },
}
</script>