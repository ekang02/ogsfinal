<template>
    <div v-if="showModalDataEdit">
    <transition>
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Teacher</h5>
                <button type="button" @click="close(false)" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" >&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-sm-12">
                  <div class="row">
                    <div class="row justify-content-between col-sm-12">
                      <div class="form-group col-sm-6">
                          <label>Faculty ID</label>
                          <input type="text" v-model="form.faculty_id" class="form-control" readonly="true" disabled="true">
                      </div>
                      <div class="form-group col-sm-6">
                          <label>Username</label>
                          <input type="text" v-model="form.username" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingUsername }" readonly="">
                          <div class="invalid-feedback">This field is required.</div>
                      </div>
                    </div>
                    <div class="row justify-content-between col-sm-12">
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
                    <div class="row justify-content-between col-sm-12">
                        <div class="form-group col-sm-6">
                          <label>Birth Date</label>
                          <datepicker 
                            v-model="state.date" 
                            name="date_of_birth"
                            @selected="getAge" 
                            :format="customFormatter"  
                            :minimumView="'day'" 
                            :maximumView="'year'" 
                            :initialView="'month'"
                            :highlighted="highlighted"
                            :disabledDates="state.disabledDates"                          
                            input-class="form-control bg-white"
                            maxlength="15">
                          </datepicker>
                          <div class="invalid-feedback">This field is required.</div>
                        </div>
                        <div class="form-group col-sm-6">
                          <label>Age <span style="font-size:12px">(Automatically filled by selecting Birth Date)</span></label>
                          <input type="text" v-model="form.age" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingAge }" maxlength="15" readonly="true">
                            <div class="invalid-feedback">This field is required.</div>
                        </div>
                    </div>
                    <div class="row justify-content-between col-sm-12">
                        <div class="form-group col-sm-4">
                          <label>Gender</label>
                          <select class="form-control" v-model="form.gender" :class="{ 'is-invalid': attemptSubmit && missingGender }">
                            <option v-for="gender in genderOption" :value="gender.value" :key="gender.id">{{ gender.label }}</option>
                          </select>
                          <div class="invalid-feedback">This field is required.</div>
                        </div>
                        <div class="form-group col-sm-4">
                          <label>Address</label>
                          <input type="text" v-model="form.address" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingAddress }">
                            <div class="invalid-feedback">This field is required.</div>
                        </div>
                        <div class="form-group col-sm-4">
                          <label>Mobile Number</label>
                          <input type="text" v-model="form.phone_number" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingMobileNumber }" @keypress="isNumber($event)" maxlength="15">
                            <div class="invalid-feedback">This field is required.</div>
                        </div>
                    </div>
                    <div class="row justify-content-between col-sm-12">
                        <div class="form-group col-sm-6">
                          <label>Email Address</label>
                          <input type="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingEmail || errors.email.length > 0}">
                          <div v-if="errors.email.length > 0" class="invalid-feedback">{{ errors.email[0] }}</div>
                          <div v-else class="invalid-feedback">This field is required.</div>
                        </div>
                        <div class="form-group col-sm-6">
                          <label>Assign Password</label>
                          <input type="password" v-model="form.password" class="form-control" :class="{ 'is-invalid': errors.password.length > 0}">
                          <div v-if="errors.password.length > 0" class="invalid-feedback">The password must be at least 8 characters / Match</div>
                          <div v-else class="invalid-feedback">This field is required.</div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="close(false)">Cancel</button>
                <button type="button" class="btn btn-primary" @click="validateForm">
                  Submit<span v-show="isLoading">ting... <i class="fa fa-refresh fa-spin"></i></span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: {
    showModalDataEdit: Boolean,
    selectedData: Object,
  },
  components: {
  },
  data(){
    return {
      attemptSubmit: false,
      isLoading: false,
      form:{
        username: this.selectedData.username,
        faculty_id: this.selectedData.info.faculty_number,
        first_name: this.selectedData.info.first_name,
        middle_name: this.selectedData.info.middle_name,
        last_name: this.selectedData.info.last_name,
        phone_number: this.selectedData.info.phone_number,
        age: this.selectedData.info.age,
        date_of_birth: this.selectedData.info.date_of_birth,
        gender: this.selectedData.info.gender,
        address: this.selectedData.info.address,
        email: this.selectedData.email,
        password: '',
      },
      errors: {
        email: [],
        password: [],
      },
      genderOption:[
        {"id": 1, "value": "male", "label": "Male"},
        {"id": 2, "value": "female", "label": "Female"}
      ],
      highlighted: {},
      state : {   
        date: this.selectedData.info.date_of_birth, 
        disabledDates: {
          from: new Date(),
        }
      }
    }
  },
  methods: {
    validateForm: function (event) {
      this.attemptSubmit = true;
      if (this.missingGender || this.missingAddress || this.missingUsername || this.missingFirstName || this.missingLastName || this.missingMobileNumber ||  this.missingEmail || this.missingAge || this.missingBirthDate){
        event.preventDefault();
      }else{
        this.update();
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
    update(){
      var self = this;
      if(self.process === true){
          return;
      }
      self.isLoading = true;
      self.process = true;
      axios.post('update-user', {
          id: this.selectedData.id,
          username: this.form.username,
          first_name: this.form.first_name,
          middle_name: this.form.middle_name,
          last_name: this.form.last_name,
          age: this.form.age,
          date_of_birth: this.form.date_of_birth,
          gender: this.form.gender,
          address: this.form.address,
          phone_number: this.form.phone_number,
          email: this.form.email,
          password: this.form.password,
      })
      .then(function (response) {
          self.process = false
          self.isLoading = false;
          self.clear();
          self.close(true);
      })
      .catch(function (error) {
          console.log(error);
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
    },

    customFormatter(date) {
      return moment(date).format('MM/DD/YYYY');
    },
    getAge(elem){
      let selectedDate =  moment(elem).format('MM/DD/YYYY');
      let age = moment().diff(selectedDate, 'years')
      this.form.date_of_birth = selectedDate;
      this.form.age = age;

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
      this.form.age = '';
      this.form.date_of_birth = '';
      this.form.address = '';
      this.form.gender = '';
      this.state.date = '';
      this.errors.email = [];
      this.errors.password = [];
    },
    close(data){
      var self = this;
      self.$emit('closeEdit',data);
    }

  },
  created (){
    // console.log(this.editSelected);
    // this.getFacultyId();
  },
  computed:{
    missingFacultyId: function () { 
        return this.form.faculty_id == ''; 
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
    missingAddress: function () { 
        return this.form.address == ''; 
    },
    missingGender: function () { 
        return this.form.gender == ''; 
    },
    missingAge: function () { 
        return this.form.age == ''; 
    },
    missingBirthDate: function () { 
        return this.form.date_of_birth == ''; 
    },
  }

};
</script>
