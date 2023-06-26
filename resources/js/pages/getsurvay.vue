<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 mt-4">

                <h2>List of Survay Records</h2>
                <p class="text-danger" v-if="!!errors" >
                    <span >{{ errors }}</span>
                </p>
                <div v-if="success" class="alert alert-success">
  {{ success }}
</div>


              
                   <table class="table table-borderd">
                    <thead>
                        <th>#Sno</th><th>Name</th><th>Email</th><th>Message</th>
                    </thead>
                    <tbody>
                        <tr  v-for="(user, index) in users" :key="user.id" >
                            <td>{{ index + 1 }}</td>
                            <td> {{ user.name }}</td>
                            <td> {{ user.email }}</td>
                            <td> {{ user.message }}</td>
                           
                        </tr>
                    </tbody>

                   </table>

               
                  
                  
              
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';

export default {
  data() {
    return {
      users: []
    };
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    fetchUsers() {
      axios.post('/api/getsurvay')
        .then(response => {
          this.users = response.data.data;
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
};
</script>
