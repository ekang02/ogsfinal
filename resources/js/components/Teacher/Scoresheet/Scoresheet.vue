<template>
  <div>
    <h3 class="m-3 text-header">
       Record on Scoresheet
    </h3>
    <div class="row">
      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-header">
            <h5>Scoresheet Details</h5>
          </div>
          <div class="card-body">
            <div class="row justify-content-between">
              <div class="form-group col-sm-12">
                <div class="d-flex justify-content-between align-items-center">
                  <label>Quarter:</label>
                  <label>{{ form.quarter.label }}</label>
                </div>
              </div>
              <div class="form-group col-sm-12">
                <div class="d-flex justify-content-between align-items-center">
                  <label>Category:</label>
                  <label>{{ form.category.label }}</label>
                </div>
              </div>
              <div class="form-group col-sm-12">
                <div class="d-flex justify-content-between align-items-center">
                  <label>Scoresheet Title:</label>
                  <label>{{ form.title }}</label>
                </div>
              </div>
              <div class="form-group col-sm-12">
                <div class="d-flex justify-content-between align-items-center">
                  <label>Subject:</label>
                  <label>{{ form.subject.label }}</label>
                </div>
              </div>
              <div class="form-group col-sm-12">
                <div class="d-flex justify-content-between align-items-center">
                  <label>Grade & Section:</label>
                  <label>{{ form.year.year }} - {{ form.section.section }} ({{ form.section.label }})</label>
                </div>
              </div>
              <div class="form-group col-sm-12">
                <div class="d-flex justify-content-between align-items-center">
                  <label class="col-sm-4 px-0">Score:</label>
                  <input v-model="form.score" type="text" @keypress="isNumber($event)" class="form-control col-sm-6" :class="{ 'is-invalid': attemptSubmit && missingScore }">
                </div>
                <div class="invalid-feedback">This field is required.</div>
              </div>

              <div class="form-group col-sm-12"> 
                <button class="btn btn-success w-100" @click="validateForm()">Save Scoresheet</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
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
                <b-form-input v-model="row.item.score" @keypress="isNumber($event)" @input="scoreChange(row.item.score,row.item.id,row.item.user_id)"/>
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
  <modalDataScore v-if="showModalData" :showModalData.sync="showModalData" :form.sync="form" :user_id.sync="user.id" @close="close()"></modalDataScore>
  </div>
</template>

<script>
import modalDataScore from '../../Modal/modalDataScore.vue';
import ScoresheetList from './ScoresheetList.vue';
export default {
  props:['user'],
  components: {
    ScoresheetList ,
    modalDataScore,

  },
  data() {
    return {
      attemptSubmit: false,
      showModalData: false,
      form:{
        academic_year: '',
        quarter: '',
        category: '',
        title: '',
        subject: '',
        year: '',
        section: '',
        score: '',        
        studentScore: [],
        isHome: false,
      },
      lists: [],
      isBusy: false,
      sortBy: 'user.id',
      sortDesc: true,
      fields:[
        {
          key: 'user.id',
          label: 'ID',
          sortable: true,
        },
        {
          key: 'student_id',
          label: 'Student ID',
          sortable: true,
        },
        {
          key: 'full_name',
          label: 'Student Name',
          sortable: true,
        },
        {
          key: 'score',
          label: 'Score',
          sortable: false,
        },
        {
          key: 'action',
          label: '',
          sortable: false,
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
    missingQuarter: function () { 
      return this.form.quarter == ''; 
    },
    missingCategory: function () { 
      return this.form.category == ''; 
    },
    missingScoreSheetTitle: function () { 
      return this.form.title == ''; 
    },
    missingSubject: function () { 
      return this.form.subject == ''; 
    },
    missingYear: function () { 
      return this.form.year == ''; 
    },
    missingSection: function () { 
      return this.form.section == ''; 
    },
    missingScore: function () { 
      return this.form.score == ''; 
    },
    missingStudentScore: function () { 
      return this.form.studentScore.length; 
    },
    
  },
  created (){
  },
  methods: {
    validateForm: function (event) {
      this.attemptSubmit = true;
      if (this.missingScore || this.missingSubject || this.missingSubjectCode || this.missingCategory || this.missingYear || this.missingSection || this.missingScoreSheetTitle || this.missingQuarter){
        event.preventDefault();
      }else if(this.missingStudentScore == 0 || this.form.studentScore.length != this.lists.length){
        this.$swal({
            position: 'center',
            icon: 'info',
            title: 'Please Set Score to Students!',
            showConfirmButton: true,
        });
      }else{
        this.openModal();
      }
      
    },
    scoreChange: function (score,id,user_id){
      console.log(event);
      if (this.missingScore){
        this.$swal({
            position: 'center',
            icon: 'info',
            title: 'Please Set Score First!',
            showConfirmButton: true,
        });
      }else if(parseInt(score) > parseInt(this.form.score)){
        this.$swal({
            position: 'center',
            icon: 'info',
            title: 'Inputted Score is greater than set score!',
            showConfirmButton: true,
        });
      }else{
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
    isNumber: function(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();;
      } else {
        return true;
      }
    },
    getStudentList(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-student-list', {
        params: {
          year_id: self.form.year,
          section_id: self.form.section,
          academic_year_id: self.form.academic_year,
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
    getSubject(){
      var self = this;
      axios.get('/get-subject', {
        params: {
          subject_id: self.form.subject,
          academic_year_id: self.form.academic_year,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.form.subject = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getYear(){
      var self = this;
      axios.get('/get-year', {
        params: {
          year_id: self.form.year,
          academic_year_id: self.form.academic_year,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.form.year = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSection(){
      var self = this;
      axios.get('/get-section', {
        params: {
          section_id: self.form.section,
          academic_year_id: self.form.academic_year,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.form.section = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getCriteria(){
      var self = this;
      axios.get('/get-criteria-list',{
        params:{
          criteria_id: this.form.category,
          academic_year_id: self.form.academic_year,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.form.category = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },


    getQuarter(){
      var self = this;
      axios.get('/get-quarter',{
        params:{
          quarter_id: this.form.quarter
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.form.quarter = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      var self = this;
      self.totalRows = filteredItems.length
      self.currentPage = 1
    },
    openModal() {
      var self = this;
      self.showModalData = true;
    },
    close(){
      var self = this;
      self.showModalData = false;
    }
  },

  watch:{
    value(newVal){
      this.lists[0].value = this.value;
      this.$set(this.items,0,this.items[0])

    }
  },
  mounted(){
    let uri = window.location.search.substring(1); 
    let params = new URLSearchParams(uri);
    this.form.academic_year = params.get("academic_year_id");
    this.form.quarter = params.get("quarter");
    this.form.category = params.get("category");
    this.form.title = params.get("title");
    this.form.subject = params.get("subject");
    this.form.year = params.get("year");
    this.form.section = params.get("section");
    if((this.form.quarter == null || this.form.quarter == '') && (this.form.category == null || this.form.category == '') && (this.form.title == null || this.form.title == '')){
      this.$swal({
        position: 'center',
        icon: 'info',
        title: 'Create Scoresheet First!',
        showConfirmButton: true,
      }).then(()=>{
        let newUrl = "add-score-sheet";
        window.location.href = newUrl;
      });
    }else{
      this.getQuarter();
      this.getCriteria();
      this.getSubject();
      this.getYear();
      this.getSection();
      this.getStudentList();
    }
  }

};
</script>
