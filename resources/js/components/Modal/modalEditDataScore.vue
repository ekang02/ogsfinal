<template>
    <div v-if="showModalData">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Scoresheet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" @click="$emit('close')">&times;</span>
                </button>
              </div>
              <div class="bg-danger text-white" v-if="errors.noscore || errors.greaterscore">
                <div class="col-sm-12 h4 my-0 py-2 text-center">
                  <div v-if="errors.noscore">
                      Please Set Score First!
                  </div>
                  <div v-if="errors.greaterscore">
                      Inputted Score is greater than set score!
                  </div>
                </div>
              </div>
              <div class="modal-body">
                <div class="col-sm-12">
                  <div class="row justify-content-between">
                    <div class="row col-sm-4 pr-0">
                      <div class="card shadow">
                        <div class="card-header">
                          <h5>Scoresheet Details</h5>
                        </div>
                        <div class="card-body">
                          <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                              <label>Scoresheet Title:</label>
                              <label>{{ selected.score_sheet_name }}</label>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                              <label>Quarter:</label>
                              <label>{{ selected.quarter.label }}</label>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                              <label>Category:</label>
                              <label>{{ selected.criteria.label }}</label>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                              <label>Subject:</label>
                                <label>{{ selected.subject.label }}</label>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                              <label>Year & Section:</label>
                              <label>{{ selected.year.year }} - {{ selected.section.section }} ({{ selected.section.label }})</label>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                              <label class="col-sm-4 px-0">Score:</label>
                              <input v-model="form.total_score" type="text" class="form-control col-sm-6" :class="{ 'is-invalid': attemptSubmit && missingScore }" @input="scoreDataChange($event)">
                            </div>
                            <div class="invalid-feedback">This field is required.</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8 pl-0">
                      <div class="card shadow">
                        <div class="card-header">
                          <h5>Scoresheet List</h5>
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
                            <template v-slot:cell(score)="row">
                              <b-form-input v-model="row.item.score" @input="scoreChange(row.item.score,row.item.id,row.item.user_id)"/>
                            </template>
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
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancel</button>
                <button type="button" class="btn btn-primary" @click="save()">
                  Submit<span v-show="isLoading">ting... <i class="fa fa-refresh fa-spin"></i></span>
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
export default {
  name: 'modalEditDataScore',
  props: {
    showModalData: Boolean,
    selected: Object,
  },
  components: {
  },
  data(){
    return {
      attemptSubmit: false,
      isLoading: false,
      form:{
          total_score: this.selected.total_score,
          studentScore: [],
      },
      errors:{
        noscore: false,
        greaterscore: false,
      },
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
    }
  },
  methods: {
    getScoreSheetData(){
      var self = this;
      self.isBusy = true;
      axios.get('/teacher/get-score-sheet-data', {
        params: {
          score_sheet_id: self.selected.id,
          academic_year_id: self.selected.academic_year_id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.lists = result;
        self.totalRows = result.length;
        self.isBusy = false;
        self.loaded = true;

        // console.log(result);
        let arr = self.form.studentScore;
        _.each(result, function (value, key) {
          // console.log(value, key);
            arr.push({ id:value.id, user_id: value.user_id, score: value.score});
        });

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
    scoreDataChange: function(event){
      console.log(event);
      if(parseInt(event.data) > 0 || event.data != null){
        this.errors.noscore = false;
        this.errors.greaterscore = false;
      }else{
        this.errors.noscore = true;
      }
    },
    scoreChange: function (score,id,user_id){
      if (this.missingScore){
        event.preventDefault();
        this.errors.noscore = true;
        this.errors.greaterscore = false;
      }else if(parseInt(score) > parseInt(this.form.total_score)){
        event.preventDefault();
        this.errors.greaterscore = true;
        this.errors.noscore = false;
      }else{
        this.errors.noscore = false;
        this.errors.greaterscore = false;
        let arr = this.form.studentScore;
        const { length } = arr;
        const id = length + 1;
        const found = arr.some(el => el.user_id === user_id);
        if (!found){
          arr.push({ id:id, user_id: user_id, score: score});
        }else{
          let obj = arr.findIndex((obj => obj.user_id == user_id));
          arr[obj].score = score;
        }
      }
    },
    save(){
      var self = this;
      if(self.process === true){
          return;
      }
      self.isLoading = true;
      self.process = true;

      axios.post( '/teacher/update-score-sheet', {
          score_sheet_id: this.selected.id,
          total_score: this.form.total_score,
          student_score: this.form.studentScore,
      })
      .then(function (response) {
        self.process = false;
        self.isLoading = false;
        self.$emit('close');
        self.$swal({
          position: 'center',
          type: 'success',
          icon: 'success',
          title: 'Successfully Updated',
          showConfirmButton: true,
        });
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
  },
  created (){
    this.getScoreSheetData();
  },
  computed:{
    missingScore: function () { 
      return this.form.total_score == ''; 
    },
  }

};
</script>
