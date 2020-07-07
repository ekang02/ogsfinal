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
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="$emit('close')">No</button>
                <button type="button" class="btn btn-primary" @click="proceed()">Yes</button>
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
  },
  components: {
      
  },
  data(){
    return {
      process: false,
      redirectUrl: '',
    }
  },
  methods: {
    proceed(){
      var self = this;
      window.location.href = self.redirectUrl;
    },
  },
  created (){
    let currentUrl = window.location.href;
    let currentPathName = window.location.pathname.substring(1);
    let newUrl = currentUrl.replace(currentPathName, "teacher/score-sheet");
    this.redirectUrl = newUrl + "?academic_year_id=" + this.form.academic_year.id + "&quarter=" + this.form.quarter.id + "&category=" + this.form.category.id + "&title=" + this.form.title + "&subject=" + this.form.subject.id + "&year=" + this.form.year.id + "&section=" + this.form.section.id;
  },
  computed:{
  }

};
</script>
