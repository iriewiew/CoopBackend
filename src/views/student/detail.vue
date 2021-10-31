<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>แก้ไขข้อมูลนักศึกษา</h1>
        <v-spacer></v-spacer>
      </v-card-title>
      <v-spacer></v-spacer>

      <v-toolbar-items>
        <v-btn flat icon @click="submit('form')">
          <v-icon>save</v-icon>
        </v-btn>
      </v-toolbar-items>
    </v-toolbar>

    <v-card-title>
      <h1></h1>
      <v-spacer></v-spacer>
    </v-card-title>
    <v-form>
      <v-container>
        <v-layout row wrap>
          <v-flex xs12 sm12 md12>
            <v-text-field
              v-model="form.user_id"
              label="รหัสนักศึกษา"
              v-validate="'required|max:11|numeric|min:11'"
              :counter="11"
              :error-messages="errors.collect('user_id')"
              data-vv-name="user_id"
              required
              disabled
            ></v-text-field>
          </v-flex>

          <v-flex xs12 md2>
            <v-select
              item-text="title"
              item-value="title"
              v-model="form.prefix_name"
              :items="prefix_name"
              label="คำนำหน้า"
              v-validate="'required'"
              :error-messages="errors.collect('selectPrefix')"
              data-vv-name="selectPrefix"
              required
            />
          </v-flex>
          <v-flex xs12 md5>
            <v-text-field
              v-model="form.first_name"
              label="ชื่อ"
              v-validate="'required'"
              :error-messages="errors.collect('first_name')"
              data-vv-name="first_name"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12 md5>
            <v-text-field
              v-model="form.last_name"
              label="นามสกุล"
              v-validate="'required'"
              :error-messages="errors.collect('last_name')"
              data-vv-name="last_name"
              required
            ></v-text-field>
          </v-flex>

          <v-flex xs12 md6 @click="form.major_name=null">
            <v-select
              item-text="faculty_name"
              item-value="id"
              v-model="form.faculty_name"
              :items="facultyData"
              label="คณะ"
              v-validate="'required'"
              :error-messages="errors.collect('selectfaculty')"
              data-vv-name="selectfaculty"
              required
            />
          </v-flex>

          <v-flex v-if="viewSelect" xs12 md6 @click="viewSelect=false,getMajorFacultyData();">
            <v-select
              no-data-text="ไม่พบข้อมูล"
              item-text="major_name"
              item-value="id"
              v-model="form.major_name"
              :items="majorData"
              label="สาขาวิชา"
              v-validate="'required'"
              :error-messages="errors.collect('selectMajor')"
              data-vv-name="selectMajor"
              required
            />
          </v-flex>

          <v-flex v-else xs12 md6 @click="getMajorFacultyData();">
            <v-select
              no-data-text="ไม่พบข้อมูล"
              item-text="major_name"
              item-value="id"
              v-model="form.major_name"
              :items="majorFacultyData"
              label="สาขาวิชา"
              v-validate="'required'"
              :error-messages="errors.collect('selectMajor')"
              data-vv-name="selectMajor"
              required
            />
          </v-flex>
          <v-flex xs12>
            <v-select
              v-model="form.std_year"
              :items="years"
              label="ปีการศึกษา"
              v-validate="'required'"
              :error-messages="errors.collect('std_year')"
              data-vv-name="std_year"
              required
            />
          </v-flex>
          <!-- <v-flex xs12 md12>
            <v-text-field
              v-model="form.email"
              label="อีเมล"
              v-validate="'required|email'"
              :error-messages="errors.collect('email')"
              data-vv-name="email"
              required
              type="email"
            ></v-text-field>
          </v-flex>-->
          <!-- <v-btn @click="clear">clear</v-btn> -->
        </v-layout>
      </v-container>
    </v-form>
  </v-app>
</template>
<script>
import axios from "axios";

export default {
  mounted() {
    this.loadMajorView();
    this.getStudentData();
    this.getData();
    this.$validator.localize("th", this.dictionary);
  },
  data() {
    return {
      viewSelect: true,
      facultyData: "",
      majorFacultyData: "",
      majorData: [],
      prefix_name: [
        {
          title: "นาย"
        },
        {
          title: "นาง"
        },
        {
          title: "นางสาว"
        }
      ],
      form: {
        id: "",
        prefix_name: "",
        first_name: "",
        last_name: "",
        role_name: "student",
        major_name: "",
        faculty_name: "",
        email: "",
        std_year: ""
      },
      dictionary: {
        attributes: {
          email: "E-mail Address"
          // custom attributes
        },
        custom: {
          user_id: {
            required: () => "โปรดกรอกรหัสนักศึกษา",
            min: "รหัสนักศึกษาควรเป็นตัวเลขจำนวน 11 หลัก",
            max: "รหัสนักศึกษาควรเป็นตัวเลขไม่เกิน 11 หลัก",
            numeric: "รหัสนักศึกษาควรเป็นตัวเลขเท่านั้น"
            // custom messages
          },
          first_name: {
            required: () => "โปรดกรอกชื่อ",
            alpha: "ไม่ควร"

            // custom messages
          },
          last_name: {
            required: () => "โปรดกรอกนามสกุล"

            // custom messages
          },
          selectPrefix: {
            required: "โปรดเลือกคำนำหน้า"
          },
          selectfaculty: {
            required: "โปรดเลือกคณะ"
          },
          selectMajor: {
            required: "โปรดเลือกสาขาวิชา"
          },
          email: {
            required: () => "โปรดกรอกอีเมล",
            email: "รูปแบบอีเมลไม่ถูกต้อง"
          },
          std_year: {
            required: "โปรดเลือกปีการศึกษา"
          }
        }
      }
    };
  },
  methods: {
    loadMajorView() {
      setTimeout(() => {
        this.getMajorData();
      }, 400);
    },
    getStudentData: function() {
      let apiURL = "http://localhost:1337/member/";
      let getID = this.$route.params.studentID;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.form = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    saveData: function() {
      let apiURL = "http://localhost:1337/member/update";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      this.axios
        .post(apiURL, this.form, setting)
        .then(response => {
          console.log("ok2");
          Swal.fire({
            title: "บันทึกข้อมูลเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            console.log("ok3");
            this.$router.push("/student");
            console.log(response);
          });
        })
        .catch(err => {
          Swal.fire({
            type: "error",
            title: "เกิดข้อผิดพลาด",
            showConfirmButton: false,
            timer: 1500
          });
          console.log(err);
        });
    },

    getData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get("http://localhost:1337/faculty/datatable", setting)
        .then(
          response => (
            (this.facultyData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    getMajorData: function() {
      let apiURL = "http://localhost:1337/major/";
      let getID = this.form.major_name;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.majorData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    getMajorFacultyData: function() {
      let apiURL = "http://localhost:1337/faculty/major/";
      let getID = this.form.faculty_name;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.majorFacultyData = response.data.data),
            (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    submit(form) {
      this.$validator.validateAll().then(result => {
        if (result) {
          // eslint-disable-next-line
          this.saveData();
          return;
        }
      });
    },
    backButton: function() {
      this.$router.push("/student");
    }
  },
  computed: {
    years() {
      const year = new Date().getFullYear() + 543;
      return Array.from(
        { length: year - 2558 },
        (value, index) => 2559 + index
      );
    }
  }
};
</script>
<style scoped>
</style>
