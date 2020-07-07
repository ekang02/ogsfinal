<template>
    <div v-if="showModalData">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Subject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" @click="$emit('close')">&times;</span>
                </button>
              </div>
              <div class="modal-body">                
                <div class="row">
                  <!-- <div class="form-group col-sm-12">
                    <label>Year</label>
                    <select v-model="year_id" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingYear }">
                        <option v-for="year in years" :value="year.id" :selected="year_id === year.id" > {{ year.year }}</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div> -->
                  <div class="form-group col-sm-12">
                      <label>Subject</label>
                      <input type="text" v-model="subject" placeholder="Input Subject" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSubject }">
                      <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                      <label>Subject Code</label>
                      <input type="text" v-model="subject_code" placeholder="Input Subject Code" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSubjectCode }">
                      <div class="invalid-feedback">This field is required.</div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancel</button>
                <button type="button" class="btn btn-primary" @click="save()">
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
    selectedSubject: Object,
  },
  components: {
  },
  data(){
    return {
      attemptSubmit: false,
      isLoading: false,
      process: false,
      subject_id: this.selectedSubject.id,
      years: [],
      year_id: this.selectedSubject.id,
      subject: this.selectedSubject.label,
      subject_code: this.selectedSubject.subject_code,
    }
  },
  methods: {
    
    getYear(){
      var self = this;
      axios.get('/get-year')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.years = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    save(){
      var self = this;
      if(self.process === true){
          return;
      }
      self.isLoading = true;
      self.process = true;
      axios.post( 'update-subject', {
          subject_id: self.subject_id,
          // year_id: self.year_id,
          subject: self.subject,
          subject_code: self.subject_code,
      })
      .then(function (response) {
          self.process = false;
          self.isLoading = false;
          self.$emit('close');
      })
      .catch(function (error) {
          console.log(error);
          self.process = false
      });
    },
  },
  created (){
    this.getYear();
  },
  computed:{
    missingYear: function () { 
      return this.year_id == ''; 
    },
    missingSubject: function () { 
      return this.subject == ''; 
    },
    missingSubjectCode: function () { 
      return this.subject_code == ''; 
    },
  }

};
</script>
