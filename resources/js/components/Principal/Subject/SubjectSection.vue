<template>
  <div class="">
    <h3 class="m-3 text-header">
        Section's Subject
    </h3>
    <hr/>
    <div class="mx-3">
      <div class="row justify-content-between">
        <div class="form-group col-sm-4">
          <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Assign New Subject to Section</h6>
            </div>
            <div class="card-body">
                <div class="row">
                  <div class="form-group col-sm-12">
                    <label>School year</label>
                    <input type="text":value="academic_year_value" placeholder="School Year" class="form-control" readonly="true">
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Grade</label>
                    <select v-model="year" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingYear }" @change="getSectionByYear()">
                        <option v-for="year in years" :value="year"> {{ year.year }}</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Section</label>
                    <select v-model="section" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSection }" :disabled="isSectionDisabled">
                        <option v-for="section in sections" :value="section"> {{ section.section }} ({{ section.label }})</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Subject</label>
                    <multiselect 
                      v-model="subject" 
                      placeholder="Search Subject" 
                      label="label" 
                      track-by="id" 
                      :options="subjects" 
                      :multiple="true" 
                      :close-on-select="false"
                      :searchable="true"
                      :class="{ 'is-invalid': attemptSubmit && missingSubject }"
                      :disabled="isSubjectDisabled">
                        
                      </multiselect>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                </div>
                <div class="text-right">
                  <button class="btn btn-outline-secondary" @click="clear()">Clear</button>
                  <button class="btn btn-primary" @click="validateForm"> {{ isEdit ? 'Update' : 'Save' }}</button>
                </div>
            </div>
          </div>
        </div>
        <div class="form-group col-sm-8">
          <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Subject List</h6>
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
                  <!-- <button class="btn btn-primary" @click="editSubjectSection(row.item)">
                    Edit
                  </button> -->
                  <button class="btn btn-danger" @click="deleteSubjectSection(row.item.id)">
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
  </div>
</template>

<script>
export default {
  props:['user'],
  components: {
  },
  data() {
    return {
      isEdit: false,
      process: false,
      attemptSubmit: false,
      loaded: false,
      academic_year: '',
      editId: '',
      years: [],
      sections: [],
      subjects: [],
      teachers: [],
      teacher: '',
      subject: [],
      year: '',
      section: '',
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
            key: 'section.label',
            label: 'Section',
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
          {
            key: 'status',
            label: 'Status',
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
    this.getSubject();
  },
  computed: {
    academic_year_value(){
      return this.academic_year ? this.academic_year.from_year + ' - ' + this.academic_year.to_year : '';
    },
    missingSubject: function () { 
      return this.subject.length <= 0; 
    },
    missingYear: function () { 
      return this.year == ''; 
    },
    missingSection: function () { 
      return this.section == ''; 
    },
    isSubjectDisabled:function(){
      return this.section == '' ? true : false;
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
        self.academic_year = result;
        self.getSubjectSectionList();
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
          year_id: this.year.id,
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
    // getSubjectByYear(){
    //   var self = this;
    //   self.subjects = [];
    //   axios.get('/get-subject-year',{
    //     params:{
    //       year_id: self.year.id,
    //     }
    //   })
    //   .then(function (response) {
    //     // handle success
    //     var result = response.data;
    //     self.subjects = result;
    //   })
    //   .catch(function (error) {
    //     console.log(error);
    //   });
    // },
    getSubject(){
      var self = this;
      axios.get('/get-subject-list')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.subjects = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSubjectSectionList(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-subject-section',{
        params:{
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
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      var self = this;
      self.totalRows = filteredItems.length
      self.currentPage = 1
    },
    validateForm: function (event) {
      this.attemptSubmit = true;
      if (this.missingYear || this.missingSubject || this.missingSection){
        event.preventDefault();
      }else{
        this.saveSubjectSection();
      }
    },

    saveSubjectSection(){
      var self = this;
        if(self.process === true){
            return;
        }
        self.process = true;
        self.$swal({
          title: 'Are you sure?',
          text: "You want to assign those subject to section " + self.section.label + "?",
          type: 'info',
          icon: "info",
          showCancelButton: true,
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.value) {
            let message = self.isEdit ? "Updating....." : "Adding.....";
            self.$swal({
                html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>" + message +"</div>",
                showConfirmButton: false,
                allowOutsideClick: false
            });
            let url = self.isEdit ? 'update-subject-section' : 'save-subject-section';
            axios.post(url, {
                id: self.editId,
                year_id: self.year.id,
                subject: self.subject,
                section_id: self.section.id,
                academic_year_id:self.academic_year.id,
            })
            .then(function (response) {
                self.$swal(
                  'Saved!',
                  'Successfully Assign Subject to Section.',
                  'success'
                )
                self.process = false
                self.clear();
                self.getSubjectSectionList();
            })
            .catch(function (error) {
                self.$swal({
                  position: 'center',
                  type: 'error',
                  icon: 'error',
                  title: error.response.data.message !== '' ? error.response.data.message : 'Something went wrong!',
                  showConfirmButton: true,
                })
                self.process = false;
                self.subject = [];
            });
          }else{
              self.process = false
          }
        })
    },
    editSubjectSection(data){
      var self = this;
      self.$swal({
          title: "Edit this Subject?",
          text: "Are you sure?",
          type: "warning",
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#38c172",
          confirmButtonText: "Yes"
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
            this.isEdit = true;
            this.editId = data.id;
            this.year = data.year;
            this.section = data.section;
            this.subject = data.subject;
            this.getSectionByYear();
            this.getSubjectByYear();
          }
      });

    },
    deleteSubjectSection(id){
      var self = this;
      self.$swal({
          title: "Are you sure to delete Subject to this Section?",
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
             axios.post('delete-subject-section', {
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
                self.getSubjectSectionList();
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
    clear(){
      this.attemptSubmit = false;
      this.year = '';
      this.subject = [];
      this.section = '';
      this.subjects = [];
      this.sections = [];
    },
  }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
