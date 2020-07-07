<template>
    <div v-if="showModalData">
    <transition>
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">{{ selectedData.criteria.label }}</h5>
                <button type="button" @click="close(false)" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" >&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-sm-12">
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

<script>export default {
  props: {
    showModalData: Boolean,
    selectedData: Object,
    isStudent: Boolean,
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
      sortDesc: false,
      fields:[
        {
          key: 'sheet_data.score_sheet_name',
          label: 'Title',
          sortable: true,
        },
        {
          key: 'score',
          label: 'Score',
          sortable: true,
        },
        {
          key: 'created_at',
          label: 'Date Recorded',
          sortable: true,
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

    getCriteriaScores(){
      var self = this;
      self.isBusy = true;
      let url = self.isStudent ? '/student/get-score-sheet-data' : '/teacher/get-score-sheet-data';
      axios.get(url,{
        params:{
          user_id: self.selectedData.user_id,
          quarter_id: self.selectedData.quarter_id,
          subject_id: self.selectedData.subject_id,
          section_id: self.selectedData.section_id,
          criteria_id: self.selectedData.criteria_id, 
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
    this.getCriteriaScores();
  },
  computed:{
  }

};
</script>
