<template>
    <div class="">
        <h3 class="m-3 text-header">
            Add Score Sheet
        </h3>
        <div class="row">
          <div class="col-md-8 mx-auto">
            <div class="card shadow">
              <div class="card-header">
                <h5>Create Scoresheet</h5>
              </div>
              <div class="card-body">
                <div class="form-group col-sm-12">
                  <label>School Year</label>
                  <input type="text":value="academic_year_value" placeholder="School Year" class="form-control" readonly="true">
                </div>
                <div class="form-group col-sm-12">
                  <label>Choose Quarter</label>
                  <select v-model="form.quarter" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingQuarter }">
                      <option v-for="qty in quarters" :value="qty"> {{ qty.label }}</option>
                  </select>
                  <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-12">
                  <label>What Grade to Record?</label>
                  <select v-model="form.year" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingYear }" @change="getSectionByYear()">
                      <option v-for="yr in years" :value="yr.year"> {{ yr.year.year }}</option>
                  </select>
                  <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-12">
                  <label>What Section to Record?</label>
                  <select v-model="form.section" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSection }" @change="getSubjectByTeacherSubject()" :disabled="isSectionDisabled">
                      <option v-for="section in sections" :value="section"> {{ section.section }} ({{ section.label }})</option>
                  </select>
                  <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-12">
                  <label>Who Subject to Record?</label>
                  <select v-model="form.subject" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSubject }" @change="getCriteriaBySubject()" :disabled="isSubjectDisabled">
                        <option v-for="sub in subjects" :value="sub.subject"> {{ sub.subject.label }}</option>
                    </select>
                  <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-12">
                  <label>Choose Category</label>
                  <select v-model="form.category" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingCategory }">
                      <option v-for="cat in categories" :value="cat.criteria"> {{ cat.criteria.label }}</option>
                  </select>
                  <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-12">
                  <label>Scoresheet Title</label>
                  <input v-model="form.title" type="text" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingScoreSheetTitle }">
                  <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-12"> 
                  <button class="btn btn-primary w-100" @click="validateForm()">Proceed to Create Scoresheet</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      <modalData v-if="showModalData" :showModalData.sync="showModalData" :form.sync="form" @close="close()"></modalData>
    </div>
</template>

<script>
import modalData from '../../Modal/modalData.vue';
export default {
  props:['user'],
  components: {
    modalData,

  },
  data() {
    return {
      attemptSubmit: false,
      showModalData: false,
      form:{
        academic_year: '',
        category: '',
        title: '',
        subject: '',
        year: '',
        section: '',
        quarter: '',
      },
      search_student: '',
      activity: '',
      record: '',
      first_quarter: '',
      second_quarter: '',
      third_quarter: '',
      fourth_quarter: '',
      categories: [],
      subjects: [],
      years: [],
      sections: [],
      quarters: [],
      lists: [],
      isBusy: false,
      sortBy: 'id',
      sortDesc: true,
      fields:[
          {
            key: 'id',
            label: 'ID',
            sortable: true,
          },
          {
            key: 'year.year',
            label: 'Grade',
            sortable: true,
          },
          {
            key: 'section.full',
            label: 'Section',
            sortable: true,
          },
          {
            key: 'subject.label',
            label: 'Subject',
            sortable: true,
          },
          {
            key: 'score_sheet_name',
            label: 'Score Sheet Title ',
            sortable: true,
          },
          {
            key: 'criteria.label',
            label: 'Category',
            sortable: true,
          },
          {
            key: 'quarter.label',
            label: 'Quarter',
            sortable: true,
          },
          {
            key: 'total_score',
            label: 'Total Score',
            sortable: true,
          },
      ],
      pageOptions: [5,10,50,100],
      perPage: 10,
      totalRows: 0,
      filter: null,
      currentPage: 1,
    };
  },
  created(){
    this.getAcademicYear();
    this.getQuarter();
    this.getYear();
  },
  computed: {
    academic_year_value(){
      return this.form.academic_year ? this.form.academic_year.from_year + ' - ' + this.form.academic_year.to_year : '';
    },
    missingCategory: function () { 
        return this.form.category == ''; 
    },
    missingScoreSheetTitle: function () { 
        return this.form.title == ''; 
    },
    missingSubject: function () { 
        return this.form.subject == ''; 
    },
    missingYear: function () { 
        return this.form.year == ''; 
    },
    missingSection: function () { 
        return this.form.section == ''; 
    },
    missingQuarter: function () { 
        return this.form.quarter == ''; 
    },

    isSubjectDisabled:function(){
      return this.subjects == '';
    },
    isSectionDisabled:function(){
      return this.sections == '';
    }

  },
  methods: {

    getAcademicYear(){
      var self = this;
      axios.get('/get-academic-year')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.form.academic_year = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getYear(){
      var self = this;
      self.sections = [];
      axios.get('/get-year-user',{
        params:{
          user_id: self.user.id,
          academic_year_id: self.form.academic_year.id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.years = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getCriteriaBySubject(){
      var self = this;
      axios.get('/get-criteria-list',{
        params: {
          subject_id: self.form.subject.id,
          academic_year_id: self.form.academic_year.id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.categories = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSectionByYear(){
      var self = this;
      axios.get('/get-year-section',{
        params:{
          year_id: this.form.year.id,
          academic_year_id: self.form.academic_year.id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.sections = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSubjectByYear(){
      var self = this;
      self.subjects = [];
      axios.get('/get-subject',{
        params:{
          year_id: self.form.year.id,
          academic_year_id: self.form.academic_year.id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.subjects = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getQuarter(){
      var self = this;
      axios.get('/get-quarter')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.quarters = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getSubjectByTeacherSubject(){
      var self = this;
      self.subjects = [];
      axios.get('/get-subject-section-teacher',{
        params:{
          user_id: self.user.id,
          year_id: self.form.year.id,
          section_id: self.form.section.id,
          academic_year_id: self.form.academic_year.id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.subjects = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    validateForm: function (event) {
      this.attemptSubmit = true;
      if (this.missingSubject || this.missingSubjectCode || this.missingCategory || this.missingYear || this.missingSection || this.missingScoreSheetTitle || this.missingQuarter){
        event.preventDefault();
      }else{
        this.openModal();
      }
      
    },

    openModal() {
      var self = this;
      self.showModalData = true;
    },
    close(){
      var self = this;
      self.showModalData = false;
    }
  }

};
</script>
