<template>
  <div class="">
    <h3 class="m-3 text-header">
        Grade
    </h3>
    <hr/>
    <div class="mx-3">
      <div class="row justify-content-between">
        <div class="form-group col-sm-4">
          <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Add New Grade</h6>
            </div>
            <div class="card-body">
                <div class="row">
                  <div class="form-group col-sm-12">
                    <label>Grade</label>
                    <select v-model="year" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingYear }">
                        <option v-for="year in years" :value="year"> {{ year }}</option>
                    </select>
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
                <h6 class="m-0 font-weight-bold">Grade/Year List</h6>
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
                  <button class="btn btn-danger" @click="deleteYear(row.item.id)">
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
      process: false,
      selectedSection: '',
      year: '',
      years: ['1','2','3','4','5','6','7','8','9','10'],
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
            key: 'year',
            label: 'Grade',
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
    this.getYearList();
  },
  computed: {
    missingYear: function () { 
      return this.year == ''; 
    },

  },
  methods: {

    getYearList(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-year')
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
      if (this.missingYear){
        event.preventDefault();
      }else{
        this.saveYear();
      }
    },

    saveYear(){
      var self = this;
        if(self.process === true){
            return;
        }
        self.process = true;
        self.$swal({
          title: 'Are you sure?',
          text: "You want to Add New Grade/Year?",
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
            axios.post( 'save-year', {
                year: this.year,
            })
            .then(function (response) {
                self.$swal(
                  'Saved!',
                  'Successfully Added New Grade/Year.',
                  'success'
                )
                self.process = false
                self.clear();
                self.getYearList();
            })
            .catch(function (error) {
                console.log(error);
                self.$swal({
                  position: 'center',
                  type: 'error',
                  icon: 'error',
                  title: error.response.data.message !== '' ? error.response.data.message : 'Something went wrong!',
                  showConfirmButton: true,
                })
                self.process = false
            });
          }else{
              self.process = false
          }
        })
    },
    deleteYear(id){
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
             axios.post('delete-year', {
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
                self.getYearList();
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
    },
  }
};
</script>
