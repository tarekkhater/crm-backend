<template>
    <div>
        <span class="ml-2 badge badge-primary font-weight-light" style="font-size: 1.4em">{{ trades_count }}</span>
    </div>
</template>
<script>
export default {
name: "updateUser",
    mounted(){
        console.log('update mounted');
        this.trades_count = this.trades;
        this.update();
        setInterval(() => {
            this.update();
        }, 10000)
    },
    props : ['trades'],
    data() {
        return {
            trades_count: 0,
        }
    },
    methods : {
        update(){
            axios.get('/dashboard/update/user/status').then((res) => {
                this.trades_count = res.data.trades;
            })
        }
    }
}
</script>

<style scoped>

</style>
