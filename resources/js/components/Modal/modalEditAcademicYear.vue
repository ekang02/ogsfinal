<template>
    <div v-if="showModalDataEdit">
    <transition>
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Academic Year</h5>
                <button type="button" @click="close(false)" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" >&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                          <label>From Year</label>
                          <input type="text" v-model="form.from_year" class="form-control" @keypress="isNumber($event)" :class="{ 'is-invalid': attemptSubmit && missingFrom }">
                          <div class="invalid-feedback">This field is required.</div>
                      </div>
                      <div class="form-group">
                          <label>To Year</label>
                          <input type="text" v-model="form.to_year" class="form-control" @keypress="isNumber($event)" :class="{ 'is-invalid': attemptSubmit && missingTo }">
                          <div class="invalid-feedback">This field is required.</div>
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
        from_year: this.selectedData.from_year,
        to_year: this.selectedData.to_year,
      },
    }
  },
  methods: {
    validateForm: function (event) {
      this.attemptSubmit = true;
      if (this.missingTo || this.missingFrom){
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
      axios.post('update-academic-year', {
          id: this.selectedData.id,
          from_year: this.form.from_year,
          to_year: this.form.to_year,
      })
      .then(function (response) {
          self.process = false
          self.isLoading = false;
          self.clear();
          self.close(true);
      })
      .catch(function (error) {
          console.log(error);
          self.process = false
      });
    },

    clear(){
      this.attemptSubmit = false;
      this.form.from_year = '';
      this.form.to_year = '';
    },
    close(data){
      var self = this;
      self.$emit('closeEdit',data);
    }

  },
  created (){
  },
  computed:{
    missingFrom: function () { 
        return this.form.from_year == ''; 
    },
    missingTo: function () { 
        return this.form.to_year == ''; 
    },
  }

};
</script>
