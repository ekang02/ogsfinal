<template>
  <div class="">
    <div class="d-flex justify-content-between align-items-center">
      <h3 class="m-3 text-header">
          Academic Year
      </h3>
      <div>
        <button class="btn btn-success" @click="openModal()">Add New Academic Year</button>
      </div>        
    </div>
    <hr/>
    <div class="mx-3">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold">Academic Year List</h6>
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
                <span class="input-group-text bg-success text-white" id="basic-addon2">Search</span>
              </div>
            </div>
          </div>
          <div class="mt-2">
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
              <template v-slot:cell(actions)="row">
                <button class="btn btn-primary" @click="edit(row.item)">
                  Edit
                </button>
                <button class="btn btn-danger" @click="del(row.item.id)">
                  Delete
                </button>
                <button class="btn btn-secondary"  :class="{ 'btn-success': row.item.isActive === '1'}" @click="current(row.item.id)">
                  {{ row.item.isActive === '1' ? 'Current' : 'Set as Current' }}
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
    <modalAddAcademicYear v-if="showModalData" :showModalData.sync="showModalData" @close="close"></modalAddAcademicYear>
    <modalEditAcademicYear v-if="showModalDataEdit" :showModalDataEdit.sync="showModalDataEdit" :selectedData.sync="selectedData" @closeEdit="closeEdit"></modalEditAcademicYear>
  </div>
</template>

<script>
import modalAddAcademicYear from '../../Modal/modalAddAcademicYear.vue';
import modalEditAcademicYear from '../../Modal/modalEditAcademicYear.vue';
export default {
  props:['user'],
  components: {
    modalAddAcademicYear,
    modalEditAcademicYear,
  },
  data() {
    return {
      showModalData: false,
      showModalDataEdit: false,
      selectedData: null,
      lists: [],
      isBusy: false,
      sortBy: 'id',
      sortDesc: false,
      fields:[
          {
            key: 'id',
            label: '#',
            variant: 'success',
          },
          {
            key: 'from_year',
            label: 'From',
            sortable: true,
          },
          {
            key: 'to_year',
            label: 'To',
            sortable: true,
          },
          {
            key: 'actions',
            label: 'Actions',
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
    this.getList();
  },
  computed: {

  },
  methods: {
    getList(){
      var self = this;
      self.isBusy = true;
      axios.get('/admin/get-academic-year-list')
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
    del(id){
      var self = this;
      self.$swal({
          title: "Are you sure to delete this Academic Year?",
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
             axios.post('delete-academic-year', {
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
                self.getList();
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
    current(id){
      var self = this;
      self.$swal({
          title: "Are you sure to set this as Current Academic Year?",
          text: "Are you sure? You won't be able to revert this!",
          type: "info",
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#38c172",
          confirmButtonText: "Yes"
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
            self.$swal({
                html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Updating.....</div>",
                showConfirmButton: false,
                allowOutsideClick: false
            });
             axios.post('update-academic-year-active', {
                id: id,
              })
              .then(function (response) {
                self.$swal({
                  position: 'center',
                  type: 'success',
                  icon: 'success',
                  title: 'Successfully Updated',
                  showConfirmButton: true,
                })
                self.getList();
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
    edit(data){
      this.selectedData = data;
      this.openModalEdit();
    },
    openModal() {
      var self = this;
      self.showModalData = true;
    },
    close(isCancel){
      var self = this;
      self.showModalData = false;
      if(isCancel){
        self.getList();
      }
    },
    openModalEdit() {
      this.showModalDataEdit = true;
    },
    closeEdit(isCancel){
      var self = this;
      self.showModalDataEdit = false;
      if(isCancel){
        self.getList();
      }
    }
  }
};
</script>
