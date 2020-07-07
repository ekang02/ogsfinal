<template>
    <div v-if="showModalData">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Are you sure to create this Scoresheet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" @click="$emit('close')">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-sm-12">
                  <div class="row justify-content-between">
                    <div class="form-group col-sm-12">
                      <div class="d-flex justify-content-between align-items-center">
                        <label>Quarter:</label>
                        <label>{{ form.quarter.label }}</label>
                      </div>
                    </div>
                    <div class="form-group col-sm-12">
                      <div class="d-flex justify-content-between align-items-center">
                        <label>Category:</label>
                        <label>{{ form.category.label }}</label>
                      </div>
                    </div>
                    <div class="form-group col-sm-12">
                      <div class="d-flex justify-content-between align-items-center">
                        <label>Scoresheet Title:</label>
                        <label>{{ form.title }}</label>
                      </div>
                    </div>
                    <div class="form-group col-sm-12">
                      <div class="d-flex justify-content-between align-items-center">
                        <label>Subject:</label>
                        <label>{{ form.subject.label }}</label>
                      </div>
                    </div>
                    <div class="form-group col-sm-12">
                      <div class="d-flex justify-content-between align-items-center">
                        <label>Grade & Section:</label>
                        <label>{{ form.year.year }} - {{ form.section.section }} ({{ form.section.label }})</label>
                      </div>
                    </div>
                    <div class="form-group col-sm-12">
                      <div class="d-flex justify-content-between align-items-center">
                        <label>Score:</label>
                        <label>{{ form.score }}</label>
                      </div>
                    </div>
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
    form: Object,
    user_id: Number,
  },
  components: {
  },
  data(){
    return {
      isLoading: false,
      redirectUrl: '',
    }
  },
  methods: {
    save(){
      var self = this;
      if(self.process === true){
          return;
      }
      self.isLoading = true;
      self.process = true;
      axios.post( '/teacher/save-score-sheet', {
          id: this.user_id,
          score_sheet_name: this.form.title,
          academic_year_id: this.form.academic_year,
          quarter_id: this.form.quarter.id,
          category_id: this.form.category.id,
          subject_id: this.form.subject.id,
          year_id: this.form.year.id,
          section_id: this.form.section.id,
          score: this.form.score,
          student_score: this.form.studentScore,
      })
      .then(function (response) {
        self.process = false;
        self.isLoading = false;
        self.$emit('close');
        self.$swal({
          position: 'center',
          type: 'success',
          icon: 'success',
          title: 'Successfully Created',
          showConfirmButton: true,
        }).then(()=>{
          window.location.href = self.redirectUrl;
        });
      })
      .catch(function (error) {
          console.log(error);
          self.process = false
      });
    },
  },
  created (){
    let newUrl ="score-sheet-list";
    this.redirectUrl = newUrl;
  },
  computed:{
  }

};
</script>
