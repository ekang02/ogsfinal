<template>
  <div class="">
    <h3 class="m-3 text-header">
        Dashboard
    </h3>
    <div class="col-lg-12">
      <div class="card shadow mb-4">
        <div class="card-header text-center">
          School Year {{ schoolYear }}
        </div>
      </div>
    </div>
    <hr/>
    <div class="row col-lg-12">
      <div class="col-lg-3 col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary">
                <div class="d-flex justify-content-between text-white">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-3x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge1">
                          {{ info.total_teacher }}
                        </div>
                        <div>Total Teacher</div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-success">
                <div class="d-flex justify-content-between text-white">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-3x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge1">
                          {{ info.total_student }}
                        </div>
                        <div>Total Student</div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary">
                <div class="d-flex justify-content-between text-white">
                    <div class="col-xs-3">
                        <i class="fa fa-male fa-3x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge1">
                          {{ info.total_student_male }}
                        </div>
                        <div>Total Male Student</div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-success">
                <div class="d-flex justify-content-between text-white">
                    <div class="col-xs-3">
                        <i class="fa fa-female fa-3x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge1">
                          {{ info.total_student_female }}
                        </div>
                        <div>Total Female Student</div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    <hr/>
    <div class="col-lg-12">
      <div class="card shadow mb-4">
          <div class="card-header">
            Student Per Sections
          </div>
          <div class="card-body">
            <div class="input-group mb-5">
              <div class="input-group-prepend">
                <label class="input-group-text bg-primary" for="inputGroupSelect01">Filter By Grade</label>
              </div>
              <select v-model="year" class="custom-select" id="inputGroupSelect01">
                  <option v-for="yr in years" :value="yr.id"> {{ yr.year }}</option>
              </select>
            </div>
            <bar-chart :year="year"></bar-chart>
          </div>
      </div>
    </div>
  </div>
</template>

<script>
import BarChart from '../../Chart/BarChart.vue';
export default {
  props:['user'],
  components: {
    BarChart,
  },
  data() {
    return {
      info: '',
      year: '',
      years: [],

    };
  },
  computed: {
    schoolYear(){
      return this.info ? this.info.current_school_year.from_year + ' - ' + this.info.current_school_year.to_year : '';
    }
  },
  created(){
    this.getData();
    this.getYear();
  },
  methods: {
    getData(){
      var self = this;
      axios.get('/principal/get-dashboard-data')
      .then(function (response) {
        // handle success
        var result = response.data;
        self.info = result;
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

  }

};
</script>
