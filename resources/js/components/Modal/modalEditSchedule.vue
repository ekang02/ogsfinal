<template>
    <div v-if="showModalData">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Schedule {{ header }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" @click="$emit('close')">&times;</span>
                </button>
              </div>
              <div class="modal-body">                
                <div class="row">
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
  name: 'modalData',
  props: {
    showModalData: Boolean,
    selectedData: Object,
  },
  components: {
  },
  data(){
    return {
      attemptSubmit: false,
      isLoading: false,
      process: false,
      schedule_id: this.selectedData.id,
      days:[],
      start_time: this.selectedData.start_time,
      end_time: this.selectedData.end_time,
      dayOptions:[
        {'id':1,'label':'Mon', value:'M'},
        {'id':2,'label':'Tue', value:'T'},
        {'id':3,'label':'Wed', value:'W'},
        {'id':4,'label':'Thu', value:'TH'},
        {'id':5,'label':'Fri', value:'F'},
      ],
    }
  },
  created(){
    this.mapSchedule();
  },
  methods: {
    
    save(){
      var self = this;
      if(self.process === true){
          return;
      }
      self.isLoading = true;
      self.process = true;
      axios.post( 'update-subject-teacher-schedule', {
          schedule_id: self.schedule_id,
          days: self.days,
          start_time: self.start_time,
          end_time: self.end_time,
      })
      .then(function (response) {
          self.process = false;
          self.isLoading = false;
          self.$emit('close');
      })
      .catch(function (error) {
          console.log(error);
          self.process = false
      });
    },
    addTag (newTag) {
      const tag = {
        name: newTag,
        code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
      }
      this.options.push(tag)
      this.value.push(tag)
    },
    mapSchedule(){
      let daysArr = this.selectedData.days.split(',');
      daysArr.forEach((data, index) => {
        let dayLabel = data.trim();
        let day = _.find(this.dayOptions, (obj) => { return dayLabel === obj.label });
        this.days.push(day);
      });

    }
  },
  computed:{
    header: function () { 
      return this.selectedData == '' ? '' : this.selectedData.section.full + ' - ' + this.selectedData.subject.label; 
    },
    missingDay: function () { 
      return this.days.length === 0; 
    },
    missingStartTime: function () { 
      return this.start_time == ''; 
    },
    missingEndTime: function () { 
      return this.end_time == ''; 
    },
  }

};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
