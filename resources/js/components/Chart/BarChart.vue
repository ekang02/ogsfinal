<script>
import { Bar } from 'vue-chartjs'
export default {
  extends: Bar,
  props:['year'],
  data: function () {
    return {
      chartData: {
        labels: [],
        datasets: [
          {
            label: 'Data',
            backgroundColor: '#6ac6bb',
            pointBackgroundColor: 'white',
            borderWidth: 1,
            pointBorderColor: '#249EBF',
            data: []
          }
        ]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            },
            gridLines: {
              display: true
            }
          }],
          xAxes: [{
            ticks: {
              beginAtZero: true
            },
            gridLines: {
              display: false
            }
          }]
        },
        legend: {
          display: false
        },
        tooltips: {
          enabled: true,
          mode: 'single',
          callbacks: {
            label: function(tooltipItems, data) {
              return tooltipItems.yLabel;
            }
          }
        },
        responsive: true,
        maintainAspectRatio: false,
        height: 200
      }
    }
  },
  mounted () {
    this.getData();
  },
  methods: {
    getData(){
      var self = this;
      // this.chartData is created in the mixin
      axios.get('/principal/get-section-bar-chart',{
        params:{
          year_id : self.year,
        }
      })
      .then(function (response) {
        // handle success
        var result = response.data;
        self.chartData.labels = result.map(item => item.labels);
        self.chartData.datasets[0].data = result.map(item => item.total_student_per_section);
        self.renderChart(self.chartData, self.options); 
      })
      .catch(function (error) {
        console.log(error);
      }); 
    }
  },
  watch: { 
    year: function(newVal, oldVal) { // watch it
      this.getData();
    }
  }
};
</script>
