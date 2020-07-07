<template>
    <div v-if="showModalDataView">
    <transition>
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">View Student</h5>
                <button type="button" @click="close(false)" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" >&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-sm-12">
                  <div class="row">
                    <div class="row justify-content-between col-sm-12">
                      <div class="form-group col-sm-6">
                          <label>Student ID</label>
                          <input type="text" v-model="form.student_id" class="form-control" readonly="true">
                      </div>
                      <div class="form-group col-sm-6">
                          <label>Username</label>
                          <input type="text" v-model="selectedData.username" class="form-control"readonly="true">
                      </div>
                    </div>
                    <div class="row justify-content-between col-sm-12">
                      <div class="form-group col-sm-6">
                          <label>Grade</label>
                          <input type="text" :value="form.year.year" class="form-control" readonly="true">
                      </div>
                      <div class="form-group col-sm-6">
                          <label>Section</label>
                          <input type="text" :value="form.section.label" class="form-control" readonly="true">
                      </div>
                    </div>
                    <div class="row justify-content-between col-sm-12">
                        <div class="form-group col-sm-4">
                            <label>First Name</label>
                            <input type="text" v-model="form.first_name" class="form-control" readonly="true">
                        </div>
                        <div class="form-group col-sm-4">
                            <label>Middle Name</label>
                            <input type="text" v-model="form.middle_name" class="form-control" readonly="true">
                        </div>
                        <div class="form-group col-sm-4">
                            <label>Last Name</label>
                            <input type="text" v-model="form.last_name" class="form-control" readonly="true">
                        </div>
                    </div>
                    <div class="row justify-content-between col-sm-12">
                        <div class="form-group col-sm-6">
                          <label>Birth Date</label>
                          <input type="text" v-model="form.date_of_birth" class="form-control" readonly="true">
                        </div>
                        <div class="form-group col-sm-6">
                          <label>Age <span style="font-size:12px">(Automatically filled by selecting Birth Date)</span></label>
                          <input type="text" v-model="form.age" class="form-control" readonly="true">
                        </div>
                    </div>
                    <div class="row justify-content-between col-sm-12">
                        <div class="form-group col-sm-4">
                          <label>Gender</label>
                          <input type="text" :value="form.gender" class="form-control" readonly="true">
                        </div>
                        <div class="form-group col-sm-4">
                          <label>Address</label>
                          <input type="text" v-model="form.address" class="form-control" readonly="true">
                        </div>
                        <div class="form-group col-sm-4">
                          <label>Mobile Number</label>
                          <input type="text" v-model="form.phone_number" class="form-control" maxlength="15" readonly="true">
                        </div>
                    </div>
                    <div class="row justify-content-between col-sm-12">
                        <div class="form-group col-sm-12">
                          <label>Email Address</label>
                          <input type="email" v-model="form.email" class="form-control" readonly="true">
                        </div>
                        <div class="form-group col-sm-6" v-if="!viewMode">
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
                <button type="button" class="btn btn-secondary" @click="close(false)">Close</button>
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
    showModalDataView: Boolean,
    selectedData: Object,
    viewMode: Boolean,
  },
  components: {
  },
  data(){
    return {
      attemptSubmit: false,
      isLoading: false,
      years: [],
      sections: [],
      form:{
        student_id: this.selectedData.student_info.student_id,
        year: this.selectedData.student_info.year_id,
        section: this.selectedData.student_info.section_id,
        first_name: this.selectedData.student_info.first_name,
        middle_name: this.selectedData.student_info.middle_name,
        last_name: this.selectedData.student_info.last_name,
        phone_number: this.selectedData.student_info.phone_number,
        age: this.selectedData.student_info.age,
        date_of_birth: this.selectedData.student_info.date_of_birth,
        gender: this.selectedData.student_info.gender,
        address: this.selectedData.student_info.address,
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
        date: this.selectedData.date_of_birth, 
        disabledDates: {
          from: new Date(),
        }
      }
    }
  },
  methods: {
    getYear(){
      var self = this;
      self.sections = [];
      axios.get('/get-year',{
        params: {
          year_id: this.selectedData.year_id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.form.year = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSectionByYear(){
      var self = this;
      axios.get('/get-section',{
        params:{
          section_id: this.selectedData.section_id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.form.section = result;
      })
      .catch(function (error) {
        console.log(error);
      });
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
      this.form.year = '';
      this.form.section = '';
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
      self.$emit('closeView',data);
    }

  },
  created (){
    // console.log(this.editSelected);
    // this.getFacultyId();
    this.getYear();
    this.getSectionByYear();
  },
  computed:{
    missingStudentId: function () { 
        return this.form.student_id == ''; 
    },
    missingYear: function () { 
        return this.form.year == ''; 
    },
    missingSection: function () { 
        return this.form.section == ''; 
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
