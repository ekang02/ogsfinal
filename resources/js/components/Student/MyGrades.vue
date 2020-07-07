<template>
  <div>
    <div class="row mb-3">
      <div class="col-sm-12">
        <div class="card shadow">
          <div class="card-header">
            <h5>My Info</h5>
          </div>
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-3">Student Number</dt>
              <dd class="col-sm-9 text-capitalize">{{ info.student_id }}</dd>
              <dt class="col-sm-3">Name</dt>
              <dd class="col-sm-9 text-capitalize">{{ info.last_name }}, {{ info.first_name }} {{ info.middle_name }}</dd>

              <dt class="col-sm-3">Grade & Section</dt>
              <dd class="col-sm-9"> {{ info.year.year }} - {{ info.section.section }} ({{ info.section.label }})</dd>

              <dt class="col-sm-3">Gender</dt>
              <dd class="col-sm-9 text-capitalize">{{ info.gender }}</dd>

              <dt class="col-sm-3">Age</dt>
              <dd class="col-sm-9">{{ info.age }}</dd>

              <dt class="col-sm-3">Birth Date</dt>
              <dd class="col-sm-9">{{ info.date_of_birth }}</dd>

              <dt class="col-sm-3">Phone Number</dt>
              <dd class="col-sm-9">{{ info.phone_number }}</dd>

              <dt class="col-sm-3">Email Address </dt>
              <dd class="col-sm-9">{{ user.email }}</dd>

              <dt class="col-sm-3">Home Address</dt>
              <dd class="col-sm-9 text-capitalize">{{ info.address }}</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card shadow">
          <div class="card-header">
            <h5>List of Subject</h5>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div class="col-lg-6 pl-0">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <label class="input-group-text bg-primary" for="inputGroupSelect01">Filter By School Year</label>
                  </div>
                  <select v-model="academic_year" class="custom-select" id="inputGroupSelect01" @change="getStudentGrade()">
                     <option v-for="academic_year in academic_years" :value="academic_year" selected="academic_year.isActive === '1'"> {{ academic_year.from_year }} - {{ academic_year.to_year }}</option>
                  </select>
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
                  <b-table :fields="nested_fields" :items="row.item.subject_grades">
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
              <div class="row justify-content-lg-between justify-content-center align-items-center px-3">
                <div class="row align-items-center p-3">
                  <div class="pr-2">
                    Showing Page {{ currentPage }} of {{ totalRows }} entries
                  </div>
                  <div class="d-flex justify-content-center align-items-center">
                    <div>Show</div>
                    <b-form-select :options="pageOptions" v-model="perPage"/>
                    <div> entries</div>
                  </div>
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
    <modalViewMoreCriteria v-if="showModalData" :showModalData.sync="showModalData" :selectedData.sync="selectedData" :isStudent.sync="isStudent" @close="close()"></modalViewMoreCriteria>
  </div>
</template>

<script>
import modalViewMoreCriteria from '../Modal/modalViewMoreCriteria.vue';
export default {
  props:['user'],
  components: {
    modalViewMoreCriteria
  },
  data() {
    return {
      isStudent: true,
      showModalData: false,
      selectedData: null,
      academic_year: '',
      academic_years: [],
      lists: [],
      info: {},
      isBusy: false,
      sortBy: 'id',
      sortDesc: false,
      fields:[
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
      nested_fields:[
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
    year(){
      return this.info.year.yea;
    }
  },
  created(){
    this.getAcademicYears();
    this.getInfo();
  },
  methods: {
    getAcademicYears(){
      var self = this;
      axios.get('/get-academic-years')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.academic_year = result.find(item => item.isActive === '1' || item.isActive === 1);
        self.academic_years = result;
        self.getStudentGrade();
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getInfo(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-user-data',{
        params: {
          user_id: self.user.id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.info = result;
      })
      .catch(function (error) {
        console.log(error);
      });

    },
    getStudentGrade(){
      var self = this;
      self.isBusy = true;
      axios.get('/student/get-student-grade',{
        params: {
          user_id: self.user.id,
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
