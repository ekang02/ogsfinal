<template>
    <div>
        <h3 class="m-3 text-header">
           Scoresheets
        </h3>
        <div class="row">
          <div class="col-sm-4">
            <div class="card shadow">
              <div class="card-header">
                <h5>Input Details</h5>
              </div>
              <div class="card-body">
                <div class="form-group col-sm-12">
                  <label>School Year</label>
                  <input type="text":value="academic_year_value" placeholder="School Year" class="form-control" readonly="true">
                </div>
                <div class="form-group col-sm-12">
                  <label>Quarter:</label>
                  <select v-model="form.quarter" class="form-control">
                      <option v-for="qty in quarters" :value="qty"> {{ qty.label }}</option>
                  </select>
                </div>
                <div class="form-group col-sm-12">
                  <label>Grade</label>
                  <select v-model="form.year" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingYear }" @change="getSectionByYear()">
                      <option v-for="yr in years" :value="yr.year"> {{ yr.year.year }}</option>
                  </select>
                  <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-12">
                  <label>Section</label>
                  <select v-model="form.section" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSection }" @change="getSubjectByTeacherSubject()" :disabled="isSectionDisabled">
                      <option v-for="section in sections" :value="section"> {{ section.section }} ({{ section.label }})</option>
                  </select>
                  <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-12">
                  <label>Subject</label>
                  <select v-model="form.subject" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSubject }" @change="getCriteriaBySubject()" :disabled="isSubjectDisabled">
                      <option v-for="sub in subjects" :value="sub.subject"> {{ sub.subject['label'] }}</option>
                  </select>
                  <div class="invalid-feedback">This field is required.</div>
                </div>
                <div class="form-group col-sm-12">
                  <label>Category</label>
                  <select v-model="form.category" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingCategory }">
                      <option v-for="cat in categories" :value="cat.criteria"> {{ cat.criteria.label }}</option>
                  </select>
                  <div class="invalid-feedback">This field is required.</div>
                </div>
                <hr/>
                <button class="btn btn-primary btn-lg w-100" @click="getScoreSheetList()" :disabled="isViewDisabled">View</button>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card shadow">
              <div class="card-header">
                <div class="d-flex justify-content-between">
                  <h5>Scoresheet List</h5>
                  
                  <button class="btn btn-primary" @click="generatePDF()" :disabled="disabledPrint"> 
                    Print Preview
                  </button>
                </div>
              </div>
              <div class="card-body">
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

                  <template v-slot:cell(options)="row">
                    <div class="d-flex">
                      <a href="#" class="text-primary" @click="viewModal(row)">View</a> 
                      <label class="text-primary"> | </label>
                      <a href="#" class="text-primary" @click="editModal(row)">Edit</a>
                    </div>
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
                    <div class="">
                      <div class="d-flex justify-content-center align-items-center">
                          <div>Show</div>
                          <b-form-select :options="pageOptions" v-model="perPage"/>
                          <div> entries</div>
                      </div>
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
import pdfMake from 'pdfMake/build/pdfMake';
import pdfFonts from 'pdfMake/build/vfs_fonts';
pdfMake.vfs = pdfFonts.pdfMake.vfs;

export default {
  props:['user'],
  components: {
  },
  data() {
    return {
      attemptSubmit: false,
      form:{
        academic_year: '',
        quarter: '',
        year: '',
        section: '',
        subject: '',
        category: '',
      },
      quarters: [],
      years: [],
      sections: [],
      subjects: [],
      categories: [],
      lists: [],
      isBusy: false,
      single: 'single',
      sortBy: 'id',
      sortDesc: false,
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
      return this.form.academic_year ? this.form.academic_year.from_year + ' - ' + this.form.academic_year.to_year : '';
    },
    isSubjectDisabled(){
      return this.subjects == '';
    },
    isSectionDisabled(){
      return this.sections == '';
    },
    isViewDisabled(){
      return this.form.category == '';
    },
    disabledPrint(){
      return this.lists.length > 0 ? false : true;
    }
  },
  created(){
    this.getAcademicYear();
    this.getQuarter();
    this.getYear();
  },
  methods: {
    getAcademicYear(){
      var self = this;
      axios.get('/get-academic-year')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.form.academic_year = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getQuarter(){
      var self = this;
      axios.get('/get-quarter')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.quarters = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getYear(){
      var self = this;
      self.sections = [];
      axios.get('/get-year-user',{
        params:{
          user_id: self.user.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.years = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    
    getSectionByYear(){
      var self = this;
      axios.get('/get-year-section',{
        params:{
          year_id: this.form.year.id,
          academic_year_id: self.form.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.sections = result;
        self.getGrades();
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSubjectByYear(){
      var self = this;
      self.subjects = [];
      axios.get('/get-subject',{
        params:{
          year_id: self.form.year.id,
          academic_year_id: self.form.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.subjects = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getSubjectByTeacherSubject(){
      var self = this;
      self.subjects = [];
      axios.get('/get-subject-section-teacher',{
        params:{
          user_id: self.user.id,
          year_id: self.form.year.id,
          section_id: self.form.section.id,
          academic_year_id: self.form.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.subjects = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },


    getCriteriaBySubject(){
      var self = this;
      axios.get('/get-criteria-list',{
        params: {
          subject_id: self.form.subject.id,
          academic_year_id: self.form.academic_year.id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.categories = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getScoreSheetList(){
      var self = this;
      self.isBusy = true;
      axios.get('/teacher/get-score-sheet-list-print', {
        params: {
          id: self.user.id,
          academic_year_id: self.form.academic_year.id,
          quarter_id: self.form.quarter.id,
          year_id: self.form.year.id,
          section_id: self.form.section.id,
          subject_id: self.form.subject.id,
          category_id: self.form.category.id,
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
    generatePDF(){
      let docDefinition = this.getDocDefinition();
      pdfMake.createPdf(docDefinition).print();
    },
    getDocDefinition(){
      return {
       pageOrientation: 'landscape',
       content: [
        {
         text: 'Score Sheet',
         bold: true,
         fontSize: 20,
         alignment: 'center',
         margin: [0, 0, 0, 20]
        },
        {
          layout: 'lightHorizontalLines',
          table: {
            headerRows: 1,
            widths: [ 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto' ],
            body: [
              [
               { text: 'Quarter', style: 'tableHeader'},
               { text: 'Grade', style: 'tableHeader'},
               { text: 'Section', style: 'tableHeader' },
               { text: 'Subject', style: 'tableHeader'},
               { text: 'Category', style: 'tableHeader'},
               { text: 'Score Sheet Title', style: 'tableHeader'},
               { text: 'Total Score', style: 'tableHeader'},
              ],
              ...this.lists.map(item => {
                return [ 
                  item.quarter.label,
                  item.year.year,
                  item.section.full,
                  item.subject.label,
                  item.criteria.label,
                  item.score_sheet_name,
                  item.total_score
                ];
              })
            ]
          }
        }
       ]
      };
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
    openModal() {
      var self = this;
      self.showModalData = true;
    },
    close(){
      var self = this;
      self.showModalData = false;
      self.getScoreSheetList();
    }
  }

};
</script>
