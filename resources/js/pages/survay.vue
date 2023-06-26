<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6 mt-4">

                <h2>Survay Form</h2>
                <p class="text-danger" v-if="!!errors" >
                    <span >{{ errors }}</span>
                </p>
                <div v-if="success" class="alert alert-success">
  {{ success }}
</div>


                <form @submit.prevent="survay">
                    <div class="form-group">
                        <label for="password">Name:</label>
                        <input type="text" class="form-control" id="name" v-model="form.name">
                    </div>

                    <div class="form-group">
                        <label for="password">Email:</label>
                        <input type="email" class="form-control" id="email" v-model="form.email">
                    </div>
                    <div class="form-group">

                        <label for="c_password">Message:</label>
                        <textarea class="form-control" id="message" v-model="form.message" >

                        </textarea>
                       
                    </div>


                    <input type="hidden" v-model="form.token"  id="token" >
                    

                    <button type="submit" class="btn btn-primary">Submit</button>
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
            let  success=ref()

            const survay = async() =>{
                await axios.post('/api/submitsurvay',form).then(res=>{
                    if(res.data.success){
                        success.value = res.data.message;
                  //     this.email='';
                    }
                }).catch(e=>{
                    //success.value='';
                  //  success.value='';
                  //console.log(e.response.data.message);
                   errors.value = e.response.data.message
                })
            }
            return{
                form,
                survay,
                errors,
                success

            }
        },

        methods: {
    fetchData() {
        const token = this.$route.query.token;
       
        axios.post('/api/survay', {
        token: token
       }) .then(res => {
    if(res.data.success){
       
       this.form.token=token
        console.log(res.data.success)
   // router.push({name:'Login'})
   document.getElementById("email").value =this.email;

  
    }
    }).catch(e=>{
       
     //  this.$router.push({ name: 'Login' });
    })
   
      // Code to fetch data from an API or perform other actions
    }
  },

  created() {
    // Access the query parameter from the current route
    this.token = this.$route.query.token|| ''; // Assign the query parameter value to the hidden field or an empty string
    
}
    }
</script>
