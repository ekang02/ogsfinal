<template>
  <div>
    <h3 class="m-3 text-header">
      Subject List Per Section
    </h3>
    <div class="row">
      <div class="col-sm-4">
        <div class="card shadow">
          <div class="card-header">
            <h5>Input Details</h5>
          </div>
          <div class="card-body">
            <div class="form-group col-sm-12">
              <label>Schoole Year</label>
              <select v-model="form.academic_year" class="form-control">
                  <option v-for="academic_year in academic_years" :value="academic_year"> {{ academic_year.from_year }} - {{ academic_year.to_year }}</option>
              </select>
            </div>
            <div class="form-group col-sm-12">
              <label>Grade</label>
              <select v-model="form.year" class="form-control" @change="getSectionByYear()">
                  <option v-for="yr in years" :value="yr"> {{ yr.year }}</option>
              </select>
              <div class="invalid-feedback">This field is required.</div>
            </div>
            <div class="form-group col-sm-12">
              <label>Section</label>
              <select v-model="form.section" class="form-control" @change="getSubjectBySection()" :disabled="isSectionDisabled">
                  <option v-for="section in sections" :value="section"> {{ section.section }} ({{ section.label }})</option>
              </select>
              <div class="invalid-feedback">This field is required.</div>
            </div>
            <hr/>
            <button class="btn btn-primary btn-lg w-100" @click="view" :disabled="isViewDisabled">View</button>
          </div>
        </div>
      </div>
      <div class="col-sm-8">
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
              <div slot="table-busy" class="text-center text-success my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>

              <template v-slot:cell(subject)="row">
                {{ form.subject.label }}
              </template>
              <template v-slot:cell(view_more)="row">
                <b-button  size="sm" @click="viewMore(row.item)">
                  View More
                </b-button>
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
    <modalViewStudent v-if="showModalDataView" :showModalDataView.sync="showModalDataView" :selectedData.sync="selectedData" :viewMode.sync="viewMode" @closeView="closeView"></modalViewStudent>

  </div>
</template>

<script>
import pagination from '../../Pagination/Pagination.vue';
import modalViewStudent from '../../Modal/modalViewStudent.vue';
export default {
  props:['user'],
  components: {
    modalViewStudent,

  },
  data() {
    return {
      showModalDataView: false,
      selectedData: null,
      viewMode: true,
      form:{
        academic_year: '',
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
            key: 'id',
            label: '#',
            sortable: true,
            variant: 'success'
          },
          {
            key: 'year.year',
            label: 'Grade',
            sortable: true
          },
          {
            key: 'subject.label',
            label: 'Subject',
            sortable: true
          },
          {
            key: 'subject.subject_code',
            label: 'Subject Code',
            sortable: true
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
    isViewDisabled(){
      return this.form.section == "" ? true : false;
    },
    isSectionDisabled(){
      return this.sections == '';
    },  
    
  },
  created(){
    this.getAcademicYears();
    this.getYear();
  },
  methods: {
    getAcademicYears(){
      var self = this;
      axios.get('/get-academic-years')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.academic_years = result;
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
          academic_year_id: this.form.academic_year.id
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

    view(){
      var self = this;
      self.$swal({
          html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Fetching.....</div>",
          showConfirmButton: false,
          allowOutsideClick: false
      });
      self.isBusy = true;
      axios.get('/get-subject-section',{
        params: {
          section_id: self.form.section.id,
          academic_year_id: this.form.academic_year.id
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
            title: 'No Records Found',
            showConfirmButton: true,
          });
        }
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

    viewMore(data){
      this.selectedData = data;
      this.openModal();
    },
    openModal() {
      var self = this;
      self.showModalDataView = true;
    },
    closeView(){
      var self = this;
      self.showModalDataView = false;
    },
  }

};
</script>
