<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="mb-2">
                    <h1 class="h3 mb-4 text-gray-800">Profile Setting</h1>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-white">Profile</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
<!--                             <div class="col-md-12 align-self-center">
                                <picture-input 
                                    ref="pictureInput" 
                                    @change="onChange" 
                                    width="200" 
                                    height="200" 
                                    margin="16" 
                                    accept="image/jpeg,image/png" 
                                    size="2" 
                                    radius="50"
                                    :crop="true"
                                    :prefill= userImage
                                    :prefillOptions="{mediaType: 'image/jpeg'}"
                                    :toggleAspectRatio="true"
                                    :autoToggleAspectRatio="true"
                                    :alertOnError="true"
                                    :zIndex="1"
                                    buttonClass="btn btn-success"
                                    :customStrings="{
                                          upload: '<p>Your device does not support file uploading.</p>', // HTML allowed
                                          drag: 'Drag an image or <br>click here to select a file', // HTML allowed
                                          tap: 'Tap here to select a photo <br>from your gallery', // HTML allowed
                                          change: 'Change Photo', // Text only
                                          remove: 'Remove Photo', // Text only
                                          select: 'Select a Photo', // Text only
                                          selected: '<p>Photo successfully selected!</p>', // HTML allowed
                                          fileSize: 'The file size exceeds the limit', // Text only
                                          fileType: 'This file type is not supported.', // Text only
                                          aspect: 'Landscape/Portrait' // Text only
                                        }">
                                </picture-input>                                                        
                            </div> -->
                            <div class="col">
                                <div class="form-group has-success">
                                    <label>First Name</label>
                                    <input type="text" v-model="info.first_name" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingFirstName }" readonly>
                                    <div class="invalid-feedback">This field is required.</div>
                                </div>
                                <div class="form-group has-success">
                                    <label>Middle Name</label>
                                    <input v-model="info.middle_name" class="form-control" readonly>
                                </div>
                                <div class="form-group has-success">
                                    <label>Last Name</label>
                                    <input type="text" v-model="info.last_name" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingLastName }" readonly>
                                    <div class="invalid-feedback">This field is required.</div>
                                </div>
                                <div class="form-group has-success">
                                    Email Address:
                                    <input type="email" v-model="user.email" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingEmail }" readonly>
                                    <div class="invalid-feedback">This field is required.</div>
                                </div>
                            </div>
                            <div class="col">
                                <!-- <div class="form-group has-success">
                                    Birth Date:
                                    <input type="text" v-model="info.date_of_birth" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingBirthDate }">
                                    <div class="invalid-feedback">This field is required.</div>
                                </div> -->
                                <div class="form-group has-success">
                                  Birth Date:
                                  <datepicker 
                                    v-model="state.date" 
                                    name="date_of_birth"
                                    @selected="getAge" 
                                    :format="customFormatter"  
                                    :minimumView="'day'" 
                                    :maximumView="'year'" 
                                    :initialView="'month'"
                                    :highlighted="highlighted"
                                    :disabledDates="state.disabledDates"                          
                                    input-class="form-control bg-white"
                                    maxlength="15">
                                  </datepicker>
                                </div>
                                <div class="form-group has-success">
                                    Address:
                                    <input type="text" v-model="info.address" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingAddress }">
                                    <div class="invalid-feedback">This field is required.</div>
                                </div>
                                <div class="form-group has-success">
                                    Mobile Number:
                                    <input type="text" v-model="info.phone_number" class="form-control" :class="{ 'is-invalid': attemptSubmit && missingPhoneNumber }" @keypress="isNumber($event)" maxlength="13">
                                    <div class="invalid-feedback">This field is required.</div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button @click="validateForm" class="btn btn-primary col-sm-12">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PictureInput from 'vue-picture-input';
    export default {
        components: {
            PictureInput
        },
        props: ['user'],
        created(){
          this.getUserInfo();
        },
        data() {
            return{
                process: false,
                attemptSubmit: false,
                info: '',
                highlighted: {},
                state : {   
                  date: '', 
                  disabledDates: {
                    from: new Date(),
                  }
                }
            }
        },
        methods: {
            getUserInfo(){
              var self = this;
              axios.get('/get-user-info',{
                params:{
                  id: this.user.id
                }
              })
              .then(function (response) {
                // handle success
                var result = response.data;
                self.info = result;
                self.state.date = result.date_of_birth;
              })
              .catch(function (error) {
                console.log(error);
                self.$swal({
                    position: 'center',
                    icon: 'error',
                    title: 'Something went wrong',
                    showConfirmButton: true,
                })
              });
            },
            onChange(image){
              console.log('New picture selected!')
              if (image) {
                console.log('Picture loaded.')
                // this.image = image
                this.user.avatar = image;
                // console.log(image)
              } else {
                console.log('FileReader API not supported: use the <form>!')
              }
            },

            validateForm: function (event) {
              this.attemptSubmit = true;
              if (this.missingFirstName || this.missingLastName || this.missingMobileNumber || this.missingBirthDate || this.missingAddress || this.missingPhoneNumber || this.missingEmail){
                event.preventDefault();
              }else{
                this.updateAccount();
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
            clear(){
                var self = this;
                self.attemptSubmit = false;
            },
            customFormatter(date) {
              return moment(date).format('MM/DD/YYYY');
            },
            getAge(elem){
              let selectedDate =  moment(elem).format('MM/DD/YYYY');
              this.info.date_of_birth = selectedDate;

            },
            updateAccount(){
                var self = this;
                if(self.process === true){
                    return;
                }
                self.process = true;
                self.$swal({
                  title: 'Are you sure?',
                  text: "You want to update your profile?",
                  icon: 'info',
                  showCancelButton: true,
                  confirmButtonText: 'Yes, update it!'
                }).then((result) => {
                  if (result.value) {
                    axios.post('/update-account', {
                        user_role: self.user.user_role,
                        info: self.info
                    })
                    .then(function (response) {
                        self.$swal(
                          'Updated!',
                          'Your account successfully updated.',
                          'success'
                        )
                        self.process = false
                    })
                    .catch(function (error) {
                        self.$swal({
                            position: 'center',
                            icon: 'error',
                            title: 'Something went wrong',
                            showConfirmButton: true,
                        })
                        self.process = false
                    });
                  }else{
                      self.process = false
                  }
                })
            },
        },
        computed:{
            userImage(){
                if(this.user.avatar){
                   return `${this.user.avatar}`;
                }else{
                    return '/assets/userid.png';
                }
            },
            missingFirstName: function () { 
                return this.info.first_name == ''; 
            },
            missingLastName: function () { 
                return this.info.last_name == ''; 
            },
            missingEmail: function () { 
                return this.info.email == ''; 
            },
            missingPhoneNumber: function () { 
                return this.info.phone_number == ''; 
            },
            missingBirthDate: function () { 
                return this.info.birth_date == ''; 
            },
            missingAddress: function () { 
                return this.info.address == ''; 
            },
            missingEmail: function () { 
                return this.user.email == ''; 
            },
        },
    };
</script>
