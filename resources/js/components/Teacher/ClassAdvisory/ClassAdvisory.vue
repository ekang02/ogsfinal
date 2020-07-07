<template>
  <div class="">
    <h3 class="m-3 text-header">
        Class Advisory
    </h3>
    <hr/>
    <div class="mx-3">
      <div class="form-group col-sm-12">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold">My Advisory Class List</h6>
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
                    <span class="input-group-text" id="basic-addon2">Search</span>
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
              @filtered="onFiltered"
            >
              <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
              
              <template v-slot:cell(show_details)="row">
                <b-button  v-if="row.item.section.students.length > 0" size="sm" @click="row.toggleDetails" class="mr-2">
                  {{ row.detailsShowing ? 'Hide' : 'Show'}} Details
                </b-button>
              </template>
               <template v-slot:row-details="row">
                <b-card class="p-0">
                  <b-table :fields="nested_fields" :items="row.item.section.students"></b-table>
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
  </div>
</template>

<script>
export default {
  props:['user'],
  components: {
  },
  data() {
    return {
      loaded: false,
      academic_year: '',
      lists: [],
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
          key: 'section.academic_year.academic',
          label: 'School Year',
          sortable: true
        },
        {
          key: 'section.full',
          label: 'Section',
          sortable: true,
        },
        {
          key: 'section.students.length',
          label: 'Total Students',
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
          key: 'student_id',
          label: 'Student #',
          sortable: true,
        },
        {
          key: 'full_name',
          label: 'Student Fullname',
          sortable: true,
        },
        {
          key: 'age',
          label: 'Age',
          sortable: true,
        },
        {
          key: 'gender',
          label: 'Gender',
          sortable: true,
        },
        {
          key: 'date_of_birth',
          label: 'Date of Birth',
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
  },
  computed: {


  },
  methods: {
    getAcademicYear(){
      var self = this;
      axios.get('/get-academic-year')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.academic_year = result;
        self.getClassAdvisoryList();
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getClassAdvisoryList(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-class-advisory-list', {
        params: {
          user_id: self.user.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.lists = result[0].advisory;
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
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      var self = this;
      self.totalRows = filteredItems.length
      self.currentPage = 1
    },
  }
};
</script>
