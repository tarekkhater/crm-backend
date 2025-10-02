<template>
   <section>


   </section>
</template>
<script>
export default {
    name: "EditComponent",
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
       
        async updte(){
            await axios.post().then(res=>{

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
