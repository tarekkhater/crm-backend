<template>
   <section>


   </section>
</template>
<script>
export default {
    name: "showComponent",
    props:{
        id:Number
    },
    data() {
        return {
           
        }
    },
    methods: {
        async index(){
            await axios.get().then(res=>{

            }).catch(res=>{

            });
        },
        
        async delete(){
            await axios.delete().then(res=>{

            }).catch(res=>{

            });
        },
        
    },
    created(){
        this.index();
    },
    mounted() {
    },
    computed: {
    }
}
</script>

<style scoped>

</style>
