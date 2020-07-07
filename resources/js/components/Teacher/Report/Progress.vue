<template>
  <div>
    <h3 class="m-3 text-header">
      Progress Report
    </h3>
    <div class="row">
      <div class="col-sm-4">
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
              <label>Grade</label>
              <select v-model="form.year" class="form-control"  @change="getSectionByYearAndAdvisory()">
                  <option v-for="yr in years" :value="yr.year"> {{ yr.year.year }}</option>
              </select>
              <div class="invalid-feedback">This field is required.</div>
            </div>
            <div class="form-group col-sm-12">
              <label>Section</label>
              <select v-model="form.section" class="form-control" :disabled="isSectionDisabled">
                  <option v-for="section in sections" :value="section"> {{ section.section }} ({{ section.label }})</option>
              </select>
              <div class="invalid-feedback">This field is required.</div>
            </div>
            <!-- <div class="form-group col-sm-12">
              <label>Subject</label>
              <select v-model="form.subject" class="form-control" :disabled="isSubjectDisabled">
                  <option v-for="sub in subjects" :value="sub.subject"> {{ sub.subject.label }}</option>
              </select>
              <div class="invalid-feedback">This field is required.</div>
            </div> -->
            <button @click="viewReport()" class="btn btn-primary btn-lg w-100" :disabled=isDisableView>View Report</button>
          </div>
        </div>
      </div>
      <div class="col-sm-8">
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
                responsive
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
              <div slot="table-busy" class="text-center text-success my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
              <template v-slot:cell(preview_grade)="row">
                <b-button variant="primary" size="sm" @click="preview(row.item)" :disabled="row.item.grades.length == 0">
                  Preview Grades
                </b-button>
              </template>
              <template v-slot:cell(show_details)="row">
                <b-button  size="sm" @click="row.toggleDetails" :disabled="row.item.grades.length == 0">
                  {{ row.detailsShowing ? 'Hide' : 'Show'}} Details
                </b-button>
              </template>
               <template v-slot:row-details="row">
                <b-card class="p-0">
                  <b-table :fields="nested_fields" :items="row.item.grades">
                    <template v-slot:cell(schedule_day)="row">
                      <span v-if="row.item.schedule">
                        {{ row.item.schedule.days }}
                      </span>
                      <span v-else>
                        N/A
                      </span>
                    </template>
                    <template v-slot:cell(schedule_time)="row">
                      <span v-if="row.item.schedule">
                        {{ row.item.schedule.start_time }} - {{ row.item.schedule.end_time }}
                      </span>
                      <span v-else>
                        N/A
                      </span>
                    </template>
                    <template v-slot:cell(show_details)="row">
                      <b-button  size="sm" @click="row.toggleDetails" :disabled="row.item.subject_grades.length == 0">
                        {{ row.detailsShowing ? 'Hide' : 'Show'}} Details
                      </b-button>
                    </template>
                     <template v-slot:row-details="row">
                      <b-card class="p-0">
                        <b-table :fields="quarter_fields" :items="row.item.subject_grades">
                          <template v-slot:cell(show_details)="row">
                            <b-button size="sm" @click="row.toggleDetails">
                              {{ row.detailsShowing ? 'Hide' : 'Show'}} Details
                            </b-button>
                          </template>

                         <template v-slot:row-details="row">
                          <b-card class="p-0">
                            <b-table :fields="criteria_fields" :items="row.item.subject_criteria_grades">
                              <template v-slot:cell(view_more)="row">
                                <b-button size="sm" @click="viewMore(row.item)">
                                  View More
                                </b-button>
                              </template>
                            </b-table>
                          </b-card>
                         </template>
                        </b-table>
                      </b-card>
                    </template>
                  </b-table>
                </b-card>
              </template>
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
    <!-- <modalViewMoreGrade v-if="showModalData" :showModalData.sync="showModalData" :selectedData.sync="selectedData" :selectedSubject.sync="form.subject" @close="close()"></modalViewMoreGrade> -->
    <modalViewMoreCriteria v-if="showModalData" :showModalData.sync="showModalData" :selectedData.sync="selectedData" :isStudent.sync="isStudent" @close="close()"></modalViewMoreCriteria>
  </div>
</template>

<script>
import modalViewMoreCriteria from '../../Modal/modalViewMoreCriteria.vue';
export default {
  props:['user'],
  components: {
    modalViewMoreCriteria
  },
  data() {
    return {
      isStudent: false,
      showModalData: false,
      selectedData: null,
      form:{
        academic_year: '',
        year: '',
        section: '',
        subject: '',
        quarter: '',
      },
      years: [],
      subjects: [],
      sections: [],
      grades: [],
      lists: [],
      isBusy: false,
      sortBy: 'id',
      sortDesc: false,
      fields:[
          {
            key: 'student_id',
            label: 'Student Number',
            sortable: true,
          },
          {
            key: 'first_name',
            label: 'Student Name',
            sortable: true,
          },
          {
            key: 'preview_grade',
            label: '',
            'class': 'text-center' 
          },
          {
            key: 'show_details',
            label: '',
            'class': 'text-center' 
          },
        ],
      nested_fields:[
        {
          key: 'subject.label',
          label: 'Subject',
          sortable: true,
        },
        {
          key: 'subject.subject_code',
          label: 'Subject Code',
          sortable: true,
        },
        {
          key: 'schedule_day',
          label: 'Days',
          sortable: true,
        },
        {
          key: 'schedule_time',
          label: 'Time',
          sortable: true,
        },
        {
          key: 'show_details',
          label: '',
          'class': 'text-center' 
        },
      ],
      quarter_fields:[
        {
          key: 'quarter.label',
          label: 'Quarter',
          sortable: true,
        },
        {
          key: 'initial_grade',
          label: 'Initial Grade',
          sortable: true,
        },
        {
          key: 'final_grade',
          label: 'Final Grade',
          sortable: true,
        },
        {
          key: 'show_details',
          label: '',
          'class': 'text-center' 
        },
      ],
      criteria_fields:[
        {
          key: 'criteria.label',
          label: 'Criteria',
          sortable: true,
        },
        {
          key: 'total_score',
          label: 'Total Score',
          sortable: true,
        },
        {
          key: 'criteria_total_score',
          label: 'Criteria Total Score',
          sortable: true,
        },
        {
          key: 'view_more',
          label: '',
          'class': 'text-center' 
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
    isDisableView(){
      return this.form.section ? false : true;
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
          academic_year_id: self.form.academic_year.id
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
    getSectionByYearAndAdvisory(){
      console.log('asdas');
      var self = this;
      axios.get('/get-advisory-section',{
        params:{
          user_id: self.user.id,
          year_id: self.form.year.id,
          academic_year_id: self.form.academic_year.id
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
    getStudentGrade(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-students-grade',{
        params: {
          section_id: self.form.section.id,
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
        self.loaded = false;
        console.log(error);
      });
    },
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      var self = this;
      self.totalRows = filteredItems.length
      self.currentPage = 1
    },

    viewReport(){
      this.getStudentGrade();
      // var self = this;
      // self.$htmlToPaper('printMe');
    },
    preview(data){
      let newUrl ="progress-report/student?academic_year_id=" + this.form.academic_year.id + "&id=" + data.user_id;
      window.open(newUrl,'_blank')
    },
    viewMore(data){
      this.selectedData = data;
      this.openModal();
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
