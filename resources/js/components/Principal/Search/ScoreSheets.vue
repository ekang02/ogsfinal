<template>
    <div>
        <h3 class="m-3 text-header">
           Score Sheets List
        </h3>
        <div class="row">
          <div class="col-md-7">
            <div class="card shadow">
              <div class="card-header">
                <h5>List</h5>
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
                  <b-table 
                    ref="selectableTable"
                    responsive
                    hover
                    show-empty
                    striped
                    selectable
                    :items="lists"
                    :busy="isBusy"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage"
                    :filter="filter"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    :select-mode="single"
                    head-variant="light"
                    @row-selected="onRowSelected"
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
          <div class="col-md-5">
            <div class="card shadow">
              <div class="card-header">
                <h5>Preview Scoresheet</h5>
              </div>
              <div class="card-body">
                <div v-if="selected">
                  <div class="d-flex justify-content-between align-items-center">
                    <h5>
                      {{ selected.subject.label }} | {{ selected.year.year }} - {{ selected.section.section }} ({{ selected.section.label }})
                    </h5>
                    <h6>S.Y {{ academic_year_value }}</h6>
                  </div>
                  <div>
                    <PreviewList :score_sheet_id.sync="selected.id" :academic_year.sync="academic_year"></PreviewList>
                  </div>
                </div>
                <div v-else>
                   No Score Sheet Selected
                </div>
              </div>
            </div>
          </div>
        </div>
        <modalEditDataScore v-if="showModalData" :showModalData.sync="showModalData" :selected.sync="selected" :user_id.sync="user.id" @close="close()"></modalEditDataScore>
      </div>
</template>

<script>
import PreviewList from './PreviewList.vue';
export default {
  props:['user'],
  components: {
    PreviewList,

  },
  data() {
    return {
      showModalData: false,
      lists: [],
      selected: '',
      academic_year: '',
      isBusy: false,
      single: 'single',
      sortBy: 'id',
      sortDesc: true,
      fields:[
          {
            key: 'year.year',
            label: 'Grade',
            sortable: true,
          },
          {
            key: 'section.full',
            label: 'Section',
            sortable: true,
          },
          {
            key: 'subject.label',
            label: 'Subject',
            sortable: true,
          },
          {
            key: 'score_sheet_name',
            label: 'Score Sheet Title ',
            sortable: true,
          },
          {
            key: 'criteria.label',
            label: 'Category',
            sortable: true,
          },
          {
            key: 'quarter.label',
            label: 'Quarter',
            sortable: true,
          },
          {
            key: 'total_score',
            label: 'Total Score',
            sortable: true,
          },
          // {
          //   key: 'options',
          //   label: 'Options',
          //   'class': 'text-center' 
          // },
      ],
      pageOptions: [5,10,50,100],
      perPage: 10,
      totalRows: 0,
      filter: null,
      currentPage: 1,
    };
  },
  computed: {
    academic_year_value(){
      return this.academic_year ? this.academic_year.from_year + ' - ' + this.academic_year.to_year : '';
    },
  },
  created(){
    this.getAcademicYear();
  },
  methods: {

    getAcademicYear(){
      var self = this;
      axios.get('/get-academic-year')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.academic_year = result;
        self.getScoreSheetList();
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getScoreSheetList(){
      var self = this;
      self.isBusy = true;
      axios.get('/principal/get-score-sheet-list',{
        params:{
          academic_year_id: self.academic_year.id,
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
    onRowSelected(items) {
      this.selected = items[0];
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
