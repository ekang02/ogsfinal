<template>
  <div>
    <h3 class="m-3 text-header">
      Add Grade to Subjects
    </h3>
    <div class="row">
      <div class="col-sm-5">
        <div class="card shadow">
          <div class="card-header">
            <h5>List of Subjects</h5>
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
            <div class="col-sm-12 px-0">
              <b-table 
                responsive="md"
                hover
                show-empty
                striped
                selectable
                :items="lists"
                :busy="isBusy"
                :fields="fields"
                :current-page="currentPage"
                :per-page="perPage"
                :filter="filter"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :select-mode=single 
                @row-selected="onRowSelected"
                head-variant="light"
                @filtered="onFiltered"
              > 
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
      <div class="col-sm-7">
        <div class="card shadow">
          <div class="card-header">
            <h5>Options Panel</h5>
          </div>
          <div class="card-body">
            <div v-if="selected !== ''">
              <dl class="row">
                <dt class="col-sm-3 text-header">School Year:</dt>
                <dd class="col-sm-9"> {{ selected.academic_year.academic }}</dd>
                <dt class="col-sm-3 text-header">Subject:</dt>
                <dd class="col-sm-9">{{ selected.label }} ({{ selected.subject_code }})</dd>
              </dl>
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
                              <th scope="col">Action</th>
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
                                  @input="selectChange">
                                </multiselect>
                              </td>
                              <td>
                                <input v-model="grade.percentage" type="text" class="form-control" @keypress="isNumber($event)" @input="percentageChange()" maxlength="3" >
                              </td>
                              <td>
                                <button class="btn btn bg-danger text-white" @click="removeRow(index, idx, grade)">X</button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <div class="text-right">
                          <button @click="addNew(index)" class="btn btn-secondary mt-3 col-sm-2" :disabled="isDisableAdd(index)">Add</button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <hr/>
              <div class="text-right">
                <button type="button" class="btn btn-primary" @click="saveSubjectGrade()" :disabled="isDisableSave">
                  {{ hasGrade ? 'Update' : 'Save'}}
                </button>
              </div>
            </div>
            <div v-else>
              Please select a class from the list of Subject table.
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
      hasGrade: false,
      attemptSubmit: false,
      process: false,
      academic_year: '',
      total_percentage: 0,
      subject_criteria_id: [],
      choosenCategories: {},
      assessments:[],
      years: [],
      year_id: '',
      trackBy: 'id',
      categories: [],
      lists: [],
      single: 'single',
      selected: '',
      isBusy: false,
      sortBy: 'id',
      sortDesc: true,
      fields:[
          
          {
            key: 'id',
            label: '#',
            sortable: true,
            variant: 'success'
          },
          {
            key: 'academic_year.academic',
            label: 'School Year',
            sortable: true
          },
          {
            key: 'label',
            label: 'Subject',
            sortable: true,
          },
          {
            key: 'subject_code',
            label: 'Subject Code',
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
    isDisableSave(){
      if(this.total_percentage === 100){
        return false;
      }else{
        return true;
      }
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
  },
  methods: {

    getAcademicYear(){
      var self = this;
      axios.get('/get-academic-year')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.academic_year = result;
        self.getAssessmentLevel();
        self.getSubjects();
        self.getCriteria();
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getAssessmentLevel(){
      var self = this;
      axios.get('/principal/get-assessment-list',{
        params:{
          academic_year_id: self.academic_year.id
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
    getCriteria(){
      var self = this;
      axios.get('/get-criteria-list',{
        params:{
          academic_year_id: self.academic_year.id
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
    getSubjects(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-subject-list-percentage',{
        params:{
          year_id: self.year_id,
          academic_year_id: self.academic_year.id
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
        console.log(error);
        self.$swal({
          position: 'center',
          type: 'error',
          icon: 'error',
          title: 'Something went wrong',
          showConfirmButton: true,
        })
        self.isBusy = false;
      });
    },
    addNew(index) {
      this.assessments[index].grades.push({category: '', percentage: 0});
    },
    removeRow(index, idx, grade){
      if(this.assessments[index].grades.length > 1){
        this.$delete(this.assessments[index].grades, idx);
        this.percentageChange();
        this.subject_criteria_id.push({'id': grade.id});
      }
    },
    selectChange(val){
      this.percentageChange();
    },
    onRowSelected(items) {
      var self = this;
      self.selected = items[0];
      if(typeof self.selected === 'undefined' || self.selected === ''){
        self.selected = '';
        self.hasGrade = false;
        self.total_percentage = 0;
        self.subject_criteria_id = [];
        self.assessments.forEach((data, index) =>{
          self.$set(self.assessments[index], 'grades', [{ category: '', percentage: 0}]);  
        });
      }else{
        let grades = self.selected.criteria_percentage;
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
      }
    },
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      var self = this;
      self.totalRows = filteredItems.length
      self.currentPage = 1
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
    percentageChange(){      
      let assessment_percentage = [];
      this.assessments.forEach((assessment, index) => {
        let percentage = assessment.grades.map((data, i) => {  return parseInt(data.percentage == '' ? 0 : data.percentage) });
        let sum = percentage.reduce((a,b) => parseInt(a) + parseInt(b));
        assessment_percentage.push(sum);
      });
      let total_percentage = _.sumBy(assessment_percentage, (percentage) => {
        return parseInt(percentage == '' ? 0 : percentage);
      });
      this.total_percentage = parseInt(total_percentage);
    },
    clearYear(){
      this.year_id = '';
      this.grades = [];
      this.getSubjects();
    },

    isDisableAdd(index){
      let lastGradeItem = this.assessments[index].grades.slice(-1).pop();
      let total_percentage = _.sumBy(this.assessments[index].grades,(data) => {
        return parseInt(data.percentage == '' ? 0 : data.percentage);
      });
      if((lastGradeItem.category.length > 0 || lastGradeItem.category == ''  || lastGradeItem.category == null) || (lastGradeItem.percentage == '' || lastGradeItem.percentage == 0)){
        return true;
      }else if(total_percentage >= this.assessments[index].percentage){
        return true;
      }else if(this.total_percentage >= 100){
        return true;
      }else if(this.renderCategories.length == 0){
        return true;
      }else{
        return false;
      }
    },
    saveSubjectGrade() {
      var self = this;
      self.$swal({
          title: 'Are you sure?',
          text: self.hasGrade ?  "You want to Update Criteria Percentage?" : "You want to Add Criteria Percentage?",
          type: 'info',
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#38c172",
          confirmButtonText: "Yes"
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
          if(self.process === true){
            return;
          }
          self.process = true;
          let loadingMessage = self.hasGrade ? "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Updating.....</div>" : "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Saving.....</div>";
          self.$swal({
              html: loadingMessage,
              showConfirmButton: false,
              allowOutsideClick: false
          });
          let url = self.hasGrade ? 'update-subject-criteria-percentage' : 'save-subject-criteria-percentage';
          axios.post(url, {
            academic_year_id: self.academic_year.id,
            subject_id: self.selected.id,
            assessments: self.assessments,
            subject_criteria_ids: self.hasGrade ? self.subject_criteria_id : null
          })
          .then(function (response) {
            self.$swal({
              position: 'center',
              type: 'success',
              icon: 'success',
              title: self.hasGrade ? 'Successfully Updated!' : 'Successfully Saved!',
              showConfirmButton: true,
            })
            self.process = false
            self.clear();
          })
          .catch(function (error) {
            console.log(error);
            self.process = false
          });
        }
      });
    },
    clear(){
      this.selected = '';
      this.grades = [];
      this.getSubjects();
    }
  }

};
</script>
