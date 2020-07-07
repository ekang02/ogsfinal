<template>
  <div class="">
    <h3 class="m-3 text-header">
        Add New User
    </h3>
    <hr/>
    <div class="mx-3">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Please provide all information below.</h6>
        </div>
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="form-group col-sm-6">
                    <label>User Role</label>
                    <select class="form-control" v-model="form.user_role" :class="{ 'is-invalid': attemptSubmit && missingUserRole }" @change="userRoleChange($event)">
                      <option v-for="role in user_role" :value="role.id" :key="role.id">{{ role.label }}</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                </div>
            </div>
            <div class="row justify-content-between">
                <div class="form-group col-sm-6">
                    <label>Username</label>
                    <input type="text" v-model="form.username" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingUsername }">
                    <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-6" v-if="isStudent">
                    <label>Student ID:</label>
                    <input type="text" v-model="form.student_id" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingStudentId }">
                    <div class="invalid-feedback">This field is required.</div>
                </div>
            </div>
            <div class="row justify-content-between">
                <div class="form-group col-sm-4">
                    <label>First Name</label>
                    <input type="text" v-model="form.first_name" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingFirstName }">
                    <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-4">
                    <label>Middle Name</label>
                    <input type="text" v-model="form.middle_name" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingMiddleName }">
                    <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-4">
                    <label>Last Name</label>
                    <input type="text" v-model="form.last_name" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingLastName }">
                    <div class="invalid-feedback">This field is required.</div>
                </div>
            </div>
            <div class="row justify-content-between">
                <div class="form-group col-sm-6">
                  <label>Mobile Number</label>
                  <input type="text" v-model="form.phone_number" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingMobileNumber }" @keypress="isNumber($event)" maxlength="15">
                    <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-6">
                  <label>Email Address</label>
                  <input type="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingEmail || errors.email.length > 0}">
                  <div v-if="errors.email.length > 0" class="invalid-feedback">{{ errors.email[0] }}</div>
                  <div v-else class="invalid-feedback">This field is required.</div>
                </div>
            </div>
            <div class="row justify-content-between">
                <div class="form-group col-sm-6">
                  <label>Assign Password</label>
                  <input type="password" v-model="form.password" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingPassword || errors.password.length > 0}">
                  <div v-if="errors.password.length > 0" class="invalid-feedback">The password must be at least 8 characters / Match</div>
                  <div v-else class="invalid-feedback">This field is required.</div>
                </div>
            </div>
            <div class="text-right">
              <button class="btn btn-outline-secondary" @click="clear()">Clear</button>
              <button class="btn btn-primary" @click="validateForm">Save</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props:['user'],
  components: {

  },
  data() {
    return {
      process: false,
      isStudent: false,
      user_role:[],
      form: {
          user_role: '',
          username: '',
          student_id: '',
          first_name: '',
          middle_name: '',
          last_name: '',
          phone_number: '',
          email: '',
          password: '',
      },
      errors: {
        email: [],
        password: [],
      },
      attemptSubmit: false,

    };
  },
  created(){
    this.getUserRoles();
  },
  computed: {
    missingUserRole: function () { 
        return this.form.username == ''; 
    },
    missingUsername: function () { 
        return this.form.username == ''; 
    },
    missingFirstName: function () { 
        return this.form.first_name == ''; 
    },
    missingMiddleName: function () { 
        return this.form.middle_name == ''; 
    },
    missingLastName: function () { 
        return this.form.last_name == ''; 
    },
    missingEmail: function () { 
        return this.form.email == ''; 
    },
    missingMobileNumber: function () { 
        return this.form.phone_number == ''; 
    },
    missingPassword: function () { 
        return this.form.password == ''; 
    },
    missingStudentId: function () { 
        return this.form.student_id == ''; 
    },

  },
  methods: {

    getUserRoles(){
      var self = this;
      axios.get('/get-user-roles')
      .then(function (response) {
        // handle success
        console.log(response.data);
        self.user_role = response.data;
      })
      .catch(function (error) {
        // handle error
        console.log(error);
      });
    },

    validateForm: function (event) {
      this.attemptSubmit = true;
      if (this.missingUsername || this.missingUsername || this.missingFirstName || this.missingLastName || this.missingMobileNumber ||  this.missingEmail || this.missingPassword){
        event.preventDefault();
      }else{
        this.saveNewUser();
      }
      
    },
    isNumber: function(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();;
      } else {
        return true;
      }
    },
    userRoleChange(event) {

      event.target.value == 5 ? this.isStudent = true : this.isStudent = false;
    },
    saveNewUser(){
      var self = this;
        if(self.process === true){
            return;
        }
        self.process = true;
        self.$swal({
          title: 'Are you sure?',
          text: "You want to Add New User?",
          type: 'info',
          icon: "info",
          showCancelButton: true,
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.value) {
            self.$swal({
                html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Adding.....</div>",
                showConfirmButton: false,
                allowOutsideClick: false
            });
            axios.post('save-new-user', {
                user_type: this.form.user_role,
                username: this.form.username,
                student_id: this.form.student_id,
                first_name: this.form.first_name,
                middle_name: this.form.middle_name,
                last_name: this.form.last_name,
                phone_number: this.form.phone_number,
                email: this.form.email,
                password: this.form.password,
            })
            .then(function (response) {
                self.$swal(
                  'Saved!',
                  'Successfully Added New User.',
                  'success'
                )
                self.process = false
                self.clear();
            })
            .catch(function (error) {
                console.log(error);
                self.$swal({
                    position: 'center',
                    type: 'error',
                    icon: 'error',
                    title: 'Something went wrong',
                    showConfirmButton: true,
                })
                let checkEmail = _.has(error.response.data.errors, 'email');
                let checkpassword = _.has(error.response.data.errors, 'password');
                if(checkEmail){
                  self.errors.email = error.response.data.errors.email;
                }else{
                  self.errors.email = [];
                }
                if(checkpassword){
                  self.errors.password = error.response.data.errors.password;
                }else{
                  self.errors.password = [];
                }
                self.process = false
            });
          }else{
              self.process = false
          }
        })
    },
    clear(){
        this.attemptSubmit = false;
        this.form.username = '';
        this.form.first_name = '';
        this.form.middle_name = '';
        this.form.last_name = '';
        this.form.phone_number = '';
        this.form.email = '';
        this.form.password = '';
        this.errors.email = [];
        this.errors.password = [];

    },
  }
};
</script>
