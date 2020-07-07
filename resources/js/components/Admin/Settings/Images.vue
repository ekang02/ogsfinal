<template>
  <div class="">
    <div class="d-flex justify-content-between align-items-center">
      <h3 class="m-3 text-header">
          Setup Images
      </h3>       
    </div>
    <hr/>
    <div class="row">
      
      <div class="col-sm-4">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Setup System Logo</h6>
          </div>
          <div class="card-body">
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
              :prefill= defaultImage
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
                    change: 'Change Logo', // Text only
                    remove: 'Remove Logo', // Text only
                    select: 'Select a Logo', // Text only
                    selected: '<p>Logo successfully selected!</p>', // HTML allowed
                    fileSize: 'The file size exceeds the limit', // Text only
                    fileType: 'This file type is not supported.', // Text only
                    aspect: 'Landscape/Portrait' // Text only
                  }">
            </picture-input> 
          </div>
        </div>
      </div>

      <div class="col-sm-4">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Setup Card Logo</h6>
          </div>
          <div class="card-body">

            <picture-input 
              ref="pictureInput" 
              @change="onCardChange" 
              width="200" 
              height="200" 
              margin="16" 
              accept="image/jpeg,image/png" 
              size="2" 
              radius="50"
              :crop="true"
              :prefill= defaultCardImage
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
                    change: 'Change Logo', // Text only
                    remove: 'Remove Logo', // Text only
                    select: 'Select a Logo', // Text only
                    selected: '<p>Logo successfully selected!</p>', // HTML allowed
                    fileSize: 'The file size exceeds the limit', // Text only
                    fileType: 'This file type is not supported.', // Text only
                    aspect: 'Landscape/Portrait' // Text only
                  }">
            </picture-input>
          </div>
        </div>
      </div>

      <div class="col-sm-4">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Setup Card Second Logo</h6>
          </div>
          <div class="card-body">

            <picture-input 
              ref="pictureInput" 
              @change="onCardSecondChange" 
              width="200" 
              height="200" 
              margin="16" 
              accept="image/jpeg,image/png" 
              size="2" 
              radius="50"
              :crop="true"
              :prefill= defaultCardSecondImage
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
                    change: 'Change Logo', // Text only
                    remove: 'Remove Logo', // Text only
                    select: 'Select a Logo', // Text only
                    selected: '<p>Logo successfully selected!</p>', // HTML allowed
                    fileSize: 'The file size exceeds the limit', // Text only
                    fileType: 'This file type is not supported.', // Text only
                    aspect: 'Landscape/Portrait' // Text only
                  }">
            </picture-input>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import PictureInput from 'vue-picture-input';
export default {
  props:['user'],
  components: {
    PictureInput
  },
  data() {
    return {
      system_logo: '',
      card_logo: '',
      card_second_logo: '',
    };
  },
  created(){
  },
  mounted(){
    this.getImages();

  },
  computed: {
    defaultImage(){
        return this.system_logo; // ? this.system_logo : '/assets/default-logo.png';
    },
    defaultCardImage(){
        return this.card_logo; // ? this.card_logo : '/assets/default-logo.png';
    },
    defaultCardSecondImage(){
        return this.card_second_logo; // ? this.card_second_logo : '/assets/default-logo.png';
    },

  },
  methods: {
    onChange(image){
      var self = this;
      console.log('New picture selected!');
      self.$swal({
          title: 'Are you sure?',
          text: "You want to update system logo?",
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: "#38c172",
          confirmButtonText: "Yes"
      }).then((result) => {
        if (result.value) {
          if (image) {
            console.log('Picture loaded.');
            self.$swal({
                html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Saving.....</div>",
                showConfirmButton: false,
                allowOutsideClick: false
            });
            axios.post('save-system-image', {
              type: 'system_logo',
              image: image,
            })
            .then(function (response) {
              self.$swal({
                position: 'center',
                icon: 'success',
                title: 'Successfully Saved',
                showConfirmButton: true,
              })
            })
            .catch(function (error) {
                self.$swal({
                  position: 'center',
                  icon: 'error',
                  title: 'Error Occured',
                  showConfirmButton: true,
                })
            });
            // this.image = image
            this.system_logo = image;
            // console.log(image)
          } else {
            console.log('FileReader API not supported: use the <form>!')
          }
        }
      })
    },
    onCardChange(image){
      var self = this;
      console.log('New picture selected!');
      self.$swal({
          title: 'Are you sure?',
          text: "You want to update card logo?",
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: "#38c172",
          confirmButtonText: "Yes"
      }).then((result) => {
        if (result.value) {
          if (image) {
            console.log('Picture loaded.');
            self.$swal({
                html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Saving.....</div>",
                showConfirmButton: false,
                allowOutsideClick: false
            });
            axios.post('save-system-image', {
              type: 'card_logo',
              image: image,
            })
            .then(function (response) {
              self.$swal({
                position: 'center',
                icon: 'success',
                title: 'Successfully Saved',
                showConfirmButton: true,
              })
            })
            .catch(function (error) {
                self.$swal({
                  position: 'center',
                  icon: 'error',
                  title: 'Error Occured',
                  showConfirmButton: true,
                })
            });
            // this.image = image
            this.card_logo = image;
            // console.log(image)
          } else {
            console.log('FileReader API not supported: use the <form>!')
          }
        }
      })
    },
    onCardSecondChange(image){
      var self = this;
      console.log('New picture selected!');
      self.$swal({
          title: 'Are you sure?',
          text: "You want to update card second logo?",
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: "#38c172",
          confirmButtonText: "Yes"
      }).then((result) => {
        if (result.value) {
          if (image) {
            console.log('Picture loaded.');
            self.$swal({
                html: "<div class='spinner-border text-success' style='width: 8rem; height: 8rem;' role='status'></div><div class='mt-5 mb-3 h1'>Saving.....</div>",
                showConfirmButton: false,
                allowOutsideClick: false
            });
            axios.post('save-system-image', {
              type: 'card_second_logo',
              image: image,
            })
            .then(function (response) {
              self.$swal({
                position: 'center',
                icon: 'success',
                title: 'Successfully Saved',
                showConfirmButton: true,
              })
            })
            .catch(function (error) {
                self.$swal({
                  position: 'center',
                  icon: 'error',
                  title: 'Error Occured',
                  showConfirmButton: true,
                })
            });
            // this.image = image
            this.card_second_logo = image;
            // console.log(image)
          } else {
            console.log('FileReader API not supported: use the <form>!')
          }
        }
      })
    },
    getImages(){
      var self = this;
      axios.get('get-images')
      .then(function (response) {
        // handle success
        var result = response.data;
        let system_logo = result.find((obj) => { return obj['type'] === 'system_logo'});
        let card_logo = result.find((obj) => { return obj['type'] === 'card_logo'});
        let card_second_logo = result.find((obj) => { return obj['type'] === 'card_second_logo'});
        self.system_logo = system_logo ? system_logo.image : null;
        self.card_logo = card_logo ? card_logo.image : null;
        self.card_second_logo = card_second_logo ? card_second_logo.image : null;
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
    }

  }
};
</script>
