<template>
    <div v-if="showModalDataAdvisory">
    <transition>
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Set Advisory Class to {{ selectedData.info.full_name }}</h5>
                <button type="button" @click="close(false)" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" >&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-sm-12">
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
                      <div slot="table-busy" class="text-center text-success my-2">
                        <b-spinner class="align-middle"></b-spinner>
                        <strong>Loading...</strong>
                      </div>
                      <template v-slot:cell(actions)="row">
                        <button class="btn btn-success" @click="addAvisory(row)">
                          {{ isLoading && indexClicked  === row.index ? 'Setting up...': 'Set as Advisory' }}
                          <span v-show="isLoading  && indexClicked  === row.index"><i class="fa fa-refresh fa-spin"></i></span>
                        </button>

                      </template>
                    </b-table>
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                          Showing Page {{ currentPage }} of {{ totalRows }} entries
                      </div>
                      <pagination :totalRows="totalRows" :currentPage.sync="currentPage" :perPage.sync="perPage"></pagination>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="close(false)">Cancel</button>
                <button type="button" class="btn btn-primary">
                  Done
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import pagination from '../Pagination/Pagination.vue';
export default {
  props: {
    showModalDataAdvisory: Boolean,
    selectedData: Object,
  },
  components: {
    pagination,

  },
  data(){
    return {
      indexClicked: '',
      academic_year: '',
      isLoading: false,
      process: false,
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
          key: 'section',
          label: 'Section',
          sortable: true
        },
        {
          key: 'label',
          label: 'Section',
          sortable: true
        },
        {
          key: 'academic_year.academic',
          label: 'School Year',
          sortable: true
        },
        {
          key: 'actions',
          label: 'Actions',
          'class': 'text-center' 
        },
      ],
      pageOptions: [5,10,50,100],
      perPage: 5,
      totalRows: 0,
      filter: null,
      currentPage: 1,
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
        self.getSectionList();
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
          hasAdviser: false,
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
    addAvisory(data){
     let item = data.item;
      var self = this;
      if(self.process === true){
          return;
      }
      self.indexClicked = data.index;
      self.isLoading = true;
      self.process = true;
      axios.post('save-advisory-class', {
        teacher_id: self.selectedData.id,
        section_id: item.id,
        academic_year_id: self.academic_year.id
      })
      .then(function (response) {
        self.process = false
        self.isLoading = false;
        self.getSectionList();
      })
      .catch(function (error) {
        console.log(error);
        self.process = false
      });
    },
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      var self = this;
      self.totalRows = filteredItems.length
      self.currentPage = 1
    },
    close(data){
      var self = this;
      self.$emit('closeAdvisory',data);
    }
  },
  created (){
    this.getAcademicYear();

  },
  computed:{

  }

};
</script>
