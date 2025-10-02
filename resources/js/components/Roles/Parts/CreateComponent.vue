<template>
   <section>


   </section>
</template>
<script>
export default {
    name: "CreateComponent",
    props:{

    },
    data() {
        return {
           
        }
    },
    methods: {
        
       
        async store(){
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
