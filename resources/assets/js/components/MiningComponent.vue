<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Mining</div>

                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Balance</label>
                                <div class="col-md-9">
                                    <p class="form-control-static mono">{{ balance }} MK5</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Current Hash</label>
                                <div class="col-md-9">
                                    <p class="form-control-static mono">{{ currentHash }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Target</label>
                                <div class="col-md-9">
                                    <p class="form-control-static mono">{{ target }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Nounce</label>
                                <div class="col-md-9">
                                    <p class="form-control-static mono">{{ nounce }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Calculated Hash</label>
                                <div class="col-md-9">
                                    <p class="form-control-static mono">{{ calculatedHash }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="button" class="btn btn-primary" :class="mining ? 'active' : ''" @click="mining = ! mining">
                                        {{ mining ? 'Stop' : 'Start' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                balance: 0,
                currentHash: '',
                target: '',
                nounce: '',
                calculatedHash: '',
                mining: false,
            };
        },
        mounted() {
            this.getChallenge();
        },
        methods: {
            getChallenge() {
                axios.get('/api/pow/challenge').then((response) => {
                    this.startPow(response.data);
                });
            },
            startPow(data) {
                this.balance     = data.balance;
                this.currentHash = data.hash;
                this.target      = data.target;

                this.schedulePow();
            },
            schedulePow(data) {
                setTimeout(() => {
                    this.pow();
                }, 1);
            },
            pow() {
                if (this.mining) {
                    this.nounce         = Math.random().toString(36).substr(2, 5);
                    this.calculatedHash = this.sha256(this.sha256(this.currentHash + this.nounce));

                    if (this.calculatedHash <= this.target) {
                        this.currentHash = this.calculatedHash;
                        this.postResponse();

                        return;
                    }
                }

                this.schedulePow();
            },
            sha256(data) {
                let sha = new jsSHA('SHA-256', 'TEXT');

                sha.update(data);

                return sha.getHash("HEX");
            },
            postResponse() {
                axios.post('/api/pow/response', {
                    nounce: this.nounce,
                }).then((response) => {
                    this.startPow(response.data);
                });
            },
        },
    }
</script>
