<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6 mt-4">

                <h2>Reset Password</h2>
                <p class="text-danger" v-if="!!errors" >
                    <span >{{ errors }}</span>
                </p>

                <form @submit.prevent="reset">
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" v-model="form.password">
                    </div>
                    <div class="form-group">
                        <label for="c_password">Confirm Password:</label>
                        <input type="password" class="form-control" id="c_password" v-model="form.c_password">
                    </div>


                    <input type="hidden" v-model="form.token"  id="token" >
                    <input type="hidden" v-model="form.email"  id="email" >

                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import { reactive,ref } from 'vue'
    import { useRouter } from "vue-router"
   
    
    export default{
        mounted() {
    // Your code here
    this.fetchData();
   
  },
        setup(){
            const router = useRouter()
          

          
            let form = reactive({
               email:'',
               token:'',
                password: '',
                c_password: '',
            
            });
          
            
            let errors = ref()

            const reset = async() =>{
                await axios.post('/api/reset',form).then(res=>{
                    if(res.data.success){
                        
                        router.push({name:'Login'})
                    }
                }).catch(e=>{
                    errors.value = e.response.data.message
                })
            }
            return{
                form,
                reset,
                errors
            }
        },

        methods: {
    fetchData() {
        const token = this.$route.query.token;
        const email = this.$route.query.email;
       
        axios.post('/api/showreset', {
        token: token,email:email
       }) .then(res => {
    if(res.data.success){
        this.form.email=email;
       this.form.token=token
        console.log(res.data.success)
   // router.push({name:'Login'})
   document.getElementById("email").value =this.email;
   document.getElementById("token").value = this.token;
  
    }
    }).catch(e=>{
       
       this.$router.push({ name: 'Login' });
    })
   
      // Code to fetch data from an API or perform other actions
    }
  },

  created() {
    // Access the query parameter from the current route
    this.token = this.$route.query.token|| ''; // Assign the query parameter value to the hidden field or an empty string
    this.email = this.$route.query.email|| '';  
}
    }
</script>
