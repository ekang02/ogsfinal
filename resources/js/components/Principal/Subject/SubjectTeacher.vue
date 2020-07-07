<template>
  <div class="">
    <h3 class="m-3 text-header">
        Subject Teacher
    </h3>
    <hr/>
    <div class="mx-3">
      <div class="row justify-content-between">
        <div class="form-group col-sm-4">
          <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Assign Subject To Teacher</h6>
            </div>
            <div class="card-body">
                <div class="row">
                  <div class="form-group col-sm-12">
                    <label>School Year</label>
                    <input type="text":value="academic_year_value" placeholder="School Year" class="form-control" readonly="true">
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Grade</label>
                    <select v-model="year" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingYear }" @change="getSectionByYear()">
                        <option v-for="year in years" :value="year"> {{ year.year }}</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Section</label>
                    <select v-model="section" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSection }" @change="getSubjectBySection()" :disabled="isSectionDisabled">
                        <option v-for="section in sections" :value="section"> {{ section.section }} ({{ section.label }})</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Subject</label>
                    <select v-model="subject" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingSubject }" :disabled="isSubjectDisabled">
                        <option v-for="sub in subjects" :value="sub.subject"> {{ sub.subject.label }}</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Teachers</label>
                    <select v-model="teacher" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingTeacher }">
                        <option v-for="teacher in teachers" :value="teacher"> {{ teacher.full_name }}</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Subject Days</label>
                      <multiselect 
                          v-model="days" 
                          tag-placeholder="Add this as day"
                         placeholder="Search or add a day" 
                         label="label" 
                         track-by="id" 
                         :options="dayOptions" 
                         :multiple="true" 
                         :taggable="true" 
                         :close-on-select="false"
                         @tag="addTag" 
                         :class="{ 'is-invalid': attemptSubmit && missingDay }">
                      </multiselect>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Subject Time</label>
                    <div>                      
                      <vue-timepicker v-model="start_time" placeholder="Start Time" hide-disabled-items format="hh:mm A" :hour-range="[['7a', '12p'],['1p', '8p']]" close-on-complete input-width="8em" :class="{ 'is-invalid': attemptSubmit && missingStartTime }"></vue-timepicker> -
                      <vue-timepicker v-model="end_time" placeholder="End Time" hide-disabled-items format="hh:mm A" :hour-range="[['7a', '12p'],['1p', '8p']]" close-on-complete input-width="8em" :class="{ 'is-invalid': attemptSubmit && missingEndTime }"></vue-timepicker>
                    </div>
                  </div>
                </div>
                <div class="text-right">
                  <button class="btn btn-outline-secondary" @click="clear()">Clear</button>
                  <button class="btn btn-primary" @click="validateForm">Save</button>
                </div>
            </div>
          </div>
        </div>
        <div class="form-group col-sm-8">
          <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Subject Teacher List</h6>
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
                      <span class="input-group-text" id="basic-addon2">Search</span>
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
                @filtered="onFiltered"
              >
                <div slot="table-busy" class="text-center my-2">
                  <b-spinner class="align-middle"></b-spinner>
                  <strong>Loading...</strong>
                </div>

                <template v-slot:cell(show_details)="row">
                  <b-button size="sm" @click="row.toggleDetails" class="mr-2">
                    {{ row.detailsShowing ? 'Hide' : 'Show'}} Details
                  </b-button>
                </template>
                 <template v-slot:row-details="row">
                  <b-card class="p-0">
                    <b-table :fields="nested_fields" :items="row.item.schedule_section">
                      <template v-slot:cell(actions)="row">
                        <button class="btn btn-primary" @click="editSchedule(row.item)">
                          Edit
                        </button>
                        <button class="btn btn-danger" @click="deleteSchedule(row.item.id)">
                          Delete
                        </button>
                      </template>
                    </b-table>
                  </b-card>
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
    </div>
    <modalEditSchedule v-if="showModalData" :showModalData.sync="showModalData" :selectedData.sync="selectedData" @close="close()"></modalEditSchedule>
  </div>
</template>

