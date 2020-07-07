<template>
  <div>
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
    section_id: Number,
    academic_year_id: Number,
  },
  components: {
  },
  created(){
    this.getStudentLlistBySectionId();
  },
  data() {
    return {
      lists: [],
      isBusy: false,
      sortBy: 'id',
      sortDesc: false,
      fields:[
          {
            key: 'id',
            label: '#',
            variant: 'success',
          },
          {
            key: 'student_id',
            label: 'Student Number',
            sortable: true,
          },
          {
            key: 'full_name',
            label: 'Student Name',
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
    section_id: function (newValue) {
      this.getStudentLlistBySectionId();
    }
  },
  methods: {
    getStudentLlistBySectionId(){
      var self = this;
      self.isBusy = true;
      axios.get('/get-student-list', {
        params: {
          section_id: self.section_id,
          academic_year_id: self.academic_year_id
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
