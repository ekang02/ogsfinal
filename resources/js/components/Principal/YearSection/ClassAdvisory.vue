<template>
  <div class="">
    <h3 class="m-3 text-header">
        Advisory Class
    </h3>
    <hr/>
    <div class="mx-3">
      <div class="form-group col-sm-12">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold">Teacher Advisory Class List</h6>
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
                <b-button v-if="row.item.advisory.length > 0" size="sm" @click="row.toggleDetails" class="mr-2">
                  {{ row.detailsShowing ? 'Hide' : 'Show'}} Details
                </b-button>
              </template>
               <template v-slot:row-details="row">
                <b-card class="p-0">
                  <b-table :fields="nested_fields" :items="row.item.advisory">
                    <template v-slot:cell(remove)="row">
                      <b-button class="btn btn-danger" @click="removeAdvisory(row.item)">
                        Remove
                      </b-button>
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
            key: 'info.full_name',
            label: 'Teacher Name',
            sortable: true
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
          },
          {
            key: 'section.students.length',
            label: 'Total Students',
            sortable: true,
          },
          {
            key: 'remove',
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
  created(){
    this.getClassAdvisoryList();
  },
  computed: {


  },
  methods: {

    getClassAdvisoryList(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-class-advisory-list')
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
    removeAdvisory(data){
      var self = this;
      self.$swal({
          title: "Are you sure to remove this?",
          text: "Are you sure? You won't be able to revert this!",
          type: "warning",
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#38c172",
          confirmButtonText: "Yes"
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
            self.$swal({
                html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Removing.....</div>",
                showConfirmButton: false,
                allowOutsideClick: false
            });
             axios.post('remove-advisory', {
                id: data.id,
                section_id: data.section_id,
              })
              .then(function (response) {
                self.$swal({
                  position: 'center',
                  type: 'success',
                  icon: 'success',
                  title: 'Successfully Removed',
                  showConfirmButton: true,
                })
                self.getClassAdvisoryList();
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
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      var self = this;
      self.totalRows = filteredItems.length
      self.currentPage = 1
    },
  }
};
</script>
