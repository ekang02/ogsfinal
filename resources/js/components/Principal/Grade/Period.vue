<template>
  <div>
    <h3 class="m-3 text-header">
      Grades per Period
    </h3>
    <div class="row">
      <div class="col-sm-5">
        <div class="card shadow">
          <div class="card-header">
            <h5>Details</h5>
          </div>
          <div class="card-body">
            <div class="form-group col-sm-12">
              <label>School year</label>
              <input type="text":value="academic_year_value" placeholder="School Year" class="form-control" readonly="true">
            </div>
            <div class="form-group col-sm-12">
              <label>Quarter:</label>
              <select v-model="form.quarter" class="form-control">
                  <option v-for="qty in quarters" :value="qty"> {{ qty.label }}</option>
              </select>
            </div>
            <div class="form-group col-sm-12">
              <label>Grade</label>
              <select v-model="form.year" class="form-control"  @change="getSectionByYear()">
                  <option v-for="yr in years" :value="yr"> {{ yr.year }}</option>
              </select>
              <div class="invalid-feedback">This field is required.</div>
            </div>
            <div class="form-group col-sm-12">
              <label>Section</label>
              <select v-model="form.section" class="form-control"  @change="getSubjectBySection()" :disabled="isSectionDisabled">
                  <option v-for="section in sections" :value="section"> {{ section.section }} ({{ section.label }})</option>
              </select>
              <div class="invalid-feedback">This field is required.</div>
            </div>
            <div class="form-group col-sm-12">
              <label>Subject</label>
              <select v-model="form.subject" class="form-control" :disabled="isSubjectDisabled">
                  <option v-for="sub in subjects" :value="sub.subject"> {{ sub.subject.label }}</option>
              </select>
              <div class="invalid-feedback">This field is required.</div>
            </div>
            <hr/>
            <button class="btn btn-primary btn-lg w-100" @click="viewGrade()" :disabled="isViewDisabled">View Grade</button>
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
      
      academic_year: '',
      form:{
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
      sortDesc: true,
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
            key: 'academic_year.academic',
            label: 'School Year',
            sortable: true
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
      return this.academic_year ? this.academic_year.from_year + ' - ' + this.academic_year.to_year : '';
    },
    isViewDisabled(){
      return this.form.section == "" || this.form.subject == "" ? true : false;
    },
    isSubjectDisabled(){
      return this.subjects == '';
    },
    isSectionDisabled(){
      return this.sections == '';
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
        self.academic_year = result;
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

    getYear(){
      var self = this;
      self.sections = [];
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

    getSectionByYear(){
      var self = this;
      axios.get('/get-year-section',{
        params:{
          year_id: this.form.year.id,
          academic_year_id: this.academic_year.id
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
          section_id: self.form.section.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.subject_schedule = result[0];
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getSubjectBySection(){
      var self = this;
      self.subjects = [];
      axios.get('/get-subject',{
        params:{
          section_id: self.form.section.id,
          academic_year_id: this.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.subjects = result.subject_sections;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    viewGrade(){
      var self = this;
      self.$swal({
          html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Fetching.....</div>",
          showConfirmButton: false,
          allowOutsideClick: false
      });
      axios.get('/get-student-grade',{
        params: {
          quarter_id: self.form.quarter.id,
          section_id: self.form.section.id,
          subject_id: self.form.subject.id,
          academic_year_id: this.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.lists = result;
        self.totalRows = result.length;
        self.isBusy = false;
        self.loaded = true;
        if(result.length > 0){
          self.$swal({
            position: 'center',
            type: 'success',
            icon: 'success',
            title: 'Successfully Fetched',
            showConfirmButton: true,
          });
        }else{
          self.$swal({
            position: 'center',
            type: 'info',
            icon: 'info',
            title: 'No Record Found',
            showConfirmButton: true,
          });
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
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      var self = this;
      self.totalRows = filteredItems.length
      self.currentPage = 1
    },
  }

};
</script>
