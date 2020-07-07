<template>
  <div class="">
    <h3 class="m-3 text-header">
        Subject
    </h3>
    <hr/>
    <div class="mx-3">
      <div class="row justify-content-between">
        <div class="form-group col-sm-4">
          <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Add New Subject</h6>
            </div>
            <div class="card-body">
                <div class="row">
                  <div class="form-group col-sm-12">
                    <label>Grade & Section</label>
                    <select v-model="year_section" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingYearSection }">
                        <option v-for="ys in year_sections" :value="ys"> {{ ys.year }} - {{ ys.section }}</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                      <label>Subject</label>
                      <input type="text" v-model="subject" placeholder="Input Subject" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSubject }">
                      <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                      <label>Subject Code</label>
                      <input type="text" v-model="subject_code" placeholder="Input Subject Code" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSubjectCode }">
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
                  <button class="btn btn-primary" @click="editSubject(row.item)">
                    Edit
                  </button>
                  <button class="btn btn-danger" @click="deleteSubject(row.item.id)">
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
    <modalEditSubject v-if="showModalData" :showModalData.sync="showModalData" :selectedSubject.sync="selectedSubject" @close="close()"></modalEditSubject>
  </div>
</template>

<script>
import modalEditSubject from '../../Modal/modalEditSubject.vue';
export default {
  props:['user'],
  components: {
    modalEditSubject,
  },
  data() {
    return {
      showModalData: false,
      process: false,
      selectedSubject: '',
      year_section: '',
      subject: '',
      subject_code: '',
      year_sections: [],
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
            key: 'year_section.year',
            label: 'Grade',
            sortable: true
          },
          {
            key: 'year_section.section',
            label: 'Section',
            sortable: true
          },
          {
            key: 'label',
            label: 'Subject',
            sortable: true
          },
          {
            key: 'subject_code',
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
    this.getYearSection();
    this.getSubjectList();
  },
  computed: {
    missingSubject: function () { 
      return this.subject == ''; 
    },
    missingSubjectCode: function () { 
      return this.subject_code == ''; 
    },
    missingYearSection: function () { 
      return this.year_section == ''; 
    },

  },
  methods: {

    getYearSection(){
      var self = this;
      axios.get('/get-year-section')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.year_sections = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSubjectList(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-subject')
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
      if (this.missingYearSection || this.missingSubject || this.missingSubjectCode){
        event.preventDefault();
      }else{
        this.saveSubject();
      }
    },

    saveSubject(){
      var self = this;
        if(self.process === true){
            return;
        }
        self.process = true;
        self.$swal({
          title: 'Are you sure?',
          text: "You want to Add New Subject?",
          type: 'info',
          icon: "info",
          showCancelButton: true,
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.value) {
            self.$swal({
                html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Updating.....</div>",
                showConfirmButton: false,
                allowOutsideClick: false
            });
            axios.post( 'save-subject', {
                year_section_id: this.year_section.id,
                subject: this.subject,
                subject_code: this.subject_code,
            })
            .then(function (response) {
                self.$swal(
                  'Saved!',
                  'Successfully Added New Subject.',
                  'success'
                )
                self.process = false
                self.clear();
                self.getSubjectList();
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
    editSubject(data){
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
            self.selectedSubject = data
            self.openModal();
          }
      });

    },
    deleteSubject(id){
      var self = this;
      self.$swal({
          title: "Are you sure to delete this Subject?",
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
             axios.post('delete-subject', {
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
                self.getSubjectList();
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
      self.getSubjectList();
    },
    clear(){
      this.attemptSubmit = false;
      this.year_section = '';
      this.subject_id = '';
      this.subject = '';
      this.subject_code = '';
    },
  }
};
</script>
