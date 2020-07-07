<template>
  <div>
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
</template>

<script>
export default {
  props: {
    score_sheet_id: Number,
    academic_year_id: Object,
  },
  components: {
  },
  created(){
    this.getScoreSheetData();
  },
  data() {
    return {
      lists: [],
      isBusy: false,
      sortBy: 'id',
      sortDesc: true,
      fields:[
          {
            key: 'student_info.student_id',
            label: 'Student Number',
            sortable: true,
          },
          {
            key: 'student_info.full_name',
            label: 'Student Name',
            sortable: true,
          },
          {
            key: 'score',
            label: 'Score',
            sortable: true,
          },
      ],
      pageOptions: [5,10,50,100],
      perPage: 10,
      totalRows: 0,
      filter: null,
      currentPage: 1,
    };
  },
  computed: {
    
  },
  watch:{
    score_sheet_id: function (newValue) {
      this.getScoreSheetData();
    }
  },
  methods: {
    getScoreSheetData(){
      var self = this;
      self.isBusy = true;
      axios.get('/teacher/get-score-sheet-data', {
        params: {
          score_sheet_id: self.score_sheet_id,
          academic_year_id: self.academic_year_id.id,
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
  }

};
</script>
