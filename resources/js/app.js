/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require("regenerator-runtime/runtime");

window.Vue = require('vue');
window.moment = require('moment');

import BootstrapVue from 'bootstrap-vue';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueHtmlToPaper from 'vue-html-to-paper';
import Multiselect from 'vue-multiselect';
import VueTimepicker from 'vue2-timepicker';
import 'vue2-timepicker/dist/VueTimepicker.css';
import Datepicker from 'vuejs-datepicker';
import VueHtml2Canvas from 'vue-html2canvas';

const options = {
  confirmButtonColor: '#5cb85c',
  cancelButtonColor: '#d33'
}

const htmloptions = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
   'https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css'
  ]
}

Vue.use(VueHtmlToPaper, htmloptions);
Vue.use(VueSweetalert2,options);
Vue.use(BootstrapVue);
Vue.use(VueHtml2Canvas);
// Vue.use(printJS);
Vue.component('multiselect', Multiselect);
Vue.component('vue-timepicker', VueTimepicker);
Vue.component('datepicker', Datepicker);


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Common
Vue.component('profile', require('./components/Common/Profile/Profile.vue').default);

//Admin
Vue.component('adminprincipal', require('./components/Admin/Users/Principal.vue').default);
Vue.component('adminteacher', require('./components/Admin/Users/Teacher.vue').default);
Vue.component('adminstudent', require('./components/Admin/Users/Student.vue').default);

Vue.component('adminsection', require('./components/Admin/YearSection/Section.vue').default);
Vue.component('adminyear', require('./components/Admin/YearSection/Year.vue').default);

Vue.component('adminsubject', require('./components/Admin/Subject/Subject.vue').default);
Vue.component('adminsubjectsection', require('./components/Admin/Subject/SubjectSection.vue').default);

Vue.component('adminacademicyear', require('./components/Admin/Settings/AcademicYear.vue').default);
Vue.component('adminassignprincipal', require('./components/Admin/Settings/AssignPrincipal.vue').default);
Vue.component('adminimages', require('./components/Admin/Settings/Images.vue').default);



//Principal
Vue.component('principaldashboard', require('./components/Principal/Dashboard/Dashboard.vue').default);
Vue.component('principalstaff', require('./components/Principal/Users/Staff.vue').default);
Vue.component('principalteacher', require('./components/Principal/Users/Teacher.vue').default);
Vue.component('principalstaff', require('./components/Principal/Users/Student.vue').default);
Vue.component('principalstudent', require('./components/Principal/Users/Student.vue').default);
Vue.component('principalsection', require('./components/Principal/YearSection/Section.vue').default);
Vue.component('principalyear', require('./components/Principal/YearSection/Year.vue').default);
Vue.component('principalclassadvisory', require('./components/Principal/YearSection/ClassAdvisory.vue').default);
Vue.component('principalsubjectteacher', require('./components/Principal/Subject/SubjectTeacher.vue').default);
Vue.component('principalsubjectsection', require('./components/Principal/Subject/SubjectSection.vue').default);
Vue.component('principalsubjectstudent', require('./components/Principal/Subject/SubjectStudent.vue').default);
Vue.component('principalsubject', require('./components/Principal/Subject/Subject.vue').default);
Vue.component('principalgrade', require('./components/Principal/Grade/Subject.vue').default);
Vue.component('principalcriteria', require('./components/Principal/Grade/Criteria.vue').default);
Vue.component('principalacademicyear', require('./components/Principal/Grade/AcademicYear.vue').default);
Vue.component('principalassessmentlevel', require('./components/Principal/Grade/AssessmentLevel.vue').default);
Vue.component('principalgradeperiod', require('./components/Principal/Grade/Period.vue').default);
Vue.component('principalgradequarter', require('./components/Principal/Grade/Quarter.vue').default);
Vue.component('principalstudents', require('./components/Principal/Search/Students.vue').default);
Vue.component('principalsubjects', require('./components/Principal/Search/Subjects.vue').default);
Vue.component('principalscoresheets', require('./components/Principal/Search/ScoreSheets.vue').default);

//Teacher
Vue.component('teacherdashboard', require('./components/Teacher/Dashboard/Dashboard.vue').default);
Vue.component('scoresheet', require('./components/Teacher/Scoresheet/Scoresheet.vue').default);
Vue.component('addscoresheet', require('./components/Teacher/Scoresheet/AddScoresheet.vue').default);
Vue.component('scoresheetlist', require('./components/Teacher/Scoresheet/ScoresheetList.vue').default);
Vue.component('scoresheetlistprint', require('./components/Teacher/Scoresheet/ScoresheetListPrint.vue').default);
Vue.component('subject', require('./components/Teacher/Subject/Subject.vue').default);
Vue.component('reportperiod', require('./components/Teacher/Report/Period.vue').default);
Vue.component('reportterm', require('./components/Teacher/Report/Term.vue').default);
Vue.component('reportprogress', require('./components/Teacher/Report/Progress.vue').default);
Vue.component('reportprogressstudent', require('./components/Teacher/Report/Student.vue').default);
Vue.component('teacherclassadvisory', require('./components/Teacher/ClassAdvisory/ClassAdvisory.vue').default);

//Student
Vue.component('studentgrade', require('./components/Student/MyGrades.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
