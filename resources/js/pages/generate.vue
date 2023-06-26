<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6 mt-4">

                <h2>Generate Links for Survay</h2>
                <p class="alert alert-danger" v-if="!!errors" >
                    <span >{{ errors }}</span>
                </p>
             <div v-if="success" class="alert alert-success">
  {{ success }}
</div>

                <form @submit.prevent="register">
                    <div class="form-group">
                        <label for="name">Expire Date</label>
                        <input type="date" class="form-control" id="expire_date" v-model="form.expire_date">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" v-model="form.email">
                    </div>


                    <button type="submit" class="btn btn-primary">Generate Links</button>
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
                expire_date :'',
                email: '',
            });
            let errors = ref()
            let  success=ref()
         
            const register = async() =>{
                await axios.post('/api/generate',form).then(res=>{
                   
                    if(res.data.success){
                        console.log(res.data)
                       // store.dispatch('setToken',res.data.data.token)
                       // router.push({name:'Dashboard'})
                       success.value = res.data.message;
                this.form = {};
                    }
                }).catch(e=>{
                    errors.value = e.response.data.message
                })
            }
            return{
                form,
                register,
                errors,
                success
            }
        }
    }
</script>
