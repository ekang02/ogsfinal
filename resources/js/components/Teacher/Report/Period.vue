<template>
  <div>
    <h3 class="m-3 text-header">
      Computation per Period
    </h3>
    <div class="row">
      <div class="col-sm-5">
        <div class="card shadow">
          <div class="card-header">
            <h5>Input Details</h5>
          </div>
          <div class="card-body">
            <div class="form-group col-sm-12">
              <label>School Year</label>
              <input type="text":value="academic_year_value" placeholder="School Year" class="form-control" readonly="true">
            </div>
            <div class="form-group col-sm-12">
              <label>Quarter:</label>
              <select v-model="form.quarter" class="form-control" @change="getGrades()">
                  <option v-for="qty in quarters" :value="qty"> {{ qty.label }}</option>
              </select>
            </div>
            <div class="form-group col-sm-12">
              <label>Grade</label>
              <select v-model="form.year" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingYear }" @change="getSectionByYear()">
                  <option v-for="yr in years" :value="yr.year"> {{ yr.year.year }}</option>
              </select>
              <div class="invalid-feedback">This field is required.</div>
            </div>
            <div class="form-group col-sm-12">
              <label>Section</label>
              <select v-model="form.section" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSection }" @change="getSubjectByTeacherSubject()" :disabled="isSectionDisabled">
                  <option v-for="section in sections" :value="section"> {{ section.section }} ({{ section.label }})</option>
              </select>
              <div class="invalid-feedback">This field is required.</div>
            </div>
            <div class="form-group col-sm-12">
              <label>Subject</label>
              <select v-model="form.subject" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSubject }" @change="getSubjectSchedule()" :disabled="isSubjectDisabled">
                  <option v-for="sub in subjects" :value="sub.subject"> {{ sub.subject.label }}</option>
              </select>
              <div class="invalid-feedback">This field is required.</div>
            </div>
            <div v-if="form.section !== '' && form.subject !== '' && form.quarter !== ''" class="col-sm-12">
              <div class="row justify-content-between">
                <div class="col-lg-6">
                  <div>
                    {{ form.subject.subject_code }} | {{ form.subject.label }}
                  </div>
                </div>
                <div class="col-lg-6 text-right">
                  <h4 class="text-header">
                    {{ form.section.section }} ({{ form.section.label }})
                  </h4>
                </div>
              </div>
              <div>
                {{ subject_schedule.days }} | {{ subject_schedule.start_time }} - {{ subject_schedule.end_time }}
              </div>
              <div>
                <hr/>
                <label>Grade Attributes = <span class="text-blue">{{ total_percentage }}%</span></label>
                <table class="table">
                  <tbody>
                    <tr v-for="(assessment, index) in assessments">
                      <td>
                      <div class="my-3">
                        <h5> {{ assessment.label }} </h5>  
                        <h6> Assessment Percentage: {{ assessment.percentage }} % </h6>
                      </div>                        
                      <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">Criteria</th>
                              <th scope="col">Percentage</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(grade, idx) in assessment.grades">
                              <td>
                                 <multiselect 
                                  v-model="grade.category" 
                                  class="px-0" 
                                  placeholder="Search Criteria" 
                                  label="label" 
                                  track-by="id" 
                                  :options="renderCategories" 
                                  :multiple="false"
                                  :close-on-select="true"
                                  disabled>
                                </multiselect>
                              </td>
                              <td>
                                <input v-model="grade.percentage" type="text" class="form-control" disabled>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <hr/>
              <button class="btn btn-primary btn-lg w-100" @click="compute()" :disabled="isRunDisabled">Run Computation</button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-7">
        <div class="card shadow">
          <div class="card-header">
            <h5>List of Students</h5>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div class="">
                  <div class="d-flex justify-content-center align-items-center">
                      <div>Show</div>
                      <b-form-select :options="pageOptions" v-model="perPage"/>
                      <div> entries</div>
                  </div>
              </div>
              <div class="input-group mb-3 col-lg-6 px-0">
                <input type="text" class="form-control" v-model="filter" placeholder="" aria-label="" aria-describedby="basic-addon2">
                <div class="input-group-append">
                  <span class="input-group-text bg-primary text-white" id="basic-addon2">Search</span>
                </div>
              </div>
            </div>
              <b-table 
                responsive="md"
                hover
                show-empty
                striped
                :items="lists"
                :busy="isBusy"
                :fields="fields"
                :current-page="currentPage"
                :per-page="perPage"
                :filter="filter"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                head-variant="light"
                @filtered="onFiltered"
              >
              <template v-slot:cell(grades)="row">
                <b-form-input v-model="row.item.grades" readonly=""/>
              </template>
              <div slot="table-busy" class="text-center text-success my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
              </b-table>
              <div class="row justify-content-lg-between justify-content-center align-items-center px-3">
                <div class="py-3">
                    Showing Page {{ currentPage }} of {{ totalRows }} entries
                </div>
                <b-pagination 
                  v-model="currentPage" 
                  :total-rows="totalRows" 
                  :per-page="perPage"
                  first-number
                  last-number
                ></b-pagination>
              </div>
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
      
      attemptSubmit: false,
      form:{
        academic_year: '',
        quarter: '',
        year: '',
        section: '',
        subject: '',
        quarter: '',
      },
      grades: [],
      assessments:[],
      trackBy: 'id',
      categories: [],
      total_percentage: 0,
      subjects: [],
      years: [],
      sections: [],
      subject_schedule: '',
      quarters: [],
      lists: [],
      isBusy: false,
      sortBy: 'id',
      sortDesc: false,
      fields:[
          {
            key: 'student_info.student_id',
            label: 'Student Number',
            sortable: true,
          },
          {
            key: 'student_info.full_name',
            label: 'Student Name',
            sortable: true,
          },
          {
            key: 'subject.label',
            label: 'Subject',
            sortable: true,
          },
          {
            key: 'initial_grade',
            label: 'Initial Grade',
            sortable: true,
          },
          {
            key: 'final_grade',
            label: 'Transmuted Grade',
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
  computed: {
    academic_year_value(){
      return this.form.academic_year ? this.form.academic_year.from_year + ' - ' + this.form.academic_year.to_year : '';
    },
    isRunDisabled(){
      return this.total_percentage < 100;
    },
    isSubjectDisabled(){
      return this.subjects == '';
    },
    isSectionDisabled(){
      return this.sections == '';
    },

    renderCategories(){
      let categories =  this.categories;
      if(this.selected != ''){
        this.assessments.forEach((data, index) =>{
          let grades = data.grades;
          let choosenCategories = _.map(grades,'category').reduce((c, v) => c.concat(v), []).map(o => o.id);
          categories = _.filter(categories,(obj) => {
            return choosenCategories.indexOf( obj.id ) == -1; 
          });
          return categories;
        });
      }
      return categories;
    },
    
  },
  created(){
    this.getAcademicYear();
    this.getYear();
    this.getQuarter();
  },
  methods: {
    getAcademicYear(){
      var self = this;
      axios.get('/get-academic-year')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.form.academic_year = result;
        self.getCriteria();
        self.getAssessmentLevel();
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getAssessmentLevel(){
      var self = this;
      axios.get('/get-assessments',{
        params: {
          academic_year_id: self.form.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.assessments = result;
      })
      .catch(function (error) {
        console.log(error);
        self.$swal({
            position: 'center',
            icon: 'error',
            title: 'Something went wrong',
            showConfirmButton: true,
        })
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
    getCriteria(){
      var self = this;
      axios.get('/get-criteria-list',{
        params: {
          academic_year_id: self.form.academic_year.id
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

    getYear(){
      var self = this;
      self.sections = [];
      axios.get('/get-year-user',{
        params:{
          user_id: self.user.id
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

    getSectionByYear(){
      var self = this;
      axios.get('/get-year-section',{
        params:{
          year_id: this.form.year.id,
          academic_year_id: self.form.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.sections = result;
        self.getGrades();
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
          academic_year_id: self.form.academic_year.id
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

    getSubjectSchedule(){
      var self = this;
      axios.get('/get-subject-schedule',{
        params:{
          user_id: self.user.id,
          section_id: self.form.section.id,
          academic_year_id: self.form.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.subject_schedule = result[0];
        self.getCriteriaPercentageBySubject();
        self.getGrades();
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getCriteriaPercentageBySubject(){
      var self = this;
      axios.get('/get-subject-criteria',{
        params:{
          subject_id: self.form.subject.id,
          academic_year_id: self.form.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        // self.grades = result;
        let grades = result;
        if(grades.length > 0){
          self.hasGrade = true;
          self.assessments.forEach((data, index) =>{
            self.$set(self.assessments[index], 'grades', []);
            grades.forEach((cat, idx) =>{
              let catData = _.find(self.categories, (criteria) => cat.criteria_id == criteria.id );
              if(data.id == cat.assessment_id){
                self.assessments[index].grades.push({id: cat.id, category: catData, percentage: cat.percentage});
              }
            });  
          });   
          self.percentageChange();
        }else{
          self.assessments.forEach((data, index) =>{
            self.$set(self.assessments[index], 'grades', [{ category: '', percentage: 0}]);  
          });
          self.hasGrade = false;
          self.total_percentage = 0;
          self.assessment = 0;
          self.assessment_length = 0;
          self.subject_criteria_id = [];
        }
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    
    percentageChange(){    
      var self = this;  
      let assessment_percentage = [];
      self.assessments.forEach((assessment, index) => {
        let percentage = assessment.grades.map((data, i) => {  return parseInt(data.percentage == '' ? 0 : data.percentage) });
        let sum = percentage.reduce((a,b) => parseInt(a) + parseInt(b));
        assessment_percentage.push(sum);
      });
      let total_percentage = _.sumBy(assessment_percentage, (percentage) => {
        return parseInt(percentage == '' ? 0 : percentage);
      });
      self.total_percentage = parseInt(total_percentage);
    },

    getSubjectByTeacherSubject(){
      var self = this;
      self.subjects = [];
      axios.get('/get-subject-section-teacher',{
        params:{
          user_id: self.user.id,
          year_id: self.form.year.id,
          section_id: self.form.section.id,
          academic_year_id: self.form.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.subjects = result;
        self.getGrades();
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    compute(){
      var self = this;
      self.$swal({
          html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Computing.....</div>",
          showConfirmButton: false,
          allowOutsideClick: false
      });
      axios.post('/get-compute-subject-grade',{
        user_id: self.user.id,
        quarter_id: self.form.quarter.id,
        year_id: self.form.year.id,
        section_id: self.form.section.id,
        subject_id: self.form.subject.id,
        academic_year_id: self.form.academic_year.id
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        if(result.length > 0){
          self.$swal({
            position: 'center',
            type: 'success',
            icon: 'success',
            title: 'Successfully Computed',
            showConfirmButton: true,
          });
          self.getStudentGrade();
        }else{

          self.$swal({
            position: 'center',
            type: 'info',
            icon: 'info',
            title: 'No Records Found',
            showConfirmButton: true,
          });
          self.lists = [];
        }
      })
      .catch(function (error) {
        self.$swal({
          position: 'center',
          type: 'error',
          icon: 'error',
          title: 'Error Occured',
          showConfirmButton: true,
        })
        console.log(error);
      });
    },
    getGrades(){
      if(this.form.quarter && this.form.section && this.form.subject){
        this.getStudentGrade();
      }
    },
    getStudentGrade(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-student-grade',{
        params: {
          quarter_id: self.form.quarter.id,
          section_id: self.form.section.id,
          subject_id: self.form.subject.id,
          academic_year_id: self.form.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.lists = result;
        self.totalRows = result.length;
        self.isBusy = false;
        self.loaded = true;
      })
      .catch(function (error) {
        
        self.isBusy = false;
        self.$swal({
          position: 'center',
          type: 'error',
          icon: 'error',
          title: 'Error Occured',
          showConfirmButton: true,
        })
        console.log(error);
      });
    },
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      var self = this;
      self.totalRows = filteredItems.length
      self.currentPage = 1
    },
  }

};
</script>
