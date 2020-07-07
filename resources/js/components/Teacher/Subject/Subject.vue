<template>
  <div>
    <h3 class="m-3 text-header">
      Subjects
    </h3>
    <div class="row">
      <div class="col-sm-6">
        <div class="card shadow">
          <div class="card-header">
            <h5>List of Subjects</h5>
          </div>
          <div class="card-body">
            <div class="col-sm-12 px-0">
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
                <template v-slot:cell(total_section)="row">
                  {{ row.item.schedule_section.length }}
                </template>
                <template v-slot:cell(show_details)="row">
                  <b-button size="sm" @click="row.toggleDetails" class="mr-2">
                    {{ row.detailsShowing ? 'Hide' : 'Show'}} Details
                  </b-button>
                </template>
                 <template v-slot:row-details="row">
                  <b-card>
                    <b-table selectable :fields="nested_fields" :items="row.item.schedule_section" :select-mode=single @row-selected="onRowSelected"></b-table>
                  </b-card>
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
      <div class="col-sm-6">
        <div class="card shadow">
          <div class="card-header">
            <h5> Student List</h5>
          </div>
          <div class="card-body">
            <div v-if="isShowList">
              <StudentList :section_id.sync="selected.section_id" :academic_year_id.sync="selected.academic_year_id"></StudentList>
            </div>
            <div v-else>
              Please select a Section from the list of Subject Table.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StudentList from './StudentList.vue';
export default {
  props:['user'],
  components: {
    StudentList,
  },
  data() {
    return {
      isShowList: false,
      lists: [],
      single: 'single',
      selected:[],
      academic_year: '',
      isBusy: false,
      sortBy: 'id',
      sortDesc: false,
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
            key: 'year.year',
            label: 'Year',
            sortable: true
          },
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
            key: 'total_section',
            label: 'Sections',
          },
          {
            key: 'show_details',
            label: '',
            'class': 'text-center' 
          },
      ],
      nested_fields:[
          {
            key: 'section.full',
            label: 'Section',
            sortable: true,
            variant: 'success'
          },
          {
            key: 'days',
            label: 'Scheduled Days',
            sortable: true
          },
          {
            key: 'start_time',
            label: 'Start Time',
            sortable: true,
          },
          {
            key: 'end_time',
            label: 'End Time',
            sortable: true,
          },
          {
            key: 'section.students.length',
            label: 'Total Students',
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
        self.getSubjects();
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSubjects(){
      var self = this;
      self.isBusy = true;
      axios.get('/teacher/get-subjects',{
        params:{
          user_id: this.user.id,
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
    onRowSelected(items) {
      this.selected = items[0];
      if(typeof this.selected === 'undefined' || this.selected === ''){
        this.selected = [];
        this.isShowList = false;
      }else{
        this.isShowList = true;
      }
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
