<template>
  <div class="mb-5">
    <h3 class="m-3 text-header">
      <div class="d-flex justify-content-between">
        <div>
          Student Report
        </div>
        <div>
          <button class="btn btn-primary" @click="print()" :disabled="isPreview || subject_grades.length == 0"> 
            Print Preview
            <span v-show="isPreview"><i class="fa fa-refresh fa-spin"></i></span>
          </button>
        </div>
      </div>
    </h3>
    <div class="col-lg-6 mx-auto printMe" ref="canvas" id="printCard">
      <div class="d-flex justify-content-between">
        <div class="col-lg-3 px-0">
          <img :src="card_logo['card_logo']" class="img-fluid logo-size">
        </div>
        <div class="col-lg-6 px-0 text-center">
          <h4 class="mb-2">New Era University</h4>
          <div class="text-uppercase font-weight-bold mb-2 font-12">Integrated School Department</div>
          <div class="font-12">No.9 Central Ave. New Era, Quezon City</div>
        </div>
        <div class="col-lg-3 px-0">
          <img :src="card_logo['card_logo2']" class="img-fluid logo-size">
        </div>
      </div>
      <div class="text-center my-2">
        <h5 class="text-uppercase font-weight-bold mb-1">Progress Report Card (F-138)</h5>
        <h6>Academic Year {{ academic_year_value }}</h6>
      </div>
      <div class="mt-4">
        <div class="w-100">
          <div class="border border-dark col-lg-12 font-12">
            <div class="">
              <div class="mx-2 mt-2 font-italic">Name</div>
              <div class="mx-2 mb-2 text-uppercase text-center font-weight-bold"> {{ info.last_name }}, {{ info.first_name }}</div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="row justify-content-between font-12">
              <div class="col-lg-6 border border-dark border-right-0 border-bottom-0">
                <div class="mx-2 mt-2 font-italic">Stud No</div>
                <div class="mx-2 mb-2 text-capitalize text-center font-weight-bold"> {{ info.student_id }} </div>
              </div>
              <div class="col-lg-6 border border-dark border-bottom-0">
                <div class="mx-2 mt-2 font-italic">Grade and Section</div>
                <div class="mx-2 mb-2 text-capitalize text-center font-weight-bold"> {{ year_section }} </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="row justify-content-between font-12">
              <div class="col-lg-4 border border-dark border-right-0">
                <div class="mx-2 mt-2 font-italic">Gender</div>
                <div class="mx-2 mb-2 text-capitalize text-center font-weight-bold"> {{ info.gender }} </div>
              </div>
              <div class="col-lg-4 border border-dark border-right-0">
                <div class="mx-2 mt-2 font-italic">Age</div>
                <div class="mx-2 mb-2 text-capitalize text-center font-weight-bold"> {{ info.age }} </div>
              </div>
              <div class="col-lg-4 border border-dark">
                <div class="mx-2 mt-2 font-italic">LRN</div>
                <div class="mx-2 mb-2 text-capitalize text-center font-weight-bold"> N/A </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12 mt-2">
            <div class="row justify-content-between">
              <div class="col-lg-5 border border-dark border-right-0 font-12">
                <div class="mx-2 mt-4"></div>
                <div class="mx-2 mb-2 text-uppercase text-center font-weight-bold"> Subjects</div>
              </div>
              <div class="col-lg-4 border border-dark border-right-0 font-12">
                <div class="mt-2 text-center">Quarter</div>
                <div class="row justify-content-between">
                  <div class="col-lg-3 border border-dark border-right-0 border-left-0">
                    <div class="mb-2 text-uppercase text-center font-weight-bold">1</div>
                  </div>
                  <div class="col-lg-3 border border-dark border-right-0">
                    <div class="mb-2 text-uppercase text-center font-weight-bold">2</div>
                  </div>
                  <div class="col-lg-3 border border-dark border-right-0">
                    <div class="mb-2 text-uppercase text-center font-weight-bold">3</div>
                  </div>
                  <div class="col-lg-3 border border-dark border-right-0">
                    <div class="mb-2  text-uppercase text-center font-weight-bold">4</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 border border-dark">
                <div class="mx-2 mt-4"></div>
                <div class="mx-2 mb-2 text-capitalize text-center font-weight-bold font-12">Final Grade</div>
              </div>
            </div>
          </div>
          <div class="col-lg-12" v-for="subject_grade in subject_grades">
            <div class="row justify-content-between" v-if="subject_grade.subject != null">
              <div class="col-lg-5 border border-dark border-right-0">
                <div class="mx-2 my-2 text-uppercase text-left font-weight-bold font-12"> {{ subject_grade.subject.label }}</div>
              </div>
              <div class="col-lg-4 border border-dark">
                <div class="row h-100" v-if="subject_grade.quarterly_grade.length > 0">
                  <div class="col-lg-3 border border-dark" v-for="quarterly in subject_grade.quarterly_grade">
                    <div class="my-2 text-uppercase text-center font-weight-bold h-100 font-12">{{ quarterly.final_grade }}</div>
                  </div>
                  <div class="col-lg-3 border border-dark" v-if="subject_grade.quarterly_grade.length === 1">
                    <div class="my-2 text-uppercase text-center font-weight-bold h-100 text-white font-12"> - </div>
                  </div>
                  <div class="col-lg-3 border border-dark" v-if="subject_grade.quarterly_grade.length === 1 || subject_grade.quarterly_grade.length === 2">
                    <div class="my-2 text-uppercase text-center font-weight-bold h-100 text-white font-12"> - </div>
                  </div>
                  <div class="col-lg-3 border border-dark" v-if="subject_grade.quarterly_grade.length === 1 || subject_grade.quarterly_grade.length === 2 || subject_grade.quarterly_grade.length === 3">
                    <div class="my-2 text-uppercase text-center font-weight-bold h-100 text-white font-12"> - </div>
                  </div>
                </div>
                <div class="row h-100" v-else>
                  <div class="col-lg-3 border border-dark">
                    <div class="my-2 text-uppercase text-center font-weight-bold h-100 text-white font-12"> - </div>
                  </div>
                  <div class="col-lg-3 border border-dark">
                    <div class="my-2 text-uppercase text-center font-weight-bold h-100 text-white font-12"> - </div>
                  </div>
                  <div class="col-lg-3 border border-dark">
                    <div class="my-2 text-uppercase text-center font-weight-bold h-100 text-white font-12"> - </div>
                  </div>
                  <div class="col-lg-3 border border-dark">
                    <div class="my-2 text-uppercase text-center font-weight-bold h-100 text-white font-12"> - </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 border border-dark">
                <div class="my-2 text-uppercase text-center font-weight-bold h-100 font-12" :class="subject_grade.quarterly_grade.length === 4 ? 'text-dark' : 'text-white'"> 
                  {{ subject_grade.quarterly_grade.length === 4 ? subject_grade.subject_final_grade : '-'}}
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12">

            <div class="row justify-content-between">
              <div class="col-lg-9 border border-dark border-right-0">
                <div class="mx-2 my-2 text-uppercase text-left font-weight-bold font-12"> General Average</div>
              </div>
              <div class="col-lg-3 border border-dark">
                <div class="my-2 text-uppercase text-center font-weight-bold h-100 font-12"> {{ compute_gwa }} </div>
              </div>
            </div>
          </div>

          <div class="col-lg-12 mt-2">
            <div class="row justify-content-between">
              <div class="col-lg-6 border border-dark border-right-0">
                <div class="mb-2 text-capitalize font-weight-bold"> Grade Equivalent </div>
                <table class="col-lg-12 font-12">
                  <tr>
                    <td>Numeric</td>
                    <td>Alphabetic</td>
                    <td>Descriptive</td>
                  </tr>
                  <tr>
                    <td>
                      95 - 100
                    </td>
                    <td>
                      A
                    </td>
                    <td>
                      Excellent
                    </td>
                  </tr>
                  <tr>
                    <td>
                      90 - 94
                    </td>
                    <td>
                      B+
                    </td>
                    <td>
                      Very Good
                    </td>
                  </tr>
                  <tr>
                    <td>
                      85 - 89
                    </td>
                    <td>
                      B
                    </td>
                    <td>
                      Good
                    </td>
                  </tr>
                  </tr>
                </table>
              </div>
              <div class="col-lg-6 border border-dark">
                <table class="col-lg-12 mt-4 font-12">
                  <tr>
                    <td>Numeric</td>
                    <td>Alphabetic</td>
                    <td>Descriptive</td>
                  </tr>
                  <tr>
                    <td>
                      80 - 84
                    </td>
                    <td>
                      C+
                    </td>
                    <td>
                      Satisfactory
                    </td>
                  </tr>
                  <tr>
                    <td>
                      75 - 79
                    </td>
                    <td>
                      C
                    </td>
                    <td>
                      Acceptable
                    </td>
                  </tr>
                  <tr>
                    <td>
                      0 - 74
                    </td>
                    <td>
                      D
                    </td>
                    <td>
                      Unsatisfactory
                    </td>
                  </tr>
                  </tr>
                </table>
              </div>

              <div class="mt-2">
                <table class="table table-bordered px-0 w-100">
                  <tr>
                    <td scope="col">Attendance Record</td>
                    <td scope="col">Jun</td>
                    <td scope="col">Jul</td>
                    <td scope="col">Aug</td>
                    <td scope="col">Sep</td>
                    <td scope="col">Oct</td>
                    <td scope="col">Nov</td>
                    <td scope="col">Dec</td>
                    <td scope="col">Jan</td>
                    <td scope="col">Feb</td>
                    <td scope="col">Mar</td>
                    <td scope="col">Apr</td>
                    <td scope="col">Total</td>
                  </tr>
                  <tr>
                    <td>No. of School Days</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>Days Present</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                  </tr>
                    <td>No. of Times</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </table>
              </div>
              <div class="col-lg-12 mt-1 px-0">
                <h6 class="text-uppercase font-weight-bold mb-3"> 
                  <u>To The Parents</u>
                </h6>
                <p class="text-dark mb-2 font-12">
                  This <span class="font-weight-bold text-uppercase"> Progress Report Card</span> shows the periodic performance of your chid in the different subject areas.
                </p>
                <p class="text-dark mb-4 font-12">
                  Please feel free to confer the Teachers and the Integrated School Administration in PTCA meetings or by appointment if you have some queries on his/her standing in class or if you want to know more about his/her achievement.
                </p>
                <div class="d-flex justify-content-center align-items-center col-lg-12 font-12">
                  <div class="col-lg-6 text-center">
                    <p class="text-dark font-weight-bold text-uppercase font-12">
                      {{ adviser_fullname }}
                    </p>
                    Adviser
                  </div>
                  <div class="col-lg-6 text-center">
                    <p class="text-dark font-weight-bold text-uppercase font-12">
                      {{ principal_fullname }}
                    </p>
                    Principal
                  </div>
                </div>
                <div class="mt-2">
                  <p class="text-dark font-12">
                    Eligable for transfer and admission to __________________________________________________
                  </p>
                  <p class="text-dark font-12">
                    Lacks credit/s in __________________________________________________
                  </p>
                </div>
                <div class="border border-dark"></div>
                <div class="border border-dark"></div>
                <div class="mt-2">
                  <p class="text-dark text-uppercase font-weight-bold text-center font-12">
                    Cancellation of Transfer Eligibility
                  <p class="text-dark font-12">
                    Has been admitted to _____________________ Date ___________________
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- OUTPUT -->
      <div id="printMe"  :class="isPreview || output ? 'd-block' : 'd-none'">
        <div class="col-lg-6 px-0 mx-auto">
          <img :src='output' style="height: 100%; max-height: 960px;">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import printJS from 'print-js';
