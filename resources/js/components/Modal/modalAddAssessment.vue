<template>
    <div v-if="showModalData">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add Assessment Level</h5>
                <button type="button" @click="close()" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" >&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>School Year</label>
                        <input type="text":value="academic_year_value" placeholder="School Year" class="form-control" readonly="true">
                      </div>
                      <div class="form-group">
                          <label>Assessment Level Name</label>
                          <input type="text" v-model="form.assessment" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingAssessment }">
                          <div class="invalid-feedback">This field is required.</div>
                      </div>
                      <div class="form-group">
                          <label>Assessment Level Percentage</label>
                          <input type="text" v-model="form.percentage" @keypress="isNumber($event)" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingPercentage }">
                          <div class="invalid-feedback">This field is required.</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="close()">Cancel</button>
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
  name: 'modalData',
  props: {
    showModalData: Boolean,
  },
  components: {
  },
  data(){
    return {
      attemptSubmit: false,
      isLoading: false,
      process: false,
      academic_year: '',
      form:{
        assessment: '',
        percentage: '',
      },
    }
  },
  methods: {

    getAcademicYear(){
      var self = this;
      axios.get('/get-academic-year')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.academic_year = result;
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

    validateForm: function (event) {
      this.attemptSubmit = true;
      if (this.missingAssessment || this.missingPercentage){
        event.preventDefault();
      }else{
        this.save();
      }
      
    },
    save(){
      var self = this;
      if(self.process === true){
          return;
      }
      self.isLoading = true;
      self.process = true;
      axios.post('save-new-assessment', {
          assessment: self.form.assessment,
          percentage: self.form.percentage,
          academic_year_id: self.academic_year.id,
      })
      .then(function (response) {
          self.process = false
          self.isLoading = false;
          self.clear();
      })
      .catch(function (error) {
          console.log(error);
          self.process = false
      });
    },

    clear(){
      this.attemptSubmit = false;
      this.form.assessment = '';
      this.form.percentage = '';
    },
    close(){
      var self = this;
      self.$emit('close',true);
    }

  },
  created (){
    this.getAcademicYear();
  },
  computed:{
    academic_year_value(){
      return this.academic_year ? this.academic_year.from_year + ' - ' + this.academic_year.to_year : '';
    },
    missingAssessment: function () { 
        return this.form.assessment == ''; 
    },
    missingPercentage: function () { 
        return this.form.percentage == ''; 
    },
  }

};
</script>
