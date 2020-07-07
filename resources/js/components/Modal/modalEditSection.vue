<template>
    <div v-if="showModalData">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" @click="$emit('close')">&times;</span>
                </button>
              </div>
              <div class="modal-body">                
                <div class="row">
                  <div class="form-group col-sm-12">
                    <label>Year</label>
                    <select v-model="year" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingYear }">
                        <option v-for="yr in years" :value="yr" :selected="yr == year"> {{ yr.year }}</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                      <label>Section</label>
                      <input type="text" v-model="section" placeholder="Input Section" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSection }">
                      <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                      <label>Section Label</label>
                      <input type="text" v-model="section_label" placeholder="Input Subject Label" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSubjectCode }">
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
    selectedSection: Object,
  },
  components: {
  },
  data(){
    return {
      attemptSubmit: false,
      isLoading: false,
      process: false,
      years: [],
      section_id: this.selectedSection.id,
      year: this.selectedSection.year,
      section: this.selectedSection.section,
      section_label: this.selectedSection.label,
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
      axios.post( 'update-section', {
          year_id: self.year.id,
          section_id: self.section_id,
          section: self.section,
          section_label: self.section_label,
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
    missingSection: function () { 
      return this.section == ''; 
    },
    missingSectionLabel: function () { 
      return this.section_label == ''; 
    },
    missingYear: function () { 
      return this.year == ''; 
    },
  }

};
</script>