<script>
import 'vue2-timepicker/dist/VueTimepicker.css';
import modalEditSchedule from '../../Modal/modalEditSchedule.vue';
export default {
  props:['user'],
  components: {
    modalEditSchedule  
  },
  data() {
    return {
      showModalData: false,
      process: false,
      selectedData: '',
      academic_year: '',
      years: [],
      sections: [],
      subjects: [],
      teachers: [],
      teacher: '',
      subject: '',
      year: '',
      section: '',
      days:'',
      start_time: '',
      end_time: '',
      dayOptions:[
        {'id':1,'label':'Mon', value:'M'},
        {'id':2,'label':'Tue', value:'T'},
        {'id':3,'label':'Wed', value:'W'},
        {'id':4,'label':'Thu', value:'TH'},
        {'id':5,'label':'Fri', value:'F'},
      ],
      attemptSubmit: false,
      loaded: false,
      lists: [],
      isBusy: false,
      sortBy: 'id',
      sortDesc: true,
      fields:[
          {
            key: 'id',
            label: '#',
            sortable: true,
            variant: 'success'
          },
          {
            key: 'user_info.full_name',
            label: 'Fullname',
            sortable: true
          },
          {
            key: 'academic_year.academic',
            label: 'School Year',
            sortable: true
          },
          {
            key: 'year.year',
            label: 'Grade',
            sortable: true
          },
          {
            key: 'subject.label',
            label: 'Subject',
            sortable: true,
          },
          {
            key: 'subject.subject_code',
            label: 'Subject Code',
            sortable: true,
          },
          {
            key: 'show_details',
            label: '',
            'class': 'text-center' 
          },

          // {
          //   key: 'actions',
          //   label: 'Actions',
          //   'class': 'text-center' 
          // },
      ],
      nested_fields:[
          {
            key: 'section.full',
            label: 'Section',
            sortable: true,
            variant: 'success'
          },
          {
            key: 'days',
            label: 'Scheduled Days',
            sortable: true
          },
          {
            key: 'start_time',
            label: 'Start Time',
            sortable: true,
          },
          {
            key: 'end_time',
            label: 'End Time',
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
    this.getAcademicYear();
    this.getTeachers();
    this.getYear();
  },
  computed: {
    academic_year_value(){
      return this.academic_year ? this.academic_year.from_year + ' - ' + this.academic_year.to_year : '';
    },
    missingSubject: function () { 
      return this.subject == ''; 
    },
    missingYear: function () { 
      return this.year == ''; 
    },
    missingSection: function () { 
      return this.Section == ''; 
    },
    missingTeacher: function () { 
      return this.teacher == ''; 
    },
    missingDay: function () { 
      return this.days == ''; 
    },
    missingStartTime: function () { 
      return this.start_time == ''; 
    },
    missingEndTime: function () { 
      return this.end_time == ''; 
    },

    isSubjectDisabled:function(){
      return this.subjects == '';
    },
    isSectionDisabled:function(){
      return this.sections == '';
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
        self.getSubjectTeacherList();
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getTeachers(){
      var self = this;
      axios.get('/get-teachers')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.teachers = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    
    getYear(){
      var self = this;
      self.sections = [];
      axios.get('/get-year')
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
          year_id: this.year.id,
          academic_year_id: this.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.sections = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSubjectBySection(){
      var self = this;
      self.subjects = [];
      axios.get('/get-subject',{
        params:{
          section_id: self.section.id,
          academic_year_id: this.academic_year.id
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.subjects = result.subject_sections;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSubjectTeacherList(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-subject-teacher',{
        params: {
          academic_year_id: this.academic_year.id
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
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      var self = this;
      self.totalRows = filteredItems.length
      self.currentPage = 1
    },
    validateForm: function (event) {
      this.attemptSubmit = true;
      if (this.missingYear || this.missingSection || this.missingSubject || this.missingTeacher || this.missingDay || this.missingStartTime || this.missingEndTime){
        event.preventDefault();
      }else{
        this.save();
      }
    },

    save(){
      var self = this;
      if(self.process === true){
        return;
      }
      self.process = true;
      self.$swal({
        title: 'Are you sure?',
        text: "You want to Add New Subject to Teacher?",
        type: 'info',
        icon: "info",
        showCancelButton: true,
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.value) {
          self.$swal({
              html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Adding.....</div>",
              showConfirmButton: false,
              allowOutsideClick: false
          });
          axios.post( 'save-subject-teacher', {
              user_id: this.teacher.user_id,
              academic_year_id: this.academic_year.id,
              year_id: this.year.id,
              section_id: this.section.id,
              subject_id: this.subject.id,
              days: this.days,
              start_time: this.start_time,
              end_time: this.end_time,
          })
          .then(function (response) {
              self.process = false
              self.clear();
              self.getSubjectTeacherList();
              self.$swal(
                'Saved!',
                'Successfully Added New Subjec to Teacher.',
                'success'
              )
          })
          .catch(function (error) {
              console.log(error);
              self.$swal({
                position: 'center',
                type: 'error',
                icon: 'error',
                title: error.response.data.message !== '' ? error.response.data.message : 'Something went wrong!',
                showConfirmButton: true,
              })
              self.process = false
          });
        }else{
            self.process = false
        }
      })
    },

    editSchedule(data){
      var self = this;
      self.$swal({
          title: "Edit this Scheduled Subject?",
          text: "Are you sure?",
          type: "warning",
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#38c172",
          confirmButtonText: "Yes"
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
            self.selectedData = data
            self.openModal();
          }
      });

    },
    deleteSchedule(id){
      var self = this;
      self.$swal({
          title: "Are you sure to delete this Scheduled Subject?",
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
             axios.post('delete-subject-teacher-schedule', {
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
                self.getSubjectTeacherList();
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
    clear(){
      this.process = false
      this.attemptSubmit = false;
      this.year = '';
      this.section = '';
      this.sections = [];
      this.subject = '';
      this.subjects = [];
      this.teacher = '';
      this.days = '';
      this.start_time = '';
      this.end_time = '';
    },

    openModal() {
      var self = this;
      self.showModalData = true;
    },
    close(){
      var self = this;
      self.showModalData = false;
      self.getSubjectTeacherList();
    },
    addTag (newTag) {
      const tag = {
        name: newTag,
        code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
      }
      this.options.push(tag)
      this.value.push(tag)
    }
  }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>