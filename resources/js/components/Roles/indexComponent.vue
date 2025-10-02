<template>
   <section>


   </section>
</template>
<script>
export default {
    name: "indexComponent",
    props:{

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
        async show(){
            await axios.get().then(res=>{

            }).catch(res=>{

            });
        },
        async store(){
            await axios.post().then(res=>{

            }).catch(res=>{
                
            });
        },
        async updte(){
            await axios.post().then(res=>{

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
