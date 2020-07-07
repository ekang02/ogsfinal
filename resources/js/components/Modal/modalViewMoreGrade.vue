<template>
    <div v-if="showModalData">
    <transition>
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">View {{ fullname }} - {{ selectedData.quarter.label }} ({{ selectedSubject.label}}) </h5>
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
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="close(false)">Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: {
    showModalData: Boolean,
    selectedData: Object,
    selectedSubject: Object,
  },
  components: {

  },
  data(){
    return {
      isLoading: false,
      user_data: null,
      lists: [],
      isBusy: false,
      sortBy: 'id',
      sortDesc: true,
      fields:[
        {
          key: 'criteria.label',
          label: 'Criteria',
          sortable: true,
          variant: 'success'
        },
        {
          key: 'total_score',
          label: 'Total Score',
          sortable: true
        },
        {
          key: 'criteria_total_score',
          label: 'Criteria Total Score',
          sortable: true
        },
        {
          key: 'average_score',
          label: 'Avarage Score',
          sortable: true
        },
        {
          key: 'weighted_score',
          label: 'Weighted Score',
          sortable: true
        },
      ],
      pageOptions: [5,10,50,100],
      perPage: 10,
      totalRows: 0,
      filter: null,
      currentPage: 1,
    }

  },
  methods: {
    getUserData(){
      var self = this;
      console.log('asds');
      axios.get('/get-user-data',{
        params:{
          user_id: self.selectedData.user_id,
          academic_year_id: self.selectedData.academic_year_id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.user_data = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getQuarterlyGrade(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-student-grade-quarterly',{
        params:{
          user_id: self.selectedData.user_id,
          quarter_id: self.selectedData.quarter_id,
          subject_id: self.selectedData.subject_id,
          section_id: self.selectedData.section_id,
          academic_year_id: self.selectedData.academic_year_id,
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
        self.isBusy = false;
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
      self.$emit('close',data);
    }
  },
  created (){
    this.getUserData();
    this.getQuarterlyGrade();
  },
  computed:{
    fullname(){
      return this.user_data ? this.user_data.first_name + ' ' + this.user_data.last_name : null; 
    }
  }

};
</script>
