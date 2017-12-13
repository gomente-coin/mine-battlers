<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Ranking</div>

                    <div class="panel-body">
                        <horizontal-bar-chart :chart-data="dataCollection" :height="1200"></horizontal-bar-chart>
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
                dataCollection: {
                    labels: [],
                    datasets: [],
                },
            };
        },
        mounted() {
            this.updateRanking();
        },
        methods: {
            updateRanking() {
                axios.get('/api/ranking/top-100-miners').then((response) => {
                    let miners = response.data.miners;
                    let labels = [];
                    let data   = [];
                    let rank   = 1;

                    for (let miner of miners) {
                        labels.push(rank.toString() + ': ' + miner.nickname);
                        data.push(miner.balance);

                        rank++;
                    }

                    while (rank <= 100) {
                        labels.push(rank.toString());
                        data.push(0);

                        rank++;
                    }

                    this.dataCollection = {
                        labels: labels,
                        datasets: [{
                            label: 'Mined MK5',
                            data: data,
                            backgroundColor: 'rgba(75,192,192,0.4)',
                            borderColor: 'rgba(75,192,192,1)',
                        }],
                    };
                    this.scheduleNextUpdate();
                });
            },
            scheduleNextUpdate() {
                setTimeout(() => {
                    this.updateRanking();
                }, 10000);
            },
        },
    }
</script>
