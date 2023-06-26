<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6 mt-4">

                <h2>Forgot Password</h2>
                <p class="text-danger" v-if="!!errors" >
                    <span >{{ errors }}</span>
                </p>

                <form @submit.prevent="reset">
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" class="form-control" id="email" v-model="form.email">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
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
                email: '',
            });
            let errors = ref()

            const reset = async() =>{
                await axios.post('/api/forgot',form).then(res=>{
                    if(res.data.success){
                      //  store.dispatch('setToken',res.data.data.token)
                      //  router.push({name:'verify'})
                        router.push({name:'Verify'})
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
        }
    }
</script>
