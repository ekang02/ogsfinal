<template>
  <div class="">
    <h3 class="m-3 text-header">
        Section
    </h3>
    <hr/>
    <div class="mx-3">
      <div class="row justify-content-between">
        <div class="form-group col-sm-4">
          <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Add New Section</h6>
            </div>
            <div class="card-body">
                <div class="row">
                  <div class="form-group col-sm-12">
                    <label>School year</label>
                    <input type="text":value="academic_year_value" placeholder="School Year" class="form-control" readonly="true">
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Grade</label>
                    <select v-model="year" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingYear }">
                        <option v-for="year in years" :value="year"> {{ year.year }}</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                      <label>Section</label>
                      <input type="text" v-model="section" placeholder="Input Section" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSection }">
                      <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                      <label>Section Label</label>
                      <input type="text" v-model="section_label" placeholder="Input Section Label" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSubjectCode }">
                      <div class="invalid-feedback">This field is required.</div>
                  </div>
                </div>
                <div class="text-right">
                  <button class="btn btn-outline-secondary" @click="clear()">Clear</button>
                  <button class="btn btn-primary" @click="validateForm">Save</button>
                </div>
            </div>
          </div>
        </div>
        <div class="form-group col-sm-8">
          <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Section List</h6>
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

                <template v-slot:cell(actions)="row">
                  <button class="btn btn-primary" @click="editSection(row.item)">
                    Edit
                  </button>
                  <button class="btn btn-danger" @click="deleteSection(row.item.id)">
                    Delete
                  </button>
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
    <modalEditSection v-if="showModalData" :showModalData.sync="showModalData" :selectedSection.sync="selectedSection" @close="close()"></modalEditSection>
  </div>
</template>

<script>
import modalEditSection from '../../Modal/modalEditSection.vue';
export default {
  props:['user'],
  components: {
    modalEditSection,
  },
  data() {
    return {
      showModalData: false,
      process: false,
      selectedSection: '',
      academic_year: '',
      year: '',
      section: '',
      section_label: '',
      years: [],
      attemptSubmit: false,
      loaded: false,
      lists: [],
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
            key: 'year.year',
            label: 'Grade',
            sortable: true
          },
          {
            key: 'section',
            label: 'Section',
            sortable: true
          },
          {
            key: 'label',
            label: 'Section Name',
            sortable: true
          },
          {
            key: 'actions',
            label: 'Actions',
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
  created(){
    this.getAcademicYear();
    this.getYear();
  },
  computed: {
    academic_year_value(){
      return this.academic_year ? this.academic_year.from_year + ' - ' + this.academic_year.to_year : '';
    },
    missingSection: function () { 
      return this.section == ''; 
    },
    missingSectionLabel: function () { 
      return this.section_label == ''; 
    },
    missingYear: function () { 
      return this.year == ''; 
    },

  },
  methods: {

    getAcademicYear(){
      var self = this;
      axios.get('/get-academic-year')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.academic_year = result;
        self.getSectionList();
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getYear(){
      var self = this;
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
    getSectionList(){
      var self = this;
      self.isBusy = true;
      axios.get('/principal/get-section-list',{
        params:{
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
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      var self = this;
      self.totalRows = filteredItems.length
      self.currentPage = 1
    },
    validateForm: function (event) {
      this.attemptSubmit = true;
      if (this.missingYear || this.missingSection || this.missingSectionLabel){
        event.preventDefault();
      }else{
        this.saveSection();
      }
    },

    saveSection(){
      var self = this;
        if(self.process === true){
            return;
        }
        self.process = true;
        self.$swal({
          title: 'Are you sure?',
          text: "You want to Add New Section?",
          type: 'info',
          icon: "info",
          showCancelButton: true,
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.value) {
            self.$swal({
                html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Adding.....</div>",
                showConfirmButton: false,
                allowOutsideClick: false
            });
            axios.post( 'save-section', {
                year_id: this.year.id,
                section: this.section,
                section_label: this.section_label,
                academic_year_id: this.academic_year.id,
            })
            .then(function (response) {
                self.$swal(
                  'Saved!',
                  'Successfully Added New Section.',
                  'success'
                )
                self.process = false
                self.clear();
                self.getSectionList();
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
                self.process = false
            });
          }else{
              self.process = false
          }
        })
    },
    editSection(data){
      var self = this;
      self.$swal({
          title: "Edit this Section?",
          text: "Are you sure?",
          type: "warning",
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#38c172",
          confirmButtonText: "Yes"
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
            self.selectedSection = data
            self.openModal();
          }
      });

    },
    deleteSection(id){
      var self = this;
      self.$swal({
          title: "Are you sure to delete this Section?",
          text: "Are you sure? You won't be able to revert this!",
          type: "warning",
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#38c172",
          confirmButtonText: "Yes"
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
            self.$swal({
                html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Deleting.....</div>",
                showConfirmButton: false,
                allowOutsideClick: false
            });
             axios.post('delete-section', {
                id: id,
              })
              .then(function (response) {
                self.$swal({
                  position: 'center',
                  type: 'success',
                  icon: 'success',
                  title: 'Successfully Deleted',
                  showConfirmButton: true,
                })
                self.getSectionList();
              })
              .catch(function (error) {
                  self.$swal({
                    position: 'center',
                    type: 'error',
                    icon: 'error',
                    title: 'Error Occured',
                    showConfirmButton: true,
                  })
              });
          }
      });
    },
    openModal() {
      var self = this;
      self.showModalData = true;
    },
    close(){
      var self = this;
      self.showModalData = false;
      self.getSectionList();
    },
    clear(){
      this.attemptSubmit = false;
      this.year = '';
      this.section = '';
      this.section_label = '';
    },
  }
};
</script>
