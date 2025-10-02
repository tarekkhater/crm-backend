<script>
export default {
name: "Update",
    mounted(){
    console.log('mounted');
    },
    methods : {
    update(){
        axios.get('/api/update/system');
        console.log('updated');
    }
    }
}
</script>

