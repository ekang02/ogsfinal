<template>
  <div class="">
    <div class="d-flex justify-content-between align-items-center">
      <h3 class="m-3 text-header">
          Teacher Management
      </h3>
      <div>
        <button class="btn btn-success" @click="openModal()">Add New Teacher</button>
      </div>        
    </div>
    <hr/>
    <div class="mx-3">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold">Teacher List</h6>
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
                <button class="btn btn-success" @click="setAdvisory(row.item)">
                  Set Advisory
                </button>
                <button class="btn btn-primary" @click="edit(row.item)">
                  Edit
                </button>
                <button class="btn btn-danger" @click="del(row.item.id)">
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
    <modalAddTeacher v-if="showModalData" :showModalData.sync="showModalData" @close="close"></modalAddTeacher>
    <modalEditTeacher v-if="showModalDataEdit" :showModalDataEdit.sync="showModalDataEdit" :selectedData.sync="selectedData" @closeEdit="closeEdit"></modalEditTeacher>
    <modalAdvisory v-if="showModalDataAdvisory" :showModalDataAdvisory.sync="showModalDataAdvisory" :selectedData.sync="selectedData" @closeAdvisory="closeAdvisory"></modalAdvisory>
  </div>
</template>

<script>
import modalAddTeacher from '../../Modal/modalAddTeacher.vue';
import modalEditTeacher from '../../Modal/modalEditTeacher.vue';
import modalAdvisory from '../../Modal/modalAdvisory.vue';
export default {
  props:['user'],
  components: {
    modalAddTeacher,
    modalEditTeacher,
    modalAdvisory,
  },
  data() {
    return {
      showModalData: false,
      showModalDataEdit: false,
      showModalDataAdvisory: false,
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
            key: 'info.faculty_number',
            label: 'Faculty Number',
            sortable: true,
          },
          {
            key: 'info.full_name',
            label: 'Teacher Name',
            sortable: true,
          },
          {
            key: 'info.age',
            label: 'Age',
            sortable: true,
          },
          {
            key: 'info.date_of_birth',
            label: 'Birth Date',
            sortable: true,
          },
          {
            key: 'email',
            label: 'Email Address',
            sortable: true,
          },
          {
            key: 'info.phone_number',
            label: 'Phone Number',
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
    this.getTeacherList();
  },
  computed: {

  },
  methods: {
    getTeacherList(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-teacher-list')
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
          title: "Are you sure to delete this Teacher?",
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
             axios.post('delete-user', {
                user_id: id,
              })
              .then(function (response) {
                self.$swal({
                  position: 'center',
                  type: 'success',
                  icon: 'success',
                  title: 'Successfully Deleted',
                  showConfirmButton: true,
                })
                self.getTeacherList();
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
        self.getTeacherList();
      }
    },
    openModalEdit() {
      this.showModalDataEdit = true;
    },
    closeEdit(isCancel){
      var self = this;
      self.showModalDataEdit = false;
      if(isCancel){
        self.getTeacherList();
      }
    },
    setAdvisory(data){
      this.selectedData = data;
      this.openModalAdvisory();
    },
    openModalAdvisory() {
      this.showModalDataAdvisory = true;
    },
    closeAdvisory(){
      var self = this;
      self.showModalDataAdvisory = false;
    }
  }
};
</script>
