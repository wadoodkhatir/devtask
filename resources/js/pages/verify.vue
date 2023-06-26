<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6 mt-4">

                <h2>Veirfy OTP</h2>
                <p class="text-danger" v-if="!!errors" >
                    <span >{{ errors }}</span>
                </p>

                <form @submit.prevent="verify">
                    <div class="form-group">
                        <label for="name">Enter your OTP</label>
                        <input type="text" class="form-control" id="otp" v-model="form.otp">
                    </div>
                   

                
                  

                    <button type="submit" class="btn btn-primary">Verify</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import { reactive,ref } from 'vue'
    import { useRouter } from "vue-router"
    import { useStore } from 'vuex'
    export default{
        setup(){
            const router = useRouter()
            const store = useStore()

            let form = reactive({
             
                otp: '',
              
            });
            let errors = ref()

            const verify = async() =>{
                await axios.post('/api/verify',form).then(res=>{
                    if(res.data.success){
                       
                        router.push({name:'Reset' , query: { token:res.data.data.token,email:res.data.data.email }})
                    }
                }).catch(e=>{
                    console.log(e.response);
                    errors.value = e.response.data.message
                })
            }
            return{
                form,
                verify,
                errors
            }
        }
    }
</script>