export default {
  props:['user'],
  components: {
  },
  data() {
    return {
      isPreview: false,
      academic_year_id: '',
      academic_year: '',
      student_id: '',
      info: '',
      authority: '',
      subject_grades: [],
      gwa: 0,
      total_subject: 0,
      card_logos: '',
      output: null,
    };
  },
  computed: {
    academic_year_value(){
      return this.academic_year ? this.academic_year.from_year + ' - ' + this.academic_year.to_year : '';
    },
    year_section(){
      return this.info ? this.info.year.year + ' - ' + this.info.section.section + ' (' + this.info.section.label + ')' : '';
    },
    adviser_fullname(){
      return this.authority ? this.authority.adviser.first_name + ' ' + this.authority.adviser.middle_name + ' ' + this.authority.adviser.last_name : '';
    },
    principal_fullname(){
      return this.authority ?this.authority.principal.info.first_name + ' ' + this.authority.principal.info.middle_name + ' ' + this.authority.principal.info.last_name : '';
    },
    card_logo(){
      return this.card_logos ? this.card_logos : '/assets/default-logo.png';
    },
    compute_gwa(){
      let gwa = 0;
      this.subject_grades.forEach((data,index) =>{
        let final_grade = data.subject_final_grade;
        gwa += final_grade;
      });
      let com_gwa = gwa / this.total_subject;
      this.gwa = com_gwa.toFixed(2);
      return this.gwa;
    }

  },
  created(){

  },
  methods: {
    getAcademicYear(){
      var self = this;
      axios.get('/get-academic-year')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.academic_year = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getInfo(){
      var self = this;
      axios.get('/get-user-data',{
        params:{
          user_id: self.student_id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.info = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSubjectGrade(){
      var self = this;
      axios.get('/get-subject-grade',{
        params:{
          user_id: self.student_id,
          academic_year_id: self.academic_year_id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.subject_grades = result;
        self.total_subject = result.length;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getAdviserPrincipal(){
      var self = this;
      axios.get('/get-adviser-principal',{
        params:{
          user_id: self.user.id,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.authority = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getCardLogos(){
      var self = this;
      axios.get('/get-card-logos')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.card_logos = result;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    print(){
      this.isPreview = true;
      const el = this.$refs.canvas;
      const options = {
        type: 'dataURL'
      }
      this.$html2canvas(el, options).then((canvas) => {
        this.output = canvas;
        setTimeout(() => { 
          printJS({
            printable: 'printMe',
            type: 'html',
            targetStyles: ['*']
          });
          this.isPreview = false;
          this.output = null;
        }, 500);
      });
    }

  },
  mounted(){
    let uri = window.location.search.substring(1); 
    let params = new URLSearchParams(uri);
    this.academic_year_id = params.get("academic_year_id");
    this.student_id = params.get("id");
    if((this.student_id == null || this.student_id == '')){
      this.$swal({
        position: 'center',
        icon: 'info',
        title: 'No Student Selected',
        showConfirmButton: true,
      }).then(()=>{
        window.close()
      });
    }else{
      this.getAcademicYear();
      this.getInfo();
      this.getSubjectGrade();
      this.getAdviserPrincipal();
      this.getCardLogos();
    }
  }

};
</script>
